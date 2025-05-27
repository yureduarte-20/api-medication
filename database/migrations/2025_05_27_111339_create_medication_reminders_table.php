<?php

use App\Models\Medication;
use App\Models\User;
use App\WeekDay;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medication_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('dose');
            $table->unsignedInteger('quantity');
            $table->time('hour');
            $table->enum('week_day', WeekDay::values())->nullable()->default(null);
            $table->date('date')->nullable();
            $table->enum('reminder_type', \App\ReminderType::values());
            $table->foreignIdFor(Medication::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_reminders');
    }
};
