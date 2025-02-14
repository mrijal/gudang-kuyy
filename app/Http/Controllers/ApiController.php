<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Outbound;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getProduct($id)
    {
        $data = Product::find($id);
        return response()->json($data);
    }

    public function getEarningsExpensesData()
    {
        // calculate earnings and expenses for each month to 6 months ago
        $data = [];
        $data['earnings'] = [];
        $data['expenses'] = [];
        $data['months'] = [];

        for ($i = 0; $i < 6; $i++) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));

            $earnings = Outbound::whereNotNull('total_payment')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_payment');

            $expenses = Inbound::whereNotNull('total_payment')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_payment');

            $data['earnings'][] = $earnings;
            $data['expenses'][] = $expenses;
            $data['months'][] = date('M Y', strtotime("-$i months"));
        }

        // sort the data by month in ascending order
        $data['earnings'] = array_reverse($data['earnings']);
        $data['expenses'] = array_reverse($data['expenses']);
        $data['months'] = array_reverse($data['months']);

        return response()->json($data);
    }
}
