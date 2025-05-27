<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicationReminder;
use App\Repositories\MedicationReminderRepository;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Medication Reminders",
 *     description="Gerenciamento de lembretes de medicamentos"
 * )
 */
class MedicationReminderController extends Controller
{
    public function __construct(
        private readonly MedicationReminderRepository $repository
    )
    {}
    /**
     * @OA\Get(
     *     path="/api/v1/medication-reminder",
     *     tags={"Medication Reminders"},
     *     security={{"sanctum":{}}},
     *     summary="Listar lembretes de medicamentos",
     *     description="Retorna lista de lembretes, podendo filtrar por medication_id",
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         description="Retornar paginado (true/false)",
     *         required=false,
     *         @OA\Schema(type="boolean", default="true")
     *     ),
     *     @OA\Parameter(
     *         name="medication_id",
     *         in="query",
     *         description="ID do medicamento para filtrar",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de lembretes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MedicationReminder")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function index()
    {
        $paginated = request()->query('paginate', true);
        $medication_id = request()->query('medication_id', null);
        if($medication_id){
            return response()->json(
                $this->repository->findByMedicationId($medication_id)
            );
        }
        return response()->json(
            $this->repository->getAll(
                $paginated
            )
        );
    }
    /**
     * @OA\Get(
     *     path="/api/v1/medication-reminder/{id}",
     *     tags={"Medication Reminders"},
     *     security={{"sanctum":{}}},
     *     summary="Obter lembrete específico",
     *     description="Retorna detalhes de um lembrete pelo ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do lembrete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do lembrete",
     *         @OA\JsonContent(ref="#/components/schemas/MedicationReminder")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lembrete não encontrado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function show($id)
    {
        return response()->json(
            $this->repository->findById($id)
        );
    }
    /**
     * @OA\Post(
     *     path="/api/v1/medication-reminder",
     *     tags={"Medication Reminders"},
     *     security={{"sanctum":{}}},
     *     summary="Criar novo lembrete",
     *     description="Cria um novo lembrete de medicamento",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicationReminderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lembrete criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/MedicationReminder")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return response()->json(
            $this->repository->create($request->all())
        , 201);
    }
    /**
     * @OA\Put(
     *     path="/api/v1/medication-reminder/{id}",
     *     tags={"Medication Reminders"},
     *     security={{"sanctum":{}}},
     *     summary="Atualizar lembrete",
     *     description="Atualiza um lembrete existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do lembrete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MedicationReminderRequest")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Lembrete atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro ao atualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lembrete não encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $result = $this->repository->updateById($id, $request->all());
        if($result) return response()->json(
            null, 204
        );
        return response()->json([
            'message' => __('Unable to update :attribute', ['attribute' => __('validation.attributes.time')])
        ],422);
    }
    /**
     * @OA\Delete(
     *     path="/api/v1/medication-reminder/{id}",
     *     tags={"Medication Reminders"},
     *     security={{"sanctum":{}}},
     *     summary="Excluir lembrete",
     *     description="Remove um lembrete existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do lembrete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Lembrete excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lembrete não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->repository->deleteById($id);
        return response()->json(null, 204);
    }
}

