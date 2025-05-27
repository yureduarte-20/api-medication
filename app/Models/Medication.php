<?php

namespace App\Models;

use App\MedicationApresentationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * @OA\Schema(
 *     schema="Medication",
 *     type="object",
 *     title="Medication",
 *     description="Medication model representation",
 *     required={"id", "name", "user_id", "presentation"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID do medicamento",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do medicamento",
 *         example="Paracetamol"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID do usuário dono do medicamento",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="presentation",
 *         type="string",
 *         enum={"SOLVENT", "CAPSULE", "TABLET"},
 *         description="Forma de apresentação do medicamento",
 *         example="CAPSULE"
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
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="Usuário dono do medicamento"
 *     ),
 *     @OA\Property(
 *         property="reminders",
 *         type="array",
 *         description="Lembretes associados ao medicamento",
 *         @OA\Items(ref="#/components/schemas/MedicationReminder")
 *     )
 * )
 */
class Medication extends Model
{
    use HasFactory;
    protected $table = 'medications';
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
    public function remimders()
    {
        return $this->hasMany(MedicationReminder::class);
    }
}
