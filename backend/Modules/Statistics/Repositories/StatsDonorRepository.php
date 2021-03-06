<?php

namespace Modules\Statistics\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Payment\Entities\Donation;
use Modules\Payment\Entities\Payment;
use Modules\UserManagement\Entities\PortalUser;

class StatsDonorRepository implements StatsDonorRepositoryInterface
{

    protected $model;

    // donations_sum
    private $donationsSum;

    // first donation and date for portal user, when he successfully made first donation monthly group
    private $firstDonationMonthly;

    // first donation and date for portal user, when he successfully made first donation
    private $firstDonation;

    private $lastDonationAt;

    private $lastDonation;

    function __construct()
    {
        $this->model = PortalUser::class;
        $this->donationsSum = Donation::query()
            ->select('portal_user_id', DB::raw('sum(amount) as amount_sum'),
                DB::raw('MIN(created_at) as first_donation_at'))
            ->where('status', 'processed')
            ->groupBy('portal_user_id');

        $this->firstDonationMonthly = Donation::query()
            ->select('portal_user_id', 'is_monthly_donation',
                DB::raw('MIN(created_at) as first_donation_at'))
            ->groupBy('portal_user_id', 'is_monthly_donation');

        // first donation and date for portal user, when he successfully made first donation
        $this->firstDonation = Donation::query()
            ->select('portal_user_id', DB::raw('MIN(created_at) as first_donation_at'))
            ->where('status', 'processed')
            ->groupBy('portal_user_id');

        // get date of last donation
        $this->lastDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->where('status', 'processed')
            ->groupBy('portal_user_id');

        // last donation detail
        $this->lastDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'created_at');

    }

    public function getDonors($from, $to, $monthly)
    {
        $payment = Payment::query()
            ->select('id as payment_id', 'transaction_date')
            ->whereDate('transaction_date', '>=', $from)
            ->whereDate('transaction_date', '<=', $to);

        // get date of last donation
        $lastDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->whereNotNull('payment_id')
            ->groupBy('portal_user_id');

        //if monthly is set, filter lastDonationAt
        if ($monthly !== null) {
            $lastDonationAt = $lastDonationAt->where('is_monthly_donation', $monthly);
        }

        // last donation detail
        $lastDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'created_at', 'payment_id');

        return PortalUser::query()
            ->joinSub($lastDonationAt, 'last_donation_at', function ($join) {
                $join->on('portal_users.id', '=', 'last_donation_at.portal_user_id');
            })
            ->joinSub($lastDonation, 'last_donation', function ($join) {
                $join->on('last_donation_at', '=', 'last_donation.created_at');
            })
            ->joinSub($payment, 'payment', function ($join) {
                $join->on('last_donation.payment_id', '=', 'payment.payment_id');
            })
            ->joinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->orderBy('last_donation_at', 'DESC')
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('variableSymbol')
            ->with('userPaymentOptions')
            ->with('firstDonation.widget.campaign')
            ->with('lastUserDonation.paymentMethod')
            ->get();
    }

    public function countOfNewDonors($from, $to)
    {
        $firstPayment = Payment::query()
            ->select(DB::raw('MIN(payments.transaction_date) as first_payment_date'),
                'donations.portal_user_id as first_portal_user_id')
            ->join('donations', 'payments.id', '=', 'donations.payment_id')
            ->orderBy('first_portal_user_id')
            ->groupBy('first_portal_user_id');

        $payment = Payment::query()
            ->select('payments.transaction_date as current_payment_date',
                'donations.portal_user_id as portal_user_id',
                'donations.is_monthly_donation as is_monthly_donation')
            ->whereDate('transaction_date', '>=', $from)
            ->whereDate('transaction_date', '<=', $to)
            ->join('donations', 'payments.id', '=', 'donations.payment_id')
            ->groupBy('payments.id', 'portal_user_id', 'is_monthly_donation');

        //get only those portal users who made first donation at specific time period
        return PortalUser::query()
            ->select(DB::raw('count(portal_users.id)'), 'is_monthly_donation')
            ->joinSub($firstPayment, 'first_payment', function ($join) {
                $join->on('portal_users.id', '=', 'first_payment.first_portal_user_id');
            })
            ->joinSub($payment, 'payment', function ($join) {
                $join->on('portal_users.id', '=', 'payment.portal_user_id');
            })
            ->with('userPaymentOptions')
            ->whereRaw('current_payment_date = first_payment_date')
            ->groupBy('is_monthly_donation')
            ->get();
    }

    public function getDonorsNew($from, $to, $monthly)
    {

        $firstPayment = Payment::query()
            ->select(DB::raw('MIN(payments.transaction_date) as first_payment_date'),
                'donations.portal_user_id as first_portal_user_id')
            ->join('donations', 'payments.id', '=', 'donations.payment_id')
            ->orderBy('first_portal_user_id')
            ->groupBy('first_portal_user_id');

        $payment = Payment::query()
            ->select('payments.transaction_date as current_payment_date',
                'donations.portal_user_id as portal_user_id',
                'donations.is_monthly_donation as is_monthly_donation')
            ->whereDate('transaction_date', '>=', $from)
            ->whereDate('transaction_date', '<=', $to)
            ->join('donations', 'payments.id', '=', 'donations.payment_id')
            ->groupBy('payments.id', 'portal_user_id', 'is_monthly_donation');

        // get date of last donation
        $lastDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->whereNotNull('payment_id')
            ->groupBy('portal_user_id');

        //if monthly is set, filter lastDonationAt
        if ($monthly !== null) {
            $lastDonationAt = $lastDonationAt->where('is_monthly_donation', $monthly);
        }

        // last donation detail
        $lastDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'created_at', 'payment_id');

        //get only those portal users who made first donation at specific time period
        return PortalUser::query()
            ->joinSub($lastDonationAt, 'last_donation_at', function ($join) {
                $join->on('portal_users.id', '=', 'last_donation_at.portal_user_id');
            })
            ->joinSub($lastDonation, 'last_donation', function ($join) {
                $join->on('last_donation_at', '=', 'last_donation.created_at');
            })
            ->joinSub($firstPayment, 'first_payment', function ($join) {
                $join->on('portal_users.id', '=', 'first_payment.first_portal_user_id');
            })
            ->joinSub($payment, 'payment', function ($join) {
                $join->on('portal_users.id', '=', 'payment.portal_user_id');
            })
            ->leftJoinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->joinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->whereRaw('current_payment_date = first_payment_date')
            ->orderBy('last_donation_at', 'DESC')
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('variableSymbol')
            ->with('firstDonation.widget.campaign')
            ->with('lastUserDonation.paymentMethod')
            ->with('userPaymentOptions')
            ->get();
    }

    public function getDonorsStoppedSupporting($stopAfterDate, $limit)
    {
        // get date of last donation
        $lastMonthlyDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->where('is_monthly_donation', true)
            ->groupBy('portal_user_id');

        // last donation detail
        $lastMonthlyDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'created_at');

        $resultQuery = PortalUser::query()
            ->joinSub($lastMonthlyDonationAt, 'latest_donation_group', function ($join) {
                $join->on('portal_users.id', '=', 'latest_donation_group.portal_user_id');
            })
            ->joinSub($lastMonthlyDonation, 'latest_donation', function ($join) {
                $join->on('last_donation_at', '=', 'latest_donation.created_at');
            })
            ->joinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->joinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->orderBy('last_donation_at', 'DESC')
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('variableSymbol')
            ->with('firstDonation.widget.campaign')
            ->with('userPaymentOptions')
            ->with('lastUserDonation.paymentMethod')
            ->whereDate('last_donation_at', '<=', $stopAfterDate);
        if ($limit !== null) {
            $resultQuery = $resultQuery->limit($limit);
        }
        return $resultQuery->get();
    }


    public function getDonorsStoppedSupportingCount($stopAfterDate)
    {
        // get date of last donation
        $lastMonthlyDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->where('is_monthly_donation', true)
            ->groupBy('portal_user_id');

        // last donation detail
        $lastMonthlyDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'created_at');

        return PortalUser::query()
            ->joinSub($lastMonthlyDonationAt, 'latest_donation_group', function ($join) {
                $join->on('portal_users.id', '=', 'latest_donation_group.portal_user_id');
            })
            ->joinSub($lastMonthlyDonation, 'latest_donation', function ($join) {
                $join->on('last_donation_at', '=', 'latest_donation.created_at');
            })
            ->joinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->joinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->orderBy('last_donation_at', 'DESC')
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('variableSymbol')
            ->with('firstDonation.widget.campaign')
            ->with('userPaymentOptions')
            ->with('lastUserDonation.paymentMethod')
            ->whereDate('last_donation_at', '<=', $stopAfterDate)->count();
    }

    public function didNotPay($from, $to, $limit)
    {
        // get date of last donation
        $lastDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->groupBy('portal_user_id');

        // last donation detail
        $lastDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'status', 'created_at');

        $resultQuery = PortalUser::query()
            ->joinSub($lastDonationAt, 'lastDonationAt', function ($join) {
                $join->on('portal_users.id', '=', 'lastDonationAt.portal_user_id');
            })
            ->joinSub($lastDonation, 'lastDonation', function ($join) {
                $join->on('last_donation_at', '=', 'lastDonation.created_at');
            })
            ->joinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->joinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->orderBy('last_donation_at', 'DESC')
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('variableSymbol')
            ->with('userPaymentOptions')
            ->with('firstDonation.widget.campaign')
            ->with('lastUserDonation.paymentMethod')
            ->where('lastDonation.status', 'waiting_for_payment');
        if ($limit !== null) {
            $resultQuery = $resultQuery->limit($limit);
        }
        return $resultQuery->get();
    }

    public function didNotPayCount($from, $to)
    {
        // get date of last donation
        $lastDonationAt = Donation::query()
            ->select('portal_user_id', DB::raw('MAX(created_at) as last_donation_at'))
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->groupBy('portal_user_id');

        // last donation detail
        $lastDonation = Donation::query()
            ->select('portal_user_id', 'amount as last_donation_value', 'is_monthly_donation as last_donation_monthly',
                'payment_method as last_donation_payment_method', 'status', 'created_at');

        $resultQuery = PortalUser::query()
            ->joinSub($lastDonationAt, 'lastDonationAt', function ($join) {
                $join->on('portal_users.id', '=', 'lastDonationAt.portal_user_id');
            })
            ->joinSub($lastDonation, 'lastDonation', function ($join) {
                $join->on('last_donation_at', '=', 'lastDonation.created_at');
            })
            ->joinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->joinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->with('userPaymentOptions')
            ->with('lastUserDonation.paymentMethod')
            ->where('lastDonation.status', 'waiting_for_payment');

        return $resultQuery->count();
    }

    public function onlyInitializeDonation($from, $to, $limit = null)
    {
        //get only these donations, that have status initialized and are in time interval
        $onlyInitializeDonation = Donation::query()
            ->select('donations.portal_user_id as portal_user_id', 'campaigns.name as campaign_name', 'campaigns.id as campaign_id')
            ->join('widgets', 'widget_id', '=', 'widgets.id')
            ->join('campaigns', 'campaign_id', '=', 'campaigns.id')
            ->where('donations.status', 'initialized')
            ->whereDate('donations.created_at', '>=', $from)
            ->whereDate('donations.created_at', '<=', $to)
            ->orderBy('portal_user_id', 'ASC');

        $resultQuery = PortalUser::query()
            ->joinSub($onlyInitializeDonation, 'onlyInitializeDonation', function ($join) {
                $join->on('portal_users.id', '=', 'onlyInitializeDonation.portal_user_id');
            })
            ->leftJoinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->leftJoinSub($this->lastDonationAt, 'lastDonationAt', function ($join) {
                $join->on('portal_users.id', '=', 'lastDonationAt.portal_user_id');
            })
            ->leftJoinSub($this->lastDonation, 'lastDonation', function ($join) {
                $join->on('last_donation_at', '=', 'lastDonation.created_at');
            })
            ->leftJoinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('userPaymentOptions')
            ->with('variableSymbol');
        if ($limit !== null) {
            $resultQuery = $resultQuery->limit($limit);
        }
        return $resultQuery->get();
    }

    public function getAllPortalUsers($from, $to)
    {
        return PortalUser
            ::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->leftJoinSub($this->donationsSum, 'donations_sum', function ($join) {
                $join->on('portal_users.id', '=', 'donations_sum.portal_user_id');
            })
            ->leftJoinSub($this->lastDonationAt, 'lastDonationAt', function ($join) {
                $join->on('portal_users.id', '=', 'lastDonationAt.portal_user_id');
            })
            ->leftJoinSub($this->firstDonation, 'first_donation', function ($join) {
                $join->on('portal_users.id', '=', 'first_donation.portal_user_id');
            })
            ->with('userPaymentOptions')
            ->with('user.userDetail')
            ->with('isMonthlyDonor')
            ->with('variableSymbol')
            ->with('firstDonation.widget.campaign')
            ->with('lastUserDonation.paymentMethod')
            ->get();
    }

}