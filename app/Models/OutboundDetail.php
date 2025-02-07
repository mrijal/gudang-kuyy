<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundDetail extends Model
{
    protected $guarded = [
        'id'
    ];

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
