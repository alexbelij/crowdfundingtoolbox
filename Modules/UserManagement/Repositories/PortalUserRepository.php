<?php


namespace Modules\UserManagement\Repositories;


use Modules\UserManagement\Entities\PortalUser;

class PortalUserRepository implements PortalUserRepositoryInterface
{
    protected $model;

    function __construct()
    {
        $this->model = PortalUser::class;
    }

    public function all()
    {
        return $this->model
            ::with('donations')
            ->with('isMonthlyDonor')
            ->get();
    }

    public function get($userId)
    {
        return $this->model
            ::where('user_id', $userId)
            ->first();
    }


    public function create($userId)
    {
        return $this->model
            ::create([
                'user_id' => $userId
            ]);
    }

    public function getDonationsByUser($id)
    {
        return $this->model
            ::where('id', $id)
            ->with('donations')
            ->first();
    }

    public function getAllWithDonations()
    {
        return $this->model
            ::has('donations')
            ->with('donations')
            ->has('user')
            ->with('user.userDetail')
            ->with('visit')
            ->get();
    }

    public function isMonthlyDonor($id, $date)
    {
        return $this->model
            ::where('id', $id)
            ->has('donations')
            ->where('donations.is_monthly_donation', '=', true)
            ->where('donations.created_at', '<=', $date)
            ->first();
    }

}