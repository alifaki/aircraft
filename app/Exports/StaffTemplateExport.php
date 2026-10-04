<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class StaffTemplateExport implements FromArray, WithHeadings, WithTitle, WithStrictNullComparison, WithStyles, WithEvents, WithColumnWidths
{
    private $companyId;
    private $branches;
    private $sections;
    private $positions;

    public function __construct()
    {
        $this->companyId = auth()->user()->staffs->branch->company_id;
        $this->loadBranchesAndSections();
        $this->loadPositions();
    }

    private function loadBranchesAndSections()
    {
        // Load all branches for the company
        $this->branches = Branch::where('company_id', $this->companyId)
            ->get()
            ->pluck('branch_name')
            ->toArray();

        // Load all sections for the company (not grouped by branch)
        $this->sections = Section::get()
            ->pluck('name')
            ->unique()
            ->toArray();
    }

    private function loadPositions()
    {
        // Get position values instead of keys
        $positions = config('common.positions', []);
        $this->positions = array_values($positions);
        
        // Format position names to be more readable
        $this->positions = array_map(function($position) {
            return ucfirst(str_replace('_', ' ', $position));
        }, $this->positions);
        
        // Limit to first 5 positions
        $this->positions = array_slice($this->positions, 0, 23);
    }

    public function array(): array
    {
        // Return empty array but ensure headings are displayed
        return [];
    }

    public function headings(): array
    {
        return [
            'Initial*',
            'First Name*',
            'Last Name*',
            'Gender*',
            'Employee Number*',
            'Phone*',
            'Email',
            'Position*',
            'Status*',
            'Branch*',
            'Section*',
            'Bio'
        ];
    }

    public function title(): string
    {
        return 'Staff Template';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Define options
                $initials = ['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.', 'Eng.', 'Sr.', 'Rev.', 'Fr.', 'Hon.'];
                $genders = ['male', 'female', 'other'];
                $statuses = ['active', 'inactive'];
                
                // Set data validation for dropdowns starting from row 2 (after headers)
                $this->addDropdownValidation($sheet, 'A2:A1000', $initials); // Initial
                $this->addDropdownValidation($sheet, 'D2:D1000', $genders); // Gender
                $this->addDropdownValidation($sheet, 'H2:H1000', $this->positions); // Position
                $this->addDropdownValidation($sheet, 'I2:I1000', $statuses); // Status
                $this->addDropdownValidation($sheet, 'J2:J1000', $this->branches); // Branch
                $this->addDropdownValidation($sheet, 'K2:K1000', $this->sections); // Section
                
                // Add title at the top
                $this->addTemplateTitle($sheet);
            },
        ];
    }

    private function addTemplateTitle($sheet)
    {
        // Insert title row above headers
        $sheet->insertNewRowBefore(1, 1);
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'STAFF REGISTRATION TEMPLATE');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2D5FF5'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(30);
    }

    private function addDropdownValidation($sheet, $range, $options)
    {
        $validation = $sheet->getDataValidation($range);
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please pick a value from the dropdown list.');
        $validation->setFormula1('"' . implode(',', $options) . '"');
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row (now row 2 after inserting title)
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2D5FF5'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Set row height for better visibility
        $sheet->getRowDimension(1)->setRowHeight(25); // Title row
        // $sheet->getRowDimension(2)->setRowHeight(25); // Header row

        // Freeze the header row so it stays visible when scrolling
        $sheet->freezePane('A2');

        return [
            
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2D5FF5'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12, // Initial
            'B' => 15, // First Name
            'C' => 15, // Last Name
            'D' => 10, // Gender
            'E' => 18, // Employee Number
            'F' => 15, // Phone
            'G' => 20, // Email
            'H' => 20, // Position
            'I' => 10, // Status
            'J' => 15, // Branch
            'K' => 15, // Section
            'L' => 25, // Bio
        ];
    }
}