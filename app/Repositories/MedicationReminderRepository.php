<?php

namespace App\Repositories;

use App\Models\Medication;
use App\Models\MedicationReminder;
use App\ReminderType;
use App\WeekDay;
use Illuminate\Validation\Rule;
use Ramsey\Collection\Collection;
/**
 * @OA\Schema(
 *     schema="MedicationReminderRequest",
 *     type="object",
 *     title="Medication Reminder Request",
 *     required={"dose", "hour", "medication_id", "reminder_type"},
 *     @OA\Property(
 *         property="dose",
 *         type="string",
 *         example="1 comprimido"
 *     ),
 *     @OA\Property(
 *         property="week_day",
 *         type="string",
 *         nullable=true,
 *         enum={"SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"},
 *         example="MON"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="number",
 *         format="float",
 *         nullable=true,
 *         example=1.5
 *     ),
 *     @OA\Property(
 *         property="hour",
 *         type="string",
 *         format="time",
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
 *         example="2023-12-31"
 *     ),
 *     @OA\Property(
 *         property="medication_id",
 *         type="integer",
 *         format="int64",
 *         example=1
 *     )
 * )
 */
class MedicationReminderRepository extends Repository
{
    /** Define the default repository rules
     * @return array<string, Rule>
     */
    public function rules(): array
    {
        return [
            'dose' => 'required|min:1',
            'quantity' => 'required|integer|min:1',
            'week_day' => ['required_if:reminder_type,' . ReminderType::SCHEDULED->name, 'in:' . implode(',', WeekDay::values())],
            'hour' => 'required|date_format:H:i',
            'reminder_type' => 'required|in:' . implode(',', ReminderType::values()),
            'date' => ['required_if:reminder_type,' . ReminderType::SINGLE->name, 'date', 'after_or_equal:today'],
            'medication_id' => ['required', Rule::exists('medications', 'id')->where('user_id', auth()->user()->id)],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required_if' => __('validation.required_if', ['attribute' => __('date'),
                'other' => __('reminder type'),
                'value' => ReminderType::SINGLE->label()]),
            'week_day.required_if' => __('validation.required_if', ['attribute' => __('week day'),
                'other' => __('reminder type'),
                'value' => ReminderType::SINGLE->label()]),
        ];
    }
    public function attributes()
    {
        return [
            'dose' => __('dose'),
            'quantity' => __('quantity'),
            'week_day' => __('week day'),
            'reminder_type' => __('reminder type'),
            'medication_id' => __('medication'),
        ];
    }

    public function findById($id): mixed
    {
        return MedicationReminder::where([
            'id' => $id,
            'user_id' => auth()->user()->id
        ])->firstOrFail();
    }

    /**
     * @param $medication_id
     * @return Collection<MedicationReminder>
     */
    public function findByMedicationId($medication_id): mixed
    {
        return MedicationReminder::where([
            'medication_id' => $medication_id,
            'user_id' => auth()->user()->id
        ])->with(['medication'])->get();
    }

    public function create(array $input): mixed
    {
        $validated = $this->validate($input);
        $validated['user_id'] = auth()->user()->id;
        return MedicationReminder::create($validated);
    }

    public function updateById($id, $input): bool
    {
        $validated = $this->validate($input);
        return MedicationReminder::where([
            'id' => $id,
            'user_id' => auth()->user()->id
        ])->update($validated);
    }

    public function deleteById($id): void
    {
        MedicationReminder::where([
            'id' => $id,
            'user_id' => auth()->user()->id
        ])->delete();
    }

    public function getAll($paginated = true): mixed
    {
        $query = MedicationReminder::where('user_id', auth()->user()->id);
        if ($paginated) {
            return $query->paginate();
        }
        return $query->get();
    }
}
