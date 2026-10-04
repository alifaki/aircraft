<?php
namespace Database\Seeders;

use App\Models\Aviation\FatiguePolicy;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AviationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['aviation.view','View aviation operations'],
            ['aviation.manage','Manage draft aviation records'],
            ['aviation.publish','Schedule and publish aviation operations'],
            ['aviation.policy','Approve fatigue policies'],
        ] as [$slug,$name]) Permission::updateOrCreate(['slug'=>$slug],['name'=>$name,'module'=>'Aviation Operations']);
        Permission::updateOrCreate(['slug'=>'url:staffs'],['name'=>'Open staff page','module'=>'Staff Management']);
        $admin=Role::where('name','Administrator')->first();
        if ($admin) $admin->permissions()->syncWithoutDetaching(Permission::where('module','Aviation Operations')->pluck('id')->all());
        $manager=Role::where('name','Manager')->first();
        if ($manager) $manager->permissions()->syncWithoutDetaching(Permission::whereIn('slug',['aviation.view','aviation.manage','aviation.publish','url:staffs'])->pluck('id')->all());

        // AH Airways FRM/M/01, issue 01, 01 Apr 2026, Chapter 5, page 29.
        // Numeric flight time ceilings on the supplied page. Draft status is deliberate:
        // the page omits FDP, duty, sector, cabin, and rest values needed for release.
        FatiguePolicy::firstOrCreate(['name'=>'AH Airways flight crew — FRM page 29'],[
            'crew_category'=>'pilot','source_reference'=>'AH/FRM/M/01, Issue 01, Chapter 5, p.29, 01 Apr 2026',
            'effective_from'=>'2026-04-01','status'=>'draft',
            'max_flight_24h_single_minutes'=>480,'max_flight_24h_multi_minutes'=>480,
            'max_flight_7d_minutes'=>2040,'max_flight_28d_minutes'=>6000,
            'max_flight_12mo_minutes'=>60000,
            'notes'=>'Complete remaining operator-approved values and effective end date before approval.',
        ]);
        FatiguePolicy::firstOrCreate(['name'=>'Cabin crew — complete approved limits'],[
            'crew_category'=>'cabin','source_reference'=>'Operator-approved cabin crew duty/rest scheme required',
            'status'=>'draft','notes'=>'The attached page supplies no cabin crew numerical duty or rest thresholds.',
        ]);
    }
}
