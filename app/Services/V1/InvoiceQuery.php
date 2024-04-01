<?php

namespace App\Services\V1;

use Illuminate\Http\Request;

class InvoiceQuery
{

    protected $safeParams = [
        'customerId' => ['eq'],
        'amount' => ['eq', 'lt', 'gt'],
        'status' => ['eq'],
        'billedDate' => ['eq', 'lt', 'gt'],
        'paidDate' => ['eq', 'lt', 'gt'],
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    protected $columnMap = [
        'customerId' => 'customer_id',
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date',
    ];

    function transform(Request $request)
    {
        $eloquentQuery = [];

        foreach ($this->safeParams as $param => $operators) {

            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloquentQuery;
    }
}
