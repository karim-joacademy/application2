<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\CustomersFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\Customer\CustomerCollection;
use App\Http\Resources\V1\Customer\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
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

    public function store(StoreCustomerRequest $request) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 201,
            'data' => new CustomerResource(Customer::query()->create($request->all())),
        ]);
    }

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

    public function update(UpdateCustomerRequest $request, Customer $customer) : JsonResponse
    {
        $response = $customer->update($request->all());

        if($response) {
            return response()->json([
                'success' => true,
                'status' => 200,
                'data' => 'Successfully updated customer'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 500,
                'data' => 'Failed to update customer'
            ]);
        }
    }

    public function destroy(Customer $customer) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => 'Customer Deleted Successfully'
        ]);
    }
}
