<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            do {
                $code = 'COMP-' . strtoupper(Str::random(8));
            } while (Company::where('company_code', $code)->exists());

            $company->company_code = $code;
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function companys()
    {
        return $this->hasMany(Company::class);
    }

    public function toolCategories()
    {
        return $this->hasMany(ToolCategory::class);
    }
}
