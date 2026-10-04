# Aircraft Pilot Management

An aviation operations module built into the provided Laravel 12 application. It replaces the home dashboard, login, and primary navigation with flight operations screens. Existing administrative records (staff, users, roles) remain available; the original parking and billing modules remain in the source for compatibility.

The application now uses a shared operations navigation and visual design across aviation and legacy administration Blade pages, including staff, users, roles, parking and reports. Sign in, password recovery, confirmation codes and error pages share a responsive account layout. Printable reports and account emails use matching document styling. Existing form IDs, server routes and administration scripts are retained so legacy data entry continues to work.

## Install

Requires PHP 8.2+, Composer, and the PHP extensions required by Laravel. SQLite is configured for the quick start; MySQL/PostgreSQL can be configured through `.env`.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

The uploaded `composer.lock` had unresolved merge-conflict markers and pinned an Excel package from the Laravel 4 era. It is excluded; the corrected `composer.json` selects Laravel Excel 3.1. Run `composer install` on your PHP host to generate a valid lock file, then keep that generated lock with the deployment. PHP extensions required by Laravel Excel include `zip`, `xml`, `gd`, `mbstring` and `fileinfo`.

Before `migrate --seed`, set `AVIATION_ADMIN_PASSWORD` and `AVIATION_MANAGER_PASSWORD` in `.env`. If either is left empty, the initial seed prints a random one-time password to the terminal; save it then. Default account names are `admin` and `hrmanager`. Never put production credentials in the ZIP. On an **existing** installation, back up the database and run `php artisan migrate` followed by `php artisan db:seed --class=AviationSeeder` (the original application seeder creates demo data and is intended only for an empty installation).

The ZIP omits the uploaded `.env`, private uploaded documents, logs, sessions, database data, compiled views and dependency folders. Install dependencies with Composer as above. Serve via Laravel or point the web server at `public/`.

## Operational sequence

1. Create airports with ICAO codes and IANA time zones, aircraft types with crew minima and a minimum turnaround interval, and aircraft with valid insurance/airworthiness dates.
2. Add staff through the existing Staff Administration screen. Create crew profiles, licence/medical expiry dates and aircraft type qualifications (captain, first officer or cabin crew).
3. Complete the seeded **draft** fatigue policies, adding the approved maximum duty, FDP, sectors, minimum rest, cabin crew limits and effective dates. A user with `aviation.policy` approves them. Approved policies cannot be edited; create a replacement policy for changes.
4. Create flight legs in UTC (the same flight number may have multiple leg sequences) and select **Schedule** to verify aircraft availability, airport continuity and type-specific turnaround. Create flight duties, attach flights, assign qualified crew and select appropriate pilot/cabin policies. Standby, training, positioning, deadheading and split duty can be recorded as duty types or activities.
5. Record planned crew rest in Crew Availability (confirm past rest). Select **Publish roster**. Validation checks aircraft grounding/overlap/location continuity, required crew complement, qualifications, certificate dates, absence/other duty overlap, documented minimum rest, FDP, duty, sectors and landings, and rolling flight and duty limits. Failed validation leaves the roster in draft.
6. Enter actual off/on times for each flight and actual report/release times, then complete the duty. Actual values are retained even if limits are exceeded; exceptions are written to the safety event log. A later grounding/absence also creates events for affected published work.

Crew members with linked login accounts can open **My roster** to see their published duties and submit fatigue or unfitness reports. An unfitness report creates an availability block and a safety event for operations staff to review.

All scheduling form inputs and stored timestamps are **UTC**. Airport IANA time zones are reference data; local report-time conversion and acclimatisation rules are not inferred.

## FRM source and compliance boundary

The uploaded scan is included at `docs/AH-Airways-FRM-Chapter5-page29.pdf`. It is page 29, Chapter 5 of **AH Airways Fatigue Risk Management Manual**, ref. AH/FRM/M/01, Issue 01, dated 01 April 2026. It sets pilot commercial air transport flight time at **480 minutes in any 24 consecutive hours** for both single-pilot and two-pilot operations (the single-pilot value is a company protective limit); **2,040 minutes in any 7 consecutive days**, **6,000 minutes in any 28 consecutive days**, and **60,000 minutes in any 12 calendar months**. These values are seeded as a draft pilot policy with a source reference. Rolling 7/28-day checks measure continuous elapsed time windows; 12-calendar-month checks measure consecutive calendar months. Total overlapping block minutes are counted at window boundaries.

The same page calls for monitoring FDP, duty, rest, standby, positioning, deadheading, split duty, sectors, landings and cumulative limits **without providing numeric thresholds for those items or cabin crew**. No missing value is guessed. Publication is blocked until an authorised administrator enters the missing operator-approved limits, adds an effective range and approves the policy. The app uses one configurable conservative FDP ceiling per policy; it does not encode variable report-time/acclimatisation tables, augmentation or in-flight rest credits, special airport standby conversion, landing limits, discretionary extensions, recurrent training verification, or automatic FRMS approval. Those operation-specific requirements must be entered and reviewed against the complete approved manual before relying on this application for live release. Recorded split duty does not extend FDP.

ICAO's Doc 9966 provides the framework for operator fatigue controls: https://www.icao.int/publications/doc-9966-includes-complete-set-fatigue-management-implementation-manuals . Tanzania's 2024 Fatigue Risk Management Regulations (https://www.tcaa.go.tz/uploads/documents/en-1781365220-The%20Civil%20Aviation%20%28Fatigue%20Risk%20Management%29%20Regulations%2C%202024.pdf) include rolling 7-day, 28-day and 12-month duty limits and additional operation-specific FDP/rest provisions. The TCAA prescriptive-scheme advisory circular (https://www.tcaa.go.tz/uploads/documents/en-1781279510-Prescriptive%20Flight%20and%20Duty%20Time%20Scheme%20Advisory%20Circular.pdf) says the operator's approved scheme must be in its operations manual. EASA (https://www.easa.europa.eu/en/document-library/easy-access-rules/online-publications/easy-access-rules-air-operations) and FAA (https://www.faa.gov/about/office_org/headquarters_offices/agc/practice_areas/regulations/part117) illustrate why rules differ by jurisdiction and operation. This application applies the uploaded manual's supplied flight-time values plus operator-configured limits, not an automatic assertion of approval under a particular regulator.

## Access and record handling

Permissions `aviation.view`, `aviation.manage`, `aviation.publish` and `aviation.policy` are seeded and assigned to the Administrator role; the Manager gets the first three. All forms use Laravel CSRF validation; only authorised users can change records. Publication uses a database transaction and locks the affected crew and aircraft rows before checking. Draft changes and actual deviations should be reviewed on the overview and safety event screens. Do not delete completed records; preserve operational evidence.

## Verification

This source archive passed PHP 8.3 syntax checks for 240 PHP files and `composer validate`. Laravel migrations, Blade compilation and feature tests could not run in the build environment because Composer could not reach its package repository. Before deploying, run the following on a development host with Composer access:

```bash
php artisan migrate:fresh --seed
php artisan route:list --path=aviation
php artisan view:cache
php artisan test --filter=AviationSchedulingTest
```

Never run `migrate:fresh` on a production database. The uploaded ZIP contained a duplicate personal access token migration and an early bill foreign key pointing at a later parking table; these were corrected for a fresh install. The original staff/users migration also referenced the wrong staff table name and is corrected here.
#   a i r c r a f t  
 