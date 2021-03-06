<?php

namespace Modules\Campaigns\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;
    protected $table = 'campaigns';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'active', 'description', 'date_from', 'date_to', 'headline_text', 'updated_at','prevent_disable'
    ];

    public function targeting() {
        return $this->hasOne('\Modules\Targeting\Entities\Targeting');
    }

    public function promote() {
        return $this->hasOne('\Modules\Campaigns\Entities\CampaignPromote');
    }

    public function widget() {
        return $this->hasMany('\Modules\Campaigns\Entities\Widget');
    }

}
