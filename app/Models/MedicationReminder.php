<?php

namespace App\Models;

use App\ReminderType;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="MedicationReminder",
 *     type="object",
 *     title="Medication Reminder",
 *     description="Modelo de lembrete para medicamentos",
 *     required={"dose", "user_id", "hour", "medication_id", "reminder_type"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID do lembrete",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="dose",
 *         type="string",
 *         description="Dose a ser tomada",
 *         example="1 comprimido"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID do usuário",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="week_day",
 *         type="string",
 *         nullable=true,
 *         description="Dia da semana (para lembretes semanais)",
 *         enum={"SUN", "MON", "TUES", "WEDNES", "THURS", "FRI", "SATUR"},
 *         example="MON"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="number",
 *         format="float",
 *         nullable=true,
 *         description="Quantidade da dose",
 *         example=1.5
 *     ),
 *     @OA\Property(
 *         property="hour",
 *         type="string",
 *         format="time",
 *         description="Hora do lembrete",
 *         example="08:00"
 *     ),
 *     @OA\Property(
 *         property="reminder_type",
 *         ref="#/components/schemas/ReminderType"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         nullable=true,
 *         description="Data específica (para lembretes de data específica)",
 *         example="2023-12-31"
 *     ),
 *     @OA\Property(
 *         property="medication_id",
 *         type="integer",
 *         format="int64",
 *         description="ID do medicamento relacionado",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de criação",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de atualização",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="medication",
 *         ref="#/components/schemas/Medication",
 *         description="Medicamento relacionado"
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="Usuário relacionado"
 *     )
 * )
 */
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
