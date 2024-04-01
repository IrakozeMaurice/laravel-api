<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    // DON'T TRUST USER INPUTS
    // ALLOWED QUERY PARAMS TO FILTER ON
    // AND ALLOWED OPERATORS(gt,lt,eq) FOR EACH PARAM
    protected $safeParams = [];

    // TRANSFORM/TRANSLATE QUERY PARAMS TO DATABASE COLUMNS
    protected $columnMap = [];

    // TRANSFORM/TRANSLATE QUERY PARAM OPERATORS(eq,lt,gt etc...) TO ELOQUENT/DATABASE OPERATORS(=, <, > etc...)
    protected $operatorMap = [];

    function transform(Request $request)
    {
        // The array to pass to eloquent
        $eloquentQuery = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            }

            // if there is, get the column to use in the query
            // transform to db column in case the param and db column don't match
            $column = $this->columnMap[$param] ?? $param;

            // check which operator the query string has for the param
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloquentQuery;
    }
}
