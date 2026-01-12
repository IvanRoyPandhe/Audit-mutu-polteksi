<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'pelaksanaan_id',
        'is_read',
        'sent_by'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by', 'user_id');
    }

    public function pelaksanaan()
    {
        return $this->belongsTo(\stdClass::class, 'pelaksanaan_id', 'pelaksanaan_id');
    }
}