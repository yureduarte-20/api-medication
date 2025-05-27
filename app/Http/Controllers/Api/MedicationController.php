<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Repositories\MedicationRepository;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Medications",
 *     description="Operações relacionadas a medicamentos"
 * )
 */
class MedicationController extends Controller
{
    public function __construct(
        private readonly MedicationRepository $repository
    )
    {
    }
    /**
     * List Medication
     *
     * @OA\Get(
     *     path="/api/v1/medication",
     *     security={{"sanctum":{}}},
     *     tags={"Medications"},
     *     summary="Lista todos os medicamentos",
     *     description="Retorna uma lista de medicamentos, podendo ser paginada ou não",
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         description="Indica se o resultado deve ser paginado",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             default=true
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="with_reminder",
     *         in="query",
     *         description="Indica se deve incluir os lembretes associados",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             default=false
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de medicamentos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Medication")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $paginated = request()->query('paginate', true);
        $paginated === 'true' and $paginated = true;
        $paginated === 'false' and $paginated = false;
        $with_reminder = request()->query('with_reminder', false);
        $with_reminder === 'true' and $with_reminder = true;
        $with_reminder === 'false' and $with_reminder = false;
        return response()->json(
            $this->repository->getAll(
                $paginated,
                $with_reminder
            )
        );
    }
    /**
     * Criar novo medicamento
     *
     * @OA\Post(
     *     path="/api/v1/medication",
     *     security={{"sanctum":{}}},
     *     tags={"Medications"},
     *     summary="Cria um novo medicamento",
     *     description="Armazena um novo medicamento no banco de dados",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicationRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medicamento criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Medication")
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
    public function store(Request $request)
    {
        $data = $this->repository->create($request->all());
        return response()->json($data, 201);
    }
    /**
     * Exibir medicamento específico
     *
     * @OA\Get(
     *     path="/api/v1/medication/{id}",
     *     security={{"sanctum":{}}},
     *     tags={"Medications"},
     *     summary="Exibe um medicamento específico",
     *     description="Retorna os detalhes de um medicamento pelo seu ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do medicamento",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do medicamento",
     *         @OA\JsonContent(ref="#/components/schemas/Medication")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medicamento não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medication not found")
     *         )
     *     )
     * )
     */
    public function show($id): mixed
    {
        return response()->json(
            $this->repository->findById($id)
        );
    }
    /**
     * Atualizar medicamento
     *
     * @OA\Put(
     *     path="/api/v1/medication/{id}",
     *     security={{"sanctum":{}}},
     *     tags={"Medications"},
     *     summary="Atualiza um medicamento existente",
     *     description="Atualiza os dados de um medicamento pelo seu ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do medicamento a ser atualizado",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicationRequest")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Medicamento atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medicamento não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medication not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro ao atualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unable to update medication")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $updated = $this->repository->updateById($id, $request->all());
        if ($updated) return response()->json(null, 204);
        return response()->json([
            'message' => __('Unable to update :attribute', ['attribute' => __('medication')])
        ], 422);
    }
    /**
     * Excluir medicamento
     *
     * @OA\Delete(
     *     path="/api/v1/medication/{id}",
     *     security={{"sanctum":{}}},
     *     tags={"Medications"},
     *     summary="Exclui um medicamento",
     *     description="Remove um medicamento do banco de dados pelo seu ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do medicamento a ser excluído",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Medicamento excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medicamento não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Medication not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->repository->deleteById($id);
        return response()->json(null, 204);
    }
}
