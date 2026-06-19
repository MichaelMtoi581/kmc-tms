<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Staff members belonging to this department.
     * (Staff model/migration is built in the next module.)
     */
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
