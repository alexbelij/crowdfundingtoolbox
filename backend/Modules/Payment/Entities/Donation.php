<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Donation extends Model
{
    use Notifiable, SoftDeletes;

    protected $table = 'donations';
    protected $fillable = [
        'amount',
        'is_monthly_donation',
        'payment_method',
        'portal_user_id',
        'payment_id',
        'widget_id',
        'status',
        'tracking_show_id',
        'amount_initialized',
    ];
    protected $casts = [
        'amount' => 'float',
    ];

    public function portalUser()
    {
        return $this->belongsTo('\Modules\UserManagement\Entities\PortalUser');
    }

    public function widget()
    {
        return $this->belongsTo('\Modules\Campaigns\Entities\Widget');
    }

    public function widgetReferral()
    {
        return $this->hasOne('\Modules\Campaigns\Entities\Widget', 'id', 'referral_widget_id');
    }

    public function payment()
    {
        return $this->hasOne('\Modules\Payment\Entities\Payment', 'id', 'payment_id');
    }

    public function trackingShow()
    {
        return $this->belongsTo('\Modules\UserManagement\Entities\TrackingShow', 'tracking_show_id');
    }

    public function paymentMethod()
    {
        return $this->hasOne('\Modules\Payment\Entities\PaymentMethod', 'id', 'payment_method');
    }

}
