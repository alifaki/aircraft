<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('aviation_maintenance_plans', function (Blueprint $t) {
            $t->id(); $t->foreignId('aircraft_id')->constrained('aviation_aircraft')->restrictOnDelete();
            $t->string('name'); $t->string('source_reference');
            $t->unsignedInteger('interval_minutes')->nullable(); $t->unsignedInteger('interval_cycles')->nullable();
            $t->unsignedInteger('interval_days')->nullable();
            $t->unsignedBigInteger('baseline_minutes')->default(0); $t->unsignedBigInteger('baseline_cycles')->default(0);
            $t->unsignedBigInteger('last_minutes')->default(0); $t->unsignedBigInteger('last_cycles')->default(0);
            $t->dateTime('last_completed_at'); $t->dateTime('tracking_from');
            $t->unsignedInteger('warning_minutes')->default(600); $t->unsignedInteger('warning_cycles')->default(10);
            $t->unsignedInteger('warning_days')->default(7); $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('aviation_maintenance_tasks', function (Blueprint $t) {
            $t->id(); $t->foreignId('plan_id')->constrained('aviation_maintenance_plans')->restrictOnDelete();
            $t->string('status')->default('upcoming'); $t->dateTime('due_at')->nullable();
            $t->dateTime('scheduled_start')->nullable(); $t->dateTime('scheduled_end')->nullable();
            $t->dateTime('completed_at')->nullable(); $t->text('completion_reference')->nullable();
            $t->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete(); $t->timestamps();
        });
        Schema::create('aviation_pilot_leave_rules', function (Blueprint $t) {
            $t->id(); $t->foreignId('crew_id')->unique()->constrained('aviation_crew')->restrictOnDelete();
            $t->string('source_reference'); $t->unsignedInteger('threshold_minutes'); $t->unsignedInteger('leave_days');
            $t->unsignedInteger('baseline_minutes')->default(0); $t->dateTime('tracking_from');
            $t->dateTime('last_leave_end')->nullable(); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::table('aviation_crew_absences', function (Blueprint $t) {
            $t->foreignId('leave_rule_id')->nullable()->constrained('aviation_pilot_leave_rules')->restrictOnDelete();
            $t->string('automation_key')->nullable()->unique();
        });
    }
    public function down(): void
    {
        Schema::table('aviation_crew_absences', function (Blueprint $t) {
            $t->dropForeign(['leave_rule_id']); $t->dropUnique(['automation_key']);
            $t->dropColumn(['leave_rule_id','automation_key']);
        });
        Schema::dropIfExists('aviation_pilot_leave_rules');
        Schema::dropIfExists('aviation_maintenance_tasks'); Schema::dropIfExists('aviation_maintenance_plans');
    }
};
