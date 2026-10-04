# Improved aircraft management — 4 October 2026

## Upgrade an existing installation

1. Back up the database and application. Keep your existing `.env` and production credentials.
2. Copy this application's updated source into your installation. The ZIP excludes `.env`, vendor dependencies, runtime caches, logs and test databases.
3. From the project directory run:

```bash
composer install --no-interaction --prefer-dist
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed --class=AviationSeeder --force
php artisan view:cache
```

The new migration is `2026_10_04_000001_create_aviation_automation_tables.php`. Existing aircraft, pilots, flights and duties are retained. Do not use `migrate:fresh` on an existing database.

## Enable background scheduling

Add one cron entry using your actual PHP executable and application directory:

```cron
* * * * * cd /absolute/path/to/application && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

The `aviation:automate` command runs every five minutes with overlap protection. During local development, `php artisan schedule:work` is an alternative to cron.

You can also use **Maintenance & pilot leave → Update schedules now**, or run:

```bash
php artisan aviation:automate
php artisan schedule:list
```

Actual flight recording and duty completion also recalculate the schedules immediately. Calendar maintenance needs the background scheduler even on days without flights.

## Set up maintenance

Open **Maintenance & pilot leave** in the sidebar. Create a separate recurring plan for each aircraft/check/component using the operator's maintenance programme reference.

- Enter the hour, landing-cycle and/or calendar-day interval. At least one is required. The earliest reached limit controls the check.
- Enter the last maintenance completion time and the aircraft's total hour and landing-cycle readings **at that maintenance**, not today's readings.
- Enter actual block times and landings for all flights since that completion. These are added to the baseline. One landing is counted as one tracked cycle.
- Choose warning margins. The table shows remaining hours/cycles, a calendar due time where configured, and healthy/upcoming/due status. A usage-only plan has no invented calendar due date.
- Tasks are generated automatically without duplicate open tasks per plan. Book an exact maintenance slot using **Book maintenance**. Overlapping scheduled flights must first be unscheduled.
- Only a user with publication permission can confirm completion, using a work order or release reference. Completion retains history and resets the next interval from the current recorded meter readings.
- Policy administrators can edit intervals/references and activate or deactivate plans while retaining meter readings and completion history.

Flight scheduling checks accumulated actual usage plus outstanding planned legs, including later scheduled flights. It blocks a release when a configured maintenance limit is reached, or the aircraft overlaps a booked maintenance slot. Existing published operations are also visible through the overview's existing safety rechecks; the new rules do not silently cancel their rosters.

## Set up automatic pilot recovery leave

A user with `aviation.policy` permission can create a rule for each pilot:

1. Select the pilot.
2. Enter the operator-approved flight-hour threshold and number of recovery days.
3. Enter the tracking start and any hours already accumulated at that start which are not represented by logged flights after that time.
4. Enter the policy reference and activate the rule.

Actual recorded flight time counts once per flight and assigned pilot. Cancelled flights and flights without completed actual block times do not add hours. Existing rolling fatigue policy checks still apply separately.

At the threshold, the system automatically creates a **Flight-hour recovery leave** absence. It starts immediately, or at the end of a published duty already underway. Future published duties overlapping leave generate safety events so operations can replace the pilot. New conflicting rosters are blocked; projected roster hours cannot exceed the recovery threshold. Future work may be rostered after an already scheduled recovery leave ends.

The counter resets after the recovery leave has ended and the automatic command runs. Automatic leave remains in the availability register as an audit record and cannot be deleted through the normal absence endpoint. Editing a rule retains existing leave and tracked history. Ordinary annual, sick and personal leave can still be recorded separately.

No universal maintenance interval or leave threshold is preselected. Configure these values from your own approved programme and leave policy. The module records block hours and landing cycles; additional engine, component, takeoff-cycle, air-time or condition monitoring would need its own tracking inputs.

## Forms and selects

- All canonical PHP/IANA time zones are available as a searchable dropdown on both airport creation and editing.
- The country dropdown includes 249 ISO country/territory options, using the browser's English region names. Existing country values remain selectable.
- Aircraft, airports, crew, policy references, statuses, roles and existing entity lists use the bundled Select2 library with search for longer lists. Absence and grounding reasons use dropdowns; add specific details under Notes. Crew assignment only enables roles compatible with the selected crew category.
- Aviation forms submit using AJAX; saves, updates, deletions, scheduling, publication, maintenance booking/completion and crew reports do not reload the whole page.
- Filters, pagination and Refresh table use asynchronous GET requests. Loading buttons prevent duplicate clicks. Field validation stays beside the input and preserves entered values. Other forms' entered values are preserved during a successful refresh.
- Laravel CSRF, authentication, permissions and server-side scheduling checks remain in force. Forms still support normal submissions when JavaScript is unavailable.

## Validation completed

- 144 application, migration, route and test PHP files passed syntax checks before the final additions; final changed files also passed PHP syntax checks.
- 24 aviation tests passed, with 49 assertions, covering actual meter readings, hour/cycle/calendar thresholds, recurrence, booked maintenance overlaps, insertion before future flights, pilot leave idempotency, reset after recovery leave, future duties after leave, published-duty conflicts, cancelled flights, AJAX responses, validation, permissions and view rendering.
- Laravel Blade cache compilation, migration/seed, aviation route listing, automation command and scheduler listing passed.
- Frontend DOM integration checks ran against the Laravel HTTP application with the bundled jQuery and Select2 scripts: 249 country choices, searchable timezone options, AJAX creation, inline validation preserving values, asynchronous pagination, date filtering and the automation screen passed. This is a DOM integration check, not a visual browser audit.
- Composer metadata validated. It reports an existing advisory about the broad Sanctum version constraint; dependency versions are fixed by the included lock file.

Run the relevant regression tests on your host:

```bash
php artisan test --filter=Aviation
```
