<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
