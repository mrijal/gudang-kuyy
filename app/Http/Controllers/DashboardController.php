<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->data;
        $yearEarnings = 0;
        $monthEarnings = 0;

        $topSaleProduct = null;
        $recentTransaction = null;

        // calculate year earnings from early year to today date
        $yearEarnings = Outbound::whereNotNull('total_payment')->whereYear('created_at', date('Y'))->sum('total_payment');
        $yearExpenses = Inbound::whereNotNull('total_payment')->whereYear('created_at', date('Y'))->sum('total_payment');

        // calculate year earnings from early last year to today date in last year
        $lastYearEarnings = Outbound::whereNotNull('total_payment')->whereYear('created_at', date('Y') - 1)->sum('total_payment');
        $lastYearExpenses = Inbound::whereNotNull('total_payment')->whereYear('created_at', date('Y') - 1)->sum('total_payment');

        // calculate percentage of year earnings and expenses from last year
        $yearEarningsPercentage = 0;
        $yearExpensesPercentage = 0;

        if ($lastYearEarnings > 0) {
            // calculate difference between year earnings and last year earnings then divide by last year earnings

            if ($yearEarnings >= $lastYearEarnings) {
                $yearEarningsPercentage = (($yearEarnings - $lastYearEarnings) / $yearEarnings) * 100;
            } else {
                $yearEarningsPercentage = (($yearEarnings - $lastYearEarnings) / $lastYearEarnings) * 100;
            }
        }

        if ($lastYearExpenses > 0) {
            // calculate difference between year expenses and last year expenses then divide by last year expenses
            if ($yearExpenses >= $lastYearExpenses) {
                $yearExpensesPercentage = (($yearExpenses - $lastYearExpenses) / $yearExpenses) * 100;
            } else {
                $yearExpensesPercentage = (($yearExpenses - $lastYearExpenses) / $lastYearExpenses) * 100;
            }
        }

        $yearEarningsPercentage = number_format($yearEarningsPercentage, 0);
        $yearExpensesPercentage = number_format($yearExpensesPercentage, 0);
        $yearEarnings = number_format($yearEarnings, 0, ',', '.');
        $yearExpenses = number_format($yearExpenses, 0, ',', '.');

        $top6ProductOutbound = OutboundDetail::selectRaw('product_id, sum(quantity) as total_quantity, products.name as product_name, products.image as product_image')
            ->join('products', 'products.id', '=', 'product_id')
            ->groupBy('product_id', 'products.name', 'products.image') // Tambahkan kolom yang tidak ada dalam agregasi
            ->orderBy('total_quantity', 'desc')
            ->limit(6)
            ->get();


        $recentTransaction = Outbound::with('details.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $data['top6ProductOutbound'] = $top6ProductOutbound;
        $data['recentTransaction'] = $recentTransaction;
        $data['yearEarnings'] = $yearEarnings;
        $data['yearExpenses'] = $yearExpenses;
        $data['yearEarningsPercentage'] = $yearEarningsPercentage;
        $data['yearExpensesPercentage'] = $yearExpensesPercentage;

        return view('main.dashboard', $data);
    }
}
