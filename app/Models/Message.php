<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'body', 'file_path', 'file_type', 'file_name', 'read_at', 'deleted_at'];

    protected $casts = [
        'read_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function isGroupMessage(): bool
    {
        return is_null($this->receiver_id);
    }

    public function isDeleted(): bool
    {
        return !is_null($this->deleted_at);
    }

    public function canDelete(): bool
    {
        if ($this->sender_id !== auth()->id()) return false;
        return $this->created_at->diffInMinutes(now()) < 5;
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where('body', 'LIKE', '%' . $search . '%');
        }
        return $query;
    }
}