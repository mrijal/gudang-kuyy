<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [
        'id'
    ];

    public function inbounds()
    {
        return $this->hasMany(Inbound::class);
    }
}
