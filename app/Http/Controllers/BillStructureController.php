<?php

namespace App\Http\Controllers;

use App\Models\BillStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BillStructureController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = BillStructure::query();

            // Apply filters
            if ($request->has('group')) {
                $query->where('group', $request->group);
            }

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            $parameters = $query->orderBy('name')->get();

            return $this->successResponse($parameters, 'chart of accounts retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve chart of accounts', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'short_name' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'gfs_code' => 'required|string|max:100|unique:bill_structures',
            'group' => 'required|in:direct-cost,tuition-fees,fine,penalty,assets,equity,income,expenses,liabilities,revenue',
            'category' => 'required|in:mandatory,optional',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
        }
        try {
            $parameter = BillStructure::create($request->all());
            return $this->successResponse($parameter, 'chart of account created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create chart of account', 'Failed to create chart of account', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $parameter = BillStructure::find($id);

            if (!$parameter) {
                return $this->errorResponse('chart of account not found', 'Not found', 404);
            }

            return $this->successResponse($parameter, 'chart of account retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve chart of account', 'Failed to retrieve chart of account', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  BillStructure  $paymentParameter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillStructure $billStructure)
    {
        $rules = [
            'short_name' => 'sometimes|string|max:50',
            'name' => 'sometimes|string|max:100',
            'group' => 'sometimes|in:direct-cost,tuition-fees,fine,penalty,assets,equity,income,expenses,liabilities,revenue',
            'category' => 'sometimes|in:mandatory,optional',
        ];

        // Only apply unique rule to gfs_code if it is being changed
        if ($request->has('gfs_code')) {
            if ($request->gfs_code != $billStructure->gfs_code) {
                $rules['gfs_code'] = 'sometimes|string|max:100|unique:bill_structures,gfs_code,' . $billStructure->id;
            } else {
                $rules['gfs_code'] = 'sometimes|string|max:100';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]),
                $validator->errors()->first()
            );
        }

        try {
            $billStructure->update($validator->validated());
            return $this->successResponse($billStructure, 'chart of account updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update chart of account', 'Failed to update chart of account', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BillStructure  $paymentParameter
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillStructure $billStructure)
    {
        try {
            // Check for related records
            $hasRelations = $billStructure->billItems()->exists() ||
                $billStructure->expenditures()->exists() ||
                $billStructure->incomes()->exists();

            if ($hasRelations) {
                return $this->errorResponse(
                    'Cannot delete parameter with related records',
                    'This chart of account is associated with existing records and cannot be deleted.',
                    409
                );
            }

            $billStructure->delete();
            return $this->successResponse(null, 'chart of account deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete chart of account', 'Failed to delete chart of account', 500);
        }
    }
}
