<?php

namespace App\Models;

use App\MedicationApresentationEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medication extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'presentation'
    ];
    protected $casts = [
        'presentation' => MedicationApresentationEnum::class
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
