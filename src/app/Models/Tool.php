<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = [
        'company_id',
        'tool_category_id',
        'name',
        'maker',
        'model',
        'stock_quantity',
        'reorder_point',
        'note',
        'manages_stock',
    ];

    protected $casts = [
        'manages_stock' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function toolCategory()
    {
        return $this->belongsTo(ToolCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function magazinePots()
    {
        return $this->belongsToMany(MagazinePot::class, 'magazine_pot_tools');
    }

    public function standbySets()
    {
        return $this->belongsToMany(StandbySet::class, 'standby_set_tools');
    }
}
