<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadItem extends Model
{
    /** @use HasFactory<\Database\Factories\LeadItemFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'companyname',
        'streetname',
        'housenumber',
        'zipcode',
        'city',
        'emailadres',
        'phonenumber',
        'ipaddress',
        'internal_note',
        'lead_status_id',
        'lead_channel_id',
        'lead_category_id',
    ];

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function leadChannel()
    {
        return $this->belongsTo(LeadChannel::class);
    }

    public function leadCategory()
    {
        return $this->belongsTo(LeadCategory::class);
    }
}
