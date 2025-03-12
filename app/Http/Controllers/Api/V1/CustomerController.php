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
    public function index(Request $request)
    {
        $filter = new CustomersFilter();
        $queryItems = $filter->transform($request); // ['column', 'operator', 'value']

        if (count($queryItems) == 0) {
            return new CustomerCollection(Customer::query()->paginate());
        }
        else {
            $customers = Customer::query()->where($queryItems)->paginate();
            return new CustomerCollection($customers->appends($request->query()));
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
            'data' => 'to be completed'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) : JsonResponse
    {
        $customer = Customer::query()->find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Customer not found'
            ], 404);
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
