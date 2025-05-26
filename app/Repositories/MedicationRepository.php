<?php
namespace App\Repositories;

use App\MedicationApresentationEnum;
use App\Models\Medication;
use Illuminate\Support\Collection;


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
        return Medication::whereUserId(auth()->user()->id)
            ->whereId($id)->firstOrFail();
    }

    /**
     * @param array $input
     * @return Medication
     */
    public function create(array $input): mixed
    {
        $validated = $this->validate($input);
        $validated['user_id'] = auth()->user()->id;
        return Medication::create($validated);
    }

    public function updateById($id, $input): bool
    {
        $validated = $this->validate($input);
        return Medication::whereUserId(auth()->user()->id)
            ->whereId($id)
            ->update($validated);
    }

    public function deleteById($id): void
    {
        Medication::whereUserId(auth()->user()->id)
            ->whereId($id)
            ->delete();
    }
    /**
     * @param bool $paginated
     * @return Collection<Medication>
     *
     */
    public function getAll($paginated = true): mixed
    {
        $userId = auth()->user()->id;
        if($paginated){
            return Medication::whereUserId($userId)->paginate();
        }
        return Medication::whereUserId($userId)->get();
    }
}
