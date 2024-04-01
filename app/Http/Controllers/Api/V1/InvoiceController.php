<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Filters\V1\InvoiceFilter;
use Exception;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return new InvoiceCollection(Invoice::all());
        try {

            $filter = new InvoiceFilter;
            $queryItems = $filter->transform($request);

            if (count($queryItems) == 0) {
                return new InvoiceCollection(Invoice::paginate());
            } else {
                $invoices = Invoice::where($queryItems)->paginate();
                return new InvoiceCollection($invoices->appends($request->query()));
            }
        } catch (Exception) {
            return response()->json(ApiException::SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvoiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::find($id);
            if ($invoice) {
                return new InvoiceResource($invoice);
            } else {
                return response()->json(ApiException::NOT_FOUND);
            }
        } catch (Exception) {
            return response()->json(ApiException::SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvoiceRequest  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
