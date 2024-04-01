<?php

namespace App\Services\V1;

use Illuminate\Http\Request;

class CustomerQuery
{

    // DON'T TRUST USER INPUTS
    // ALLOWED QUERY PARAMS TO FILTER ON
    // AND ALLOWED OPERATORS(gt,lt,eq) FOR EACH PARAM
    protected $safeParams = [
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['lt', 'eq', 'gt']
    ];

    // TRANSFORM/TRANSLATE QUERY PARAMS TO DATABASE COLUMNS
    protected $columnMap = [
        'postalCode' => 'postal_code',  // postalCode param -> postal_code db column
    ];

    // TRANSFORM/TRANSLATE QUERY PARAM OPERATORS(eq,lt,gt etc...) TO ELOQUENT/DATABASE OPERATORS(=, <, > etc...)
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    function transform(Request $request)
    {
        // The array to pass to eloquent
        $eloquentQuery = [];

        foreach ($this->safeParams as $param => $operators) { // eg: param:postalCode => operators:['lt','eq','gt']
            // check if there is a query string for a particular safe param
            $query = $request->query($param);   // eg: /api/customers?postalCode[gt]=30000

            if (!isset($query)) {
                continue;
            }

            // if there is, get the column to use in the query
            // transform to db column in case the param and db column don't match
            $column = $this->columnMap[$param] ?? $param; // eg: city -> city, postalCode -> postal_code

            // check which operator the query string has for the param
            foreach ($operators as $operator) {
                if (isset($query[$operator])) { // eg: postalCode['gt']
                    $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]]; // eg: Customer::where(column, operator, value)
                }
            }
        }

        return $eloquentQuery;
    }
}
