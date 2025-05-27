<?php

namespace Tests\Feature;

use App\Models\Medication;
use App\Models\MedicationReminder;
use App\Models\User;
use App\ReminderType;
use App\WeekDay;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MedicationReminderRepositoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_create_reminder_scheduled(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $medication = Medication::factory()->create([
            'user_id' => $user->id
        ]);
        $reminder = MedicationReminder::make([
            'user_id' => $user->id,
            'medication_id' => $medication->id,
            'reminder_type' => ReminderType::SCHEDULED->name,
            'quantity' => 1,
            'week_day' => WeekDay::FRI->name,
            'dose' => '1000mg',
            'hour' => '12:00'

        ]);
        $response = $this->postJson(route('medication-reminder.store'), $reminder->toArray());
        $response->assertStatus(201);
        $this->assertArrayIsEqualToArrayOnlyConsideringListOfKeys($reminder->toArray(), $response->json(), array_keys($reminder->toArray()));
        $this->assertDatabaseHas('medication_reminders', $reminder->toArray());
    }
    public function test_create_reminder_scheduled_with_tag_single(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $medication = Medication::factory()->create([
            'user_id' => $user->id
        ]);
        $reminder = MedicationReminder::make([
            'user_id' => $user->id,
            'medication_id' => $medication->id,
            'reminder_type' => ReminderType::SINGLE->name,
            'quantity' => 1,
            'week_day' => WeekDay::FRI->name,
            'dose' => '1000mg',
            'hour' => '12:00'

        ]);
        $response = $this->postJson(route('medication-reminder.store'), $reminder->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['date' => __('validation.required_if', ['attribute' => 'date',
            'other' => __('reminder type'),
            'value' => ReminderType::SINGLE->label()]) ] );
    }
    public function test_create_reminder_single(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $medication = Medication::factory()->create([
            'user_id' => $user->id
        ]);
        $reminder = MedicationReminder::make([
            'user_id' => $user->id,
            'medication_id' => $medication->id,
            'reminder_type' => ReminderType::SINGLE->name,
            'quantity' => 1,
            'dose' => '1000mg',
            'hour' => '12:00',
            'date' => date('Y-m-d', strtotime('+3 day')),
        ]);
        $response = $this->postJson(route('medication-reminder.store'), $reminder->toArray());
        $response->assertStatus(201);
        $this->assertDatabaseHas('medication_reminders', $reminder->toArray());
    }
    public function test_create_reminder_single_with_tag_scheduled(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $medication = Medication::factory()->create([
            'user_id' => $user->id
        ]);
        $reminder = MedicationReminder::make([
            'user_id' => $user->id,
            'medication_id' => $medication->id,
            'reminder_type' => ReminderType::SCHEDULED->name,
            'quantity' => 1,
            'dose' => '1000mg',
            'hour' => '12:00',
            'date' => date('Y-m-d', strtotime('+3 day')),
        ]);
        $response = $this->postJson(route('medication-reminder.store'), $reminder->toArray());
        $response->assertStatus(422);
    }
}
