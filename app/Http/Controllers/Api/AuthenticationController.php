<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Operações de autenticação e registro de usuários"
 * )
 */
class AuthenticationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"Authentication"},
     *     summary="Autenticar usuário",
     *     description="Realiza o login e retorna um token de acesso",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@exemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="senha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="accessToken", type="string", example="1|abcdefghijklmnopqrstuvwxyz")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::whereEmail($validated['email'])->first();
        if(!$user){
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }
        if(!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => [__('auth.password')],
            ]);
        }
        $token = $user->createToken($user->email)->plainTextToken;
        return response()->json([ 'accessToken' => $token ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"Authentication"},
     *     summary="Registrar novo usuário",
     *     description="Cria uma nova conta de usuário e retorna um token de acesso",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","birth_date"},
     *             @OA\Property(property="name", type="string", example="Fulano de Tal"),
     *             @OA\Property(property="email", type="string", format="email", example="novo@usuario.com"),
     *             @OA\Property(property="password", type="string", format="password", example="senhaSegura123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="senhaSegura123"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully!"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Fulano de Tal"),
     *                 @OA\Property(property="email", type="string", example="novo@usuario.com"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01")
     *             ),
     *             @OA\Property(property="accessToken", type="string", example="2|abcdefghijklmnopqrstuvwxyz")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ],[], [
            'birth_date' => __('birth_date')
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
        ]);

        $token = $user->createToken($user->email)->plainTextToken;


        return response()->json([
            'message' => __('User created successfully!'),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birth_date' => $user->birth_date->format('Y-m-d')
            ],
            'accessToken' => $token,
        ], 201);
    }

}
