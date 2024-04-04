<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Filters\V1\CustomerFilter;
use App\Http\Requests\V1\DeleteCustomerRequest;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $filter = new CustomerFilter();
            $filterItems = $filter->transform($request);

            // include invoices if the client requested it
            $includeInvoices = $request->query('includeInvoices');

            $customers = Customer::where($filterItems);

            if ($includeInvoices) {
                $customers = $customers->with('invoices');  // load relationship
            }

            return new CustomerCollection($customers->paginate()->appends($request->query()));
        } catch (Exception) {

            return response()->json(ApiException::SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $customer = Customer::find($id);

            if ($customer) {

                // include invoices if the client requested it
                $includeInvoices = request()->query('includeInvoices');

                if ($includeInvoices) {

                    return new CustomerResource($customer->loadMissing('invoices'));
                }

                return new CustomerResource($customer);
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
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        try {

            $customer = Customer::find($id);

            if ($customer) {

                $customer->update($request->all());
            } else {

                return response()->json(ApiException::NOT_FOUND);
            }
        } catch (Exception) {

            return response()->json(ApiException::SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCustomerRequest $request, $id)
    {
        try {

            $customer = Customer::find($id);

            if ($customer) {

                $customer->delete();
            } else {

                return response()->json(ApiException::NOT_FOUND);
            }
        } catch (Exception) {

            return response()->json(ApiException::SERVER_ERROR);
        }
    }
}
