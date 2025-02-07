<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $guarded = [
        'id'
    ];

    public function details()
    {
        return $this->hasMany(InboundDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
