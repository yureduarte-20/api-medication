<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema (
 *      schema="User",
 *      type="object",
 *      title="User",
 *      description="User model representation",
 *      required={"id", "name", "password", "birth_date"},
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          format="int64",
 *          description="ID do medicamento",
 *          example=1
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Nome",
 *          example="Paracetamol"
 *      ),
 *     @OA\Property(
 *           property="email",
 *           type="string",
 *           description="Email",
 *           example="example@email.com"
 *       ),
 *     @OA\Property(
 *            property="password",
 *            type="string",
 *            description="Senha do usuário",
 *            example="example@email.com"
 *        ),
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          description="Data de criação",
 *          example="2023-01-01T12:00:00Z"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          description="Data de atualização",
 *          example="2023-01-01T12:00:00Z"
 *      ),
 * )
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date'
        ];
    }
}
