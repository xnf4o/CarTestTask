<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarRentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'rented_at',
        'returned_at',
    ];

    protected $casts = [
        'rented_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        // Get car's renter from relation database
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car(): BelongsTo
    {
        // Get car from relation database
        return $this->belongsTo(Car::class);
    }

    public function getRentStartAttribute($value)
    {
        return $value->format('Y-m-d H:i:s');
    }

    public function getRentEndAttribute($value)
    {
        return $value->format('Y-m-d H:i:s');
    }

    public function setRentStartAttribute($value)
    {
        $this->attributes['rented_at'] = \Carbon\Carbon::parse($value);
    }

    public function setRentEndAttribute($value)
    {
        $this->attributes['returned_at'] = \Carbon\Carbon::parse($value);
    }

    public function getRentDurationAttribute()
    {
        return $this->returned_at ? $this->returned_at->diffInMinutes($this->rented_at) : 0;
    }

    public function getRentPriceAttribute()
    {
        return $this->rent_duration * 10; // 10 rub per minute
    }

    public function end()
    {
        $this->returned_at = \Carbon\Carbon::now();
        $this->save();
    }
}
