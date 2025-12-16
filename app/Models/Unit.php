<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';
    protected $primaryKey = 'unit_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_unit',
        'kode_unit',
        'deskripsi',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'unit_id', 'unit_id');
    }
}