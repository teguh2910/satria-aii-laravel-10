<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sj extends Model
{
    protected $table = 'sjs';

    protected $fillable = [
        'tanggal_delivery',
        'customer_code',
        'pdsnumber',
        'doaii',
        'sj_balik',
        'terima_finance',
        'invoice',
        'user_ppic_scan',
        'user_finance_scan',
    ];

    protected $casts = [
        'tanggal_delivery' => 'date:Y-m-d',
        'sj_balik' => 'datetime',
        'terima_finance' => 'datetime',
    ];
}
