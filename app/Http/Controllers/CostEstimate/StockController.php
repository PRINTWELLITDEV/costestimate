<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stocks;

class StockController extends Controller
{
    //
    public function index()
    {
        $sites = Stocks::siteList();
        return view('ce.ce-layouts.stocks', compact('sites'));
    }

    public function stockList()
    {
        $stocks = Stocks::stockList(auth()->user());
        return view('ce.ce-tables.stock-list', compact('stocks'));
    }

    public function getPtypes(Request $request)
    {
        $site = $request->input('site');
        $ptypes = Stocks::getPtypes($site);
        return response()->json($ptypes);
    }

    public function addStockForm()
    {
        $sites = Stocks::siteList();
        $units = Stocks::unitsList();
        return view('ce.ce-layouts.stocks-add', compact('sites', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Site' => 'required|max:8',
            'ptype' => 'required|max:7',
            'product_group' => 'required|max:20',
            'gsm' => 'nullable|numeric',
            'caliper' => 'nullable|numeric',
            'pounds_ream' => 'nullable|numeric',
            'chipboard_no' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'stock_code' => 'required|unique:stocks,StockCode|max:30',
            'stock_description' => 'required|max:50',
        ]);
        $result = Stocks::addStock($validated, auth()->user()->userid);
        if ($request->ajax()) {
            return response()->json(['message' => $result['message']], 200);
        }
    }

    public function editStockForm(Request $request)
    {
        $stockCode = $request->query('stock_code');
        $stock = Stocks::getStockByCode($stockCode);
        $sites = Stocks::siteList();
        $units = Stocks::unitsList();
        return view('ce.ce-layouts.stocks-edit', compact('stock', 'sites', 'units'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|max:8',
            'ptype' => 'required|max:7',
            'product_group' => 'required|max:20',
            'gsm' => 'nullable|numeric',
            'caliper' => 'nullable|numeric',
            'pounds_ream' => 'nullable|numeric',
            'chipboard_no' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'stock_code' => 'required|max:30',
            'stock_description' => 'required|max:50',
            'stockcode' => 'required|max:30',
        ]);
        $result = Stocks::updateStock($validated, auth()->user()->userid);
        if ($request->ajax()) {
            $status = $result['success'] ? 200 : 422;
            return response()->json(['message' => $result['message']], $status);
        }
    }

    public function delete(Request $request)
    {
        $result = Stocks::deleteStock($request->Site, $request->StockCode);
        $status = $result['success'] ? 200 : 404;
        return response()->json(['message' => $result['message']], $status);
    }
}
