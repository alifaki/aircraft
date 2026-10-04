<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateGroupBills extends Command
{
    // app/Console/Commands/GenerateGroupBills.php

    protected $signature = 'billing:generate {--date=}';
    protected $description = 'Generate bills for all customer groups';

    public function handle()
    {
        $billingDate = $this->option('date') ? Carbon::parse($this->option('date')) : now();

        BillGroup::query()
            ->where('is_active', true)
            ->where(function($query) use ($billingDate) {
                // Check if it's the right time to bill based on frequency
                $query->where(function($q) use ($billingDate) {
                    $q->where('billing_frequency', 'monthly')
                        ->whereDay('billing_day', $billingDate->day);
                })
                    ->orWhere(function($q) use ($billingDate) {
                        $q->where('billing_frequency', 'quarterly')
                            ->whereMonth('billing_day', $billingDate->month)
                            ->whereIn($billingDate->month, [3, 6, 9, 12])
                            ->whereDay('billing_day', $billingDate->day);
                    })
                    ->orWhere(function($q) use ($billingDate) {
                        $q->where('billing_frequency', 'annual')
                            ->whereMonth('billing_day', $billingDate->month)
                            ->whereDay('billing_day', $billingDate->day);
                    });
            })
            ->with(['activeCustomers', 'costs'])
            ->each(function($group) use ($billingDate) {
                $this->info("Processing bill group: {$group->name}");

                $group->activeCustomers->each(function($customer) use ($group, $billingDate) {
                    $this->generateBillForCustomer($customer, $group, $billingDate);
                });
            });
    }

    protected function generateBillForCustomer($customer, $group, $billingDate)
    {
        // Check if bill already exists for this period
        $existingBill = CustomerBill::where('customer_id', $customer->id)
            ->where('bill_group_id', $group->id)
            ->whereYear('bill_date', $billingDate->year)
            ->whereMonth('bill_date', $billingDate->month)
            ->exists();

        if ($existingBill) {
            $this->warn("Bill already exists for customer {$customer->id} in group {$group->id}");
            return;
        }

        try {
            DB::beginTransaction();

            // Calculate due date based on group settings
            $dueDate = $billingDate->copy()->addDays($group->payment_terms_days ?? 30);

            $bill = CustomerBill::create([
                'customer_id' => $customer->id,
                'bill_group_id' => $group->id,
                'bill_date' => $billingDate,
                'due_date' => $dueDate,
                'status' => 'draft',
                'total_amount' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'grand_total' => 0,
            ]);

            $totalAmount = 0;
            $totalTax = 0;

            // Add all active costs from the group
            foreach ($group->costs()->where('is_active', true)->get() as $cost) {
                $taxAmount = $cost->is_taxable ? ($cost->amount * ($group->tax_rate / 100)) : 0;

                $bill->items()->create([
                    'bill_group_cost_id' => $cost->id,
                    'description' => $cost->name,
                    'quantity' => 1,
                    'unit_price' => $cost->amount,
                    'tax_rate' => $cost->is_taxable ? $group->tax_rate : 0,
                    'tax_amount' => $taxAmount,
                    'discount_rate' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $cost->amount + $taxAmount,
                ]);

                $totalAmount += $cost->amount;
                $totalTax += $taxAmount;
            }

            // Apply customer-specific discounts if any
            $discountAmount = 0;
            if ($customer->discount_rate) {
                $discountAmount = $totalAmount * ($customer->discount_rate / 100);
            }

            // Update bill totals
            $bill->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $totalTax,
                'discount_amount' => $discountAmount,
                'grand_total' => $totalAmount + $totalTax - $discountAmount,
                'status' => 'sent', // Or keep as draft for review
            ]);

            DB::commit();

            $this->info("Generated bill #{$bill->id} for customer {$customer->id}");

            // Dispatch event for notifications
            event(new BillGenerated($bill));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to generate bill for customer {$customer->id}: " . $e->getMessage());
            Log::error("Bill generation failed", ['error' => $e]);
        }
    }
}
