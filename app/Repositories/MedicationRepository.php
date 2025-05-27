<?php
namespace App\Repositories;

use App\MedicationApresentationEnum;
use App\Models\Medication;
use Illuminate\Support\Collection;
/**
 * @OA\Schema(
 *     schema="MedicationRequest",
 *     type="object",
 *     title="Medication Request",
 *     required={"name", "presentation"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="Paracetamol",
 *         description="Nome do medicamento"
 *     ),
 *     @OA\Property(
 *         property="presentation",
 *         type="string",
 *         example="Comprimido 500mg",
 *         description="Forma farmacêutica do medicamento"
 *     ),
 *     @OA\Property(
 *         property="dose",
 *         type="string",
 *         example="1 comprimido",
 *         description="Dosagem recomendada"
 *
 *     ),
 *     @OA\Property(
 *         property="instructions",
 *         type="string",
 *         example="Tomar a cada 8 horas",
 *         description="Instruções de uso"
 *
 *     )
 * )
 */
class MedicationRepository  extends Repository{

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'presentation' => 'required|string|in:'.join(',', MedicationApresentationEnum::values()),
        ];
    }
    public function attributes()
    {
        return [
            'name' => __('validation.attributes.name'),
            'presentation' => __('presentation')
        ];
    }

    /**
     * @param $id
     * @return Medication
     */
    public function findById($id): mixed
    {
        return Medication::whereUserId(request()->user()->id)
            ->whereId($id)->firstOrFail();
    }

    /**
     * @param array $input
     * @return Medication
     */
    public function create(array $input): mixed
    {
        $validated = $this->validate($input);
        $validated['user_id'] = request()->user()->id;
        return Medication::create($validated);
    }

    public function updateById($id, $input): bool
    {
        $validated = $this->validate($input);
        return Medication::whereUserId(request()->user()->id)
            ->whereId($id)
            ->update($validated);
    }

    public function deleteById($id): void
    {
        Medication::whereUserId(request()->user()->id)
            ->whereId($id)
            ->delete();
    }
    /**
     * @param bool $paginated
     * @return Collection<Medication>
     *
     */
    public function getAll($paginated = true, $withReminder = false): mixed
    {
        $userId = request()->user()->id;
        $query = Medication::whereUserId($userId);
        if($withReminder){
            $query = $query->with(['remimders']);
        }
        if($paginated){
            return $query->paginate();
        }
        return $query->get();
    }
}
