<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagazinePot extends Model
{
    protected $fillable = ['machine_id', 'pot_number', 'is_disabled'];

    protected $casts = ['is_disabled' => 'boolean'];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'magazine_pot_tools')->orderByPivot('id');
    }
}
