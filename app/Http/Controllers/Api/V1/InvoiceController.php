<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\InvoicesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Http\Resources\V1\Invoice\InvoiceCollection;
use App\Http\Resources\V1\Invoice\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    public function index(Request $request): InvoiceCollection
    {
        $filter = new InvoicesFilter();
        $queryItems = $filter->transform($request); // ['column', 'operator', 'value']

        if (count($queryItems) == 0) {
            return new InvoiceCollection(Invoice::query()->paginate());
        }
        else {
            $invoices = Invoice::query()->where($queryItems);
            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }

    public function store(StoreInvoiceRequest $request) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => 'To be completed'
        ]);
    }

    public function bulkStore(BulkStoreInvoiceRequest $request): void
    {
        $bulk = collect($request->all())->map(function($arr, $key){
            return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
        });

        Invoice::query()->insert($bulk->toArray());
    }

    public function show($id) : JsonResponse
    {
        $invoice = Invoice::query()->find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Invoice not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => new InvoiceResource($invoice)
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => 'Invoice Updated Successfully'
        ]);
    }

    public function destroy(Invoice $invoice) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => 'Invoice Deleted Successfully'
        ]);
    }
}
