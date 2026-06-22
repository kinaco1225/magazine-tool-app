<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'company_id',
        'tool_id',
        'quantity',
        'status',
        'ordered_at',
        'received_at',
        'note',
    ];

    protected $casts = [
        'ordered_at'  => 'date',
        'received_at' => 'date',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
