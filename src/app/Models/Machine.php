<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'machine_number',
        'maker',
        'model',
        'location',
        'magazine_capacity',
        'available_spots',
        'is_active',
        'note',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function magazinePots()
    {
        return $this->hasMany(MagazinePot::class);
    }
}
