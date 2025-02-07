<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [
        'id'
    ];

    public function inbounds()
    {
        return $this->hasMany(Inbound::class);
    }

    public function inboundsDetail()
    {
        return $this->hasMany(InboundDetail::class);
    }

    public function opnames()
    {
        return $this->hasMany(Opname::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }
}
