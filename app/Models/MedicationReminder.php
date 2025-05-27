<?php

namespace App\Models;

use App\ReminderType;
use Illuminate\Database\Eloquent\Model;

class MedicationReminder extends Model
{
    protected $fillable = [
        'dose',
        'user_id',
        'week_day',
        'quantity',
        'hour',
        'reminder_type',
        'date',
        'medication_id',
    ];
    protected $casts = [
        'hour' => 'date:H:i',
        'reminder_type' => ReminderType::class,
        'date' => 'date:Y-m-d',
    ];
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
