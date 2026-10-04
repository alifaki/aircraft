<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Aviation\{
    DashboardController,AirportController,AircraftTypeController,AircraftController,CrewController,
    QualificationController,AbsenceController,RestController,GroundingController,PolicyController,
    FlightController,DutyController,ActivityController,SafetyEventController,CrewPortalController
};

Route::prefix('aviation')->name('aviation.')->middleware(\App\Http\Middleware\AviationAjax::class)->group(function () {
    Route::get('automation',[\App\Http\Controllers\Aviation\AutomationController::class,'index'])->name('automation.index');
    Route::post('automation/sync',[\App\Http\Controllers\Aviation\AutomationController::class,'sync'])->name('automation.sync');
    Route::post('automation/maintenance',[\App\Http\Controllers\Aviation\AutomationController::class,'maintenance'])->name('automation.maintenance');
    Route::post('automation/leave-rule',[\App\Http\Controllers\Aviation\AutomationController::class,'leaveRule'])->name('automation.leave-rule');
    Route::post('automation/tasks/{task}/book',[\App\Http\Controllers\Aviation\AutomationController::class,'book'])->name('automation.book');
    Route::post('automation/tasks/{task}/complete',[\App\Http\Controllers\Aviation\AutomationController::class,'complete'])->name('automation.complete');
    Route::put('automation/leave-rules/{rule}',[\App\Http\Controllers\Aviation\AutomationController::class,'updateLeaveRule'])->name('automation.leave-rule.update');
    Route::put('automation/maintenance/{plan}',[\App\Http\Controllers\Aviation\AutomationController::class,'updateMaintenance'])->name('automation.maintenance.update');
    Route::get('my-roster',[CrewPortalController::class,'index'])->name('my-roster');
    Route::post('my-roster/report',[CrewPortalController::class,'report'])->name('my-roster.report');
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::resource('airports',AirportController::class)->only(['index','store','update','destroy']);
    Route::resource('types',AircraftTypeController::class)->parameters(['types'=>'type'])->only(['index','store','update','destroy']);
    Route::resource('aircraft',AircraftController::class)->only(['index','store','update','destroy']);
    Route::resource('crew',CrewController::class)->only(['index','store','update','destroy']);
    Route::post('qualifications',[QualificationController::class,'store'])->name('qualifications.store');
    Route::delete('qualifications/{qualification}',[QualificationController::class,'destroy'])->name('qualifications.destroy');
    Route::resource('absences',AbsenceController::class)->only(['index','store','destroy']);
    Route::post('rest-periods',[RestController::class,'store'])->name('rests.store');
    Route::post('rest-periods/{rest}/confirm',[RestController::class,'confirm'])->name('rests.confirm');
    Route::delete('rest-periods/{rest}',[RestController::class,'destroy'])->name('rests.destroy');
    Route::resource('groundings',GroundingController::class)->only(['index','store','destroy']);
    Route::resource('policies',PolicyController::class)->only(['index','store','update','destroy']);
    Route::post('policies/{policy}/approve',[PolicyController::class,'approve'])->name('policies.approve');
    Route::resource('flights',FlightController::class)->only(['index','store','update','destroy']);
    Route::post('flights/{flight}/schedule',[FlightController::class,'schedule'])->name('flights.schedule');
    Route::post('flights/{flight}/unschedule',[FlightController::class,'unschedule'])->name('flights.unschedule');
    Route::post('flights/{flight}/actual',[FlightController::class,'actual'])->name('flights.actual');
    Route::resource('duties',DutyController::class)->only(['index','store','update','destroy']);
    Route::post('duties/{duty}/assign',[DutyController::class,'assign'])->name('duties.assign');
    Route::delete('duties/{duty}/crew/{crew}',[DutyController::class,'removeCrew'])->name('duties.crew.destroy');
    Route::post('duties/{duty}/flights',[DutyController::class,'attachFlight'])->name('duties.flights.store');
    Route::delete('duties/{duty}/flights/{flight}',[DutyController::class,'detachFlight'])->name('duties.flights.destroy');
    Route::post('duties/{duty}/activities',[ActivityController::class,'store'])->name('duties.activities.store');
    Route::delete('duties/{duty}/activities/{activity}',[ActivityController::class,'destroy'])->name('duties.activities.destroy');
    Route::post('duties/{duty}/publish',[DutyController::class,'publish'])->name('duties.publish');
    Route::post('duties/{duty}/unpublish',[DutyController::class,'unpublish'])->name('duties.unpublish');
    Route::post('duties/{duty}/complete',[DutyController::class,'complete'])->name('duties.complete');
    Route::get('safety-events',[SafetyEventController::class,'index'])->name('events.index');
});
