<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\CustomersFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\V1\Customer\CustomerCollection;
use App\Http\Resources\V1\Customer\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $filter = new CustomersFilter();
            $filterItems = $filter->transform($request); // ['column', 'operator', 'value']

            $includeInvoices = $request->query('includeInvoices');

            $customers = Customer::query()->where($filterItems);

            if(!$customers->exists()) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'data' => [],
                    'message' => 'no data were found.'
                ]);
            }

            if($includeInvoices) {
                $customers = $customers->with(['invoices']);

                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'data' => new CustomerCollection($customers->paginate()->appends($request->query()))
                ]);
            }
            return response()->json([
                'success' => true,
                'status' => 200,
                'data' => new CustomerCollection($customers->paginate())
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'data' => [],
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 201,
            'data' => new CustomerResource(Customer::query()->create($request->all())),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer) : JsonResponse
    {
        $includeInvoices = request()->query('includeInvoices');

        if($includeInvoices) {
            return response()->json([
                'success' => true,
                'status' => 200,
                'data' => new CustomerResource($customer->loadMissing('invoices')),
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => new CustomerResource($customer)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 201,
            'data' => 'to be completed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => 'Customer Deleted Successfully'
        ]);
    }
}
