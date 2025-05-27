<?php

namespace App\Repositories;

use App\Models\Medication;
use App\Models\MedicationReminder;
use App\ReminderType;
use App\WeekDay;
use Illuminate\Validation\Rule;
use Ramsey\Collection\Collection;

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
        ])->get();
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
        if ($paginated) {
            return MedicationReminder::where('user_id', auth()->user()->id)->paginate();
        }
        return MedicationReminder::where('user_id', auth()->user()->id)->get();
    }
}
