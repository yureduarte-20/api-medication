<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
/**
 *
 * @OA\Tag(
 *     name="Profile"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/v1/profile",
     *     tags={"Profile"},
     *     security={{"sanctum":{}}},
     *     summary="Atualizar informações do perfil",
     *     description="Atualiza o nome e email do usuário autenticado",
     *     @OA\Parameter(
     *            name="accept",
     *            in="header",
     *            required=true,
     *            @OA\Schema(type="string", default="application/json")
     *        ),
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", minLength=3, maxLength=255, example="Novo Nome"),
     *             @OA\Property(property="email", type="string", format="email", example="novo@email.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Perfil atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
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
    public function update(Request $request)
    {
        /**
         * @OA\Schema(
         *     schema="ProfileUpdateRequest",
         *     type="object",
         *     required={"name","email"},
         *     @OA\Property(property="name", type="string", minLength=3, maxLength=255, example="Novo Nome"),
         *     @OA\Property(property="email", type="string", format="email", example="novo@email.com")
         * )
         */

        $user = $request->user();
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => ['required', Rule::unique('users', 'email')->ignore($user->id)],
        ]);
        $user->update($validated);
        return response()->json(null, 204);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/profile/password",
     *     tags={"Profile"},
     *     security={{"sanctum":{}}},
     *     summary="Atualizar senha",
     *     description="Atualiza a senha do usuário autenticado",
     *     @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","password","password_confirmation"},
     *             @OA\Property(property="current_password", type="string", format="password", example="senhaAtual123"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8, example="novaSenha123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="novaSenha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Senha atualizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
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
    public function update_password(Request $request)
    {
        /**
         * @OA\Schema(
         *     schema="PasswordUpdateRequest",
         *     type="object",
         *     required={"current_password","password","password_confirmation"},
         *     @OA\Property(property="current_password", type="string", format="password", example="senhaAtual123"),
         *     @OA\Property(property="password", type="string", format="password", minLength=8, example="novaSenha123"),
         *     @OA\Property(property="password_confirmation", type="string", format="password", example="novaSenha123")
         * )
         */
        $validated = Validator::make($request->all(), [
            'current_password' => 'required|current_password:sanctum',
            'password' => 'required|min:8|confirmed',
        ])->validate();
        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        return response()->json(null, 204);

    }
}
