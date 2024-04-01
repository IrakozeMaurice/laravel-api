<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Services\V1\CustomerQuery;
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
        // CustomerCollection will assume that there is a CustomerResource
        // and uses that to transform every record accordingly

        // return new CustomerCollection(Customer::all()); // returns all data
        try {
            $filter = new CustomerQuery();
            $queryItems = $filter->transform($request); // return [[colum,operator,value], [etc...]]
            if (count($queryItems) == 0) {
                return new CustomerCollection(Customer::paginate(10)); // paginate the data (10 results per page)
            } else {
                return new CustomerCollection(Customer::where($queryItems)->paginate());
            }
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
        //
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
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
