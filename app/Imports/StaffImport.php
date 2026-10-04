<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\Branch;
use App\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffImport implements ToCollection, WithHeadingRow
{
    private $errors = [];
    private $rowCount = 0;
    private $updateExisting;
    private $companyId;
    private $branches;
    private $sections;
    private $positionsMap;

    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
        $this->companyId = auth()->user()->staffs->branch->company_id;
        $this->loadBranchesAndSections();
        $this->loadPositionsMap();
    }

    // Add this method to specify the heading row
    public function headingRow(): int
    {
        return 2; // Headers are in row 2 (A2:L2)
    }

    private function loadBranchesAndSections()
    {
        // Load all branches for the company
        $this->branches = Branch::where('company_id', $this->companyId)
            ->get()
            ->keyBy('branch_name');

        // Load all sections for the company (matching export logic)
        $this->sections = Section::get()
            ->keyBy('name');
    }

    private function loadPositionsMap()
    {
        $positions = config('common.positions', []);
        
        // Create mapping from formatted name to key
        $this->positionsMap = [];
        foreach ($positions as $key => $value) {
            $formattedName = ucfirst(str_replace('_', ' ', $value));
            $this->positionsMap[$formattedName] = $key;
        }
    }

    public function collection(Collection $rows)
    {
        $validInitials = ['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.', 'Eng.', 'Sr.', 'Rev.', 'Fr.', 'Hon.'];
        $validGenders = ['male', 'female', 'other'];
        $validStatuses = ['active', 'inactive'];

        foreach ($rows as $index => $row) {
            $this->rowCount++;

            // Skip empty rows
            if (empty(array_filter($row->toArray()))) {
                continue;
            }

            try {
                // Prepare data
                $data = [
                    'initial' => $row['initial'] ?? null,
                    'first_name' => $row['first_name'] ?? null,
                    'last_name' => $row['last_name'] ?? null,
                    'gender' => $row['gender'] ?? null,
                    'employee_number' => $row['employee_number'] ?? null,
                    'phone' => $row['phone'] ?? null,
                    'email' => $row['email'] ?? null,
                    'position' => $row['position'] ?? null,
                    'status' => strtolower($row['status'] ?? 'active'),
                    'branch_name' => $row['branch'] ?? null,
                    'section_name' => $row['section'] ?? null,
                    'bio' => $row['bio'] ?? null,
                ];

                // Convert formatted position name back to key
                $positionKey = $this->positionsMap[$data['position']] ?? null;
                
                // Validate row data
                $validator = Validator::make($data, [
                    'initial' => 'required|in:' . implode(',', $validInitials),
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'gender' => 'required|in:' . implode(',', $validGenders),
                    'employee_number' => 'required|max:50|unique:staff,employee_number',
                    'phone' => 'required|max:20',
                    'email' => 'nullable|email|max:100|unique:staff,email',
                    'position' => 'required',
                    'status' => 'required|in:' . implode(',', $validStatuses),
                    'branch_name' => 'required|string',
                    'section_name' => 'required|string',
                ]);

                // Add custom validation for position
                $validator->after(function ($validator) use ($positionKey, $data) {
                    if (!$positionKey) {
                        $validator->errors()->add('position', "Position '{$data['position']}' is not valid. Available positions: " . implode(', ', array_keys($this->positionsMap)));
                    }
                });

                if ($validator->fails()) {
                    $this->errors[] = "Row " . ($index + 3) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Find branch
                $branch = $this->branches->get($data['branch_name']);
                if (!$branch) {
                    $this->errors[] = "Row " . ($index + 3) . ": Branch '{$data['branch_name']}' not found. Available branches: " . $this->branches->keys()->implode(', ');
                    continue;
                }

                // Find section
                $section = $this->sections->get($data['section_name']);
                if (!$section) {
                    $availableSections = $this->sections->keys()->implode(', ');
                    $this->errors[] = "Row " . ($index + 3) . ": Section '{$data['section_name']}' not found. Available sections: " . $availableSections;
                    continue;
                }

                // // Verify section belongs to the branch
                // if ($section->branch_id != $branch->id) {
                //     $this->errors[] = "Row " . ($index + 3) . ": Section '{$data['section_name']}' does not belong to branch '{$data['branch_name']}'";
                //     continue;
                // }

                // Check if staff exists for update
                $existingStaff = Staff::where('employee_number', $data['employee_number'])->first();

                if ($existingStaff && $this->updateExisting) {
                    // Update existing staff
                    $existingStaff->update([
                        'initial' => $data['initial'],
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'gender' => $data['gender'],
                        'phone' => $data['phone'],
                        'email' => $data['email'],
                        'position' => $positionKey,
                        'status' => $data['status'],
                        'branch_id' => $branch->id,
                        'section_id' => $section->id,
                        'bio' => $data['bio'],
                    ]);
                } elseif (!$existingStaff) {
                    // Create new staff
                    Staff::create([
                        'initial' => $data['initial'],
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'gender' => $data['gender'],
                        'employee_number' => $data['employee_number'],
                        'phone' => $data['phone'],
                        'email' => $data['email'],
                        'position' => $positionKey,
                        'status' => $data['status'],
                        'branch_id' => $branch->id,
                        'section_id' => $section->id,
                        'bio' => $data['bio'],
                    ]);
                } else {
                    $this->errors[] = "Row " . ($index + 3) . ": Staff with employee number '{$data['employee_number']}' already exists and update not enabled";
                }

            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 3) . ": " . $e->getMessage();
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}