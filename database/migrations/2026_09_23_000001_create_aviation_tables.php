<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aviation_airports', function (Blueprint $t) {
            $t->id(); $t->string('icao', 4)->unique(); $t->string('iata', 3)->nullable();
            $t->string('name'); $t->string('city'); $t->string('country');
            $t->string('timezone'); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('aviation_aircraft_types', function (Blueprint $t) {
            $t->id(); $t->string('icao_designator', 8)->unique(); $t->string('manufacturer')->nullable();
            $t->string('model'); $t->unsignedTinyInteger('minimum_pilots');
            $t->unsignedTinyInteger('minimum_cabin_crew')->default(0);
            $t->unsignedSmallInteger('minimum_turnaround_minutes');
            $t->unsignedSmallInteger('passenger_capacity')->nullable(); $t->timestamps();
        });
        Schema::create('aviation_aircraft', function (Blueprint $t) {
            $t->id(); $t->string('registration', 24)->unique();
            $t->foreignId('aircraft_type_id')->constrained('aviation_aircraft_types')->restrictOnDelete();
            $t->foreignId('home_airport_id')->nullable()->constrained('aviation_airports')->nullOnDelete();
            $t->string('serial_number')->nullable(); $t->string('status')->default('active');
            $t->date('airworthiness_expires_at')->nullable(); $t->date('insurance_expires_at')->nullable();
            $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('aviation_fatigue_policies', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('crew_category', 10); // pilot or cabin
            $t->string('source_reference'); $t->date('effective_from')->nullable();
            $t->date('effective_until')->nullable(); $t->string('status')->default('draft');
            $t->unsignedInteger('max_flight_24h_single_minutes')->nullable();
            $t->unsignedInteger('max_flight_24h_multi_minutes')->nullable();
            $t->unsignedInteger('max_flight_7d_minutes')->nullable();
            $t->unsignedInteger('max_flight_28d_minutes')->nullable();
            $t->unsignedInteger('max_flight_12mo_minutes')->nullable();
            $t->unsignedInteger('max_fdp_minutes')->nullable();
            $t->unsignedInteger('max_duty_minutes')->nullable();
            $t->unsignedInteger('max_duty_7d_minutes')->nullable();
            $t->unsignedInteger('max_duty_28d_minutes')->nullable();
            $t->unsignedInteger('max_duty_12mo_minutes')->nullable();
            $t->unsignedInteger('min_rest_minutes')->nullable();
            $t->unsignedInteger('max_sectors_per_duty')->nullable();
            $t->unsignedInteger('max_landings_per_duty')->nullable();
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('approved_at')->nullable(); $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('aviation_crew', function (Blueprint $t) {
            $t->id(); $t->foreignId('staff_id')->unique()->constrained('staff')->restrictOnDelete();
            $t->string('crew_category', 10); $t->foreignId('base_airport_id')->nullable()->constrained('aviation_airports')->nullOnDelete();
            $t->string('licence_number'); $t->date('licence_expires_at'); $t->date('medical_expires_at');
            $t->string('status')->default('active'); $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('aviation_qualifications', function (Blueprint $t) {
            $t->id(); $t->foreignId('crew_id')->constrained('aviation_crew')->cascadeOnDelete();
            $t->foreignId('aircraft_type_id')->constrained('aviation_aircraft_types')->restrictOnDelete();
            $t->string('role', 24); $t->string('certificate_number')->nullable(); $t->date('expires_at');
            $t->unique(['crew_id','aircraft_type_id','role']); $t->timestamps();
        });
        Schema::create('aviation_crew_absences', function (Blueprint $t) {
            $t->id(); $t->foreignId('crew_id')->constrained('aviation_crew')->cascadeOnDelete();
            $t->string('reason'); $t->dateTime('starts_at'); $t->dateTime('ends_at');
            $t->text('notes')->nullable(); $t->timestamps(); $t->index(['crew_id','starts_at','ends_at']);
        });
        Schema::create('aviation_crew_rest_periods', function (Blueprint $t) {
            $t->id(); $t->foreignId('crew_id')->constrained('aviation_crew')->cascadeOnDelete();
            $t->dateTime('starts_at'); $t->dateTime('ends_at'); $t->string('status')->default('planned');
            $t->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->text('notes')->nullable(); $t->timestamps(); $t->index(['crew_id','starts_at','ends_at']);
        });
        Schema::create('aviation_groundings', function (Blueprint $t) {
            $t->id(); $t->foreignId('aircraft_id')->constrained('aviation_aircraft')->cascadeOnDelete();
            $t->string('reason'); $t->dateTime('starts_at'); $t->dateTime('ends_at');
            $t->text('notes')->nullable(); $t->timestamps(); $t->index(['aircraft_id','starts_at','ends_at']);
        });
        Schema::create('aviation_duties', function (Blueprint $t) {
            $t->id(); $t->string('reference', 32)->unique(); $t->string('type', 24);
            $t->dateTime('report_at'); $t->dateTime('release_at');
            $t->dateTime('actual_report_at')->nullable(); $t->dateTime('actual_release_at')->nullable();
            $t->foreignId('pilot_policy_id')->nullable()->constrained('aviation_fatigue_policies')->restrictOnDelete();
            $t->foreignId('cabin_policy_id')->nullable()->constrained('aviation_fatigue_policies')->restrictOnDelete();
            $t->string('status')->default('draft'); $t->text('notes')->nullable();
            $t->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('published_at')->nullable(); $t->timestamps(); $t->index(['report_at','release_at']);
        });
        Schema::create('aviation_duty_crew', function (Blueprint $t) {
            $t->id(); $t->foreignId('duty_id')->constrained('aviation_duties')->cascadeOnDelete();
            $t->foreignId('crew_id')->constrained('aviation_crew')->restrictOnDelete();
            $t->string('role', 24); $t->unique(['duty_id','crew_id']); $t->timestamps();
        });
        Schema::create('aviation_flights', function (Blueprint $t) {
            $t->id(); $t->string('flight_number', 32); $t->date('flight_date'); $t->unsignedSmallInteger('leg_sequence');
            $t->foreignId('aircraft_id')->constrained('aviation_aircraft')->restrictOnDelete();
            $t->foreignId('origin_id')->constrained('aviation_airports')->restrictOnDelete();
            $t->foreignId('destination_id')->constrained('aviation_airports')->restrictOnDelete();
            $t->foreignId('duty_id')->nullable()->constrained('aviation_duties')->nullOnDelete();
            $t->dateTime('departure_at'); $t->dateTime('arrival_at');
            $t->dateTime('actual_off_at')->nullable(); $t->dateTime('actual_on_at')->nullable();
            $t->unsignedSmallInteger('planned_landings'); $t->unsignedSmallInteger('actual_landings')->nullable();
            $t->string('status')->default('draft'); $t->text('notes')->nullable();
            $t->timestamps(); $t->unique(['flight_number','flight_date','leg_sequence']);
            $t->index(['aircraft_id','departure_at','arrival_at']);
        });
        Schema::create('aviation_duty_activities', function (Blueprint $t) {
            $t->id(); $t->foreignId('duty_id')->constrained('aviation_duties')->cascadeOnDelete();
            $t->string('type', 24); $t->dateTime('starts_at'); $t->dateTime('ends_at');
            $t->text('notes')->nullable(); $t->timestamps();
        });
        Schema::create('aviation_safety_events', function (Blueprint $t) {
            $t->id(); $t->foreignId('duty_id')->nullable()->constrained('aviation_duties')->nullOnDelete();
            $t->foreignId('flight_id')->nullable()->constrained('aviation_flights')->nullOnDelete();
            $t->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->string('event_type'); $t->json('issues'); $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['aviation_safety_events','aviation_duty_activities','aviation_flights','aviation_duty_crew',
            'aviation_duties','aviation_groundings','aviation_crew_absences','aviation_qualifications',
            'aviation_crew_rest_periods','aviation_crew','aviation_fatigue_policies','aviation_aircraft','aviation_aircraft_types','aviation_airports'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
