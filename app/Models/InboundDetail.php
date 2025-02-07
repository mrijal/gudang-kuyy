<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundDetail extends Model
{
    protected $guarded = [
        'id'
    ];

    public function inbound()
    {
        return $this->belongsTo(Inbound::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
