<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandbySet extends Model
{
    protected $fillable = ['company_id', 'machine_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class)->withDefault(['name' => '不明']);
    }

    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'standby_set_tools');
    }
}
