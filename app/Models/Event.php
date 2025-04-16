<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'price',
        'is_approved'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_time', '<', now());
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function isFull()
    {
        if (is_null($this->capacity)) {
            return false;
        }
        return $this->attendees()->count() >= $this->capacity;
    }

    public function isPast()
    {
        return $this->end_time < now();
    }
}