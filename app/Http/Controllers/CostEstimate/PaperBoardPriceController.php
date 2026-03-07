<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaperBoardPricing;

class PaperBoardPriceController extends Controller
{
    public function index()
    {
        $sites = PaperBoardPricing::siteList();
        return view('ce.ce-layouts.paperboardprice', compact('sites'));
    }

    public function pricingList()
    {
        $pricings = PaperBoardPricing::pricingList(auth()->user());
        return view('ce.ce-tables.pbp-list', compact('pricings'));
    }

    // New method to fetch all ptypes
    public function getPTypes()
    {
        try {
            $ptypes = PaperBoardPricing::getPTypes();
            return response()->json($ptypes);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching paper types: ' . $e->getMessage()], 500);
        }
    }

    // New method to fetch all vendors
    public function getVendors()
    {
        try {
            $vendors = PaperBoardPricing::getVendors();
            return response()->json($vendors);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching vendors: ' . $e->getMessage()], 500);
        }
    }

    // New method to fetch all stocks
    public function getStocks()
    {
        try {
            $stocks = PaperBoardPricing::getStocks();
            return response()->json($stocks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching stocks: ' . $e->getMessage()], 500);
        }
    }

    public function getStockCode(Request $request)
    {
        try {
            $stocks = PaperBoardPricing::getStockCode($request->site, $request->ptype);
            return response()->json($stocks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching stock codes: ' . $e->getMessage()], 500);
        }
    }

    public function getUM()
    {
        try {
            $um = PaperBoardPricing::getUM();
            return response()->json($um);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching unit of measure: ' . $e->getMessage()], 500);
        }
    }

    public function getVendorCurrency(Request $request)
    {
        try {
            $result = PaperBoardPricing::getVendorCurrency($request->site, $request->vendnum);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching vendor currency: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Site' => 'required|max:8',
            'Group' => 'nullable|max:10',
            'PType' => 'required|max:7',
            'Vendor' => 'required|max:10',
            'StockCode' => 'required|max:30',
            'UM' => 'required|max:10',
            'Currcode' => 'required|max:3',
            'Price_MT' => 'nullable|numeric',
            'Price_Sheet' => 'nullable|numeric',
            'Price_Pound' => 'nullable|numeric',
            'Price_Bale' => 'nullable|numeric',
            'EffectiveDate' => 'required|date',
        ]);
        $result = PaperBoardPricing::storePricing($validated, auth()->user()->username);
        return response()->json(['message' => $result['message']]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'Site' => 'required|max:8',
            'id' => 'required|integer',
            'Group' => 'nullable|max:10',
            'PType' => 'required|max:7',
            'Vendor' => 'required|max:10',
            'StockCode' => 'required|max:30',
            'UM' => 'required|max:10',
            'Currcode' => 'required|max:3',
            'Price_MT' => 'nullable|numeric',
            'Price_Sheet' => 'nullable|numeric',
            'Price_Pound' => 'nullable|numeric',
            'Price_Bale' => 'nullable|numeric',
            'EffectiveDate' => 'required|date',
        ]);
        $result = PaperBoardPricing::updatePricing($validated);
        if ($result['error']) {
            return response()->json(['message' => $result['message']], 404);
        }
        return response()->json(['message' => $result['message']]);
    }

    public function delete(Request $request)
    {
        $result = PaperBoardPricing::deletePricing($request->Site, $request->PricingId);
        if ($result['error']) {
            return response()->json(['message' => $result['message']], 404);
        }
        return response()->json(['message' => $result['message']]);
    }

    public function pbpcalculatorForm()
    {
        $sites = PaperBoardPricing::siteList();
        $stockCodes = PaperBoardPricing::getStocks();

        return view('ce.ce-layouts.pbp-calculator', compact('sites', 'stockCodes'));
    }

    public function pbpCalculate(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|max:8',
            'StockCode' => 'required|max:30',
            'StockDesc' => 'required|max:255',
            'PType' => 'required|max:20',
            'GSM' => 'required|numeric',
            'SheetMM_L' => 'required|numeric',
            'SheetMM_W' => 'required|numeric',
            'SheetIN_L' => 'required|numeric',
            'SheetIN_W' => 'required|numeric',
            'FXRate' => 'required|numeric',
            'DutyRate' => 'nullable|numeric',
            'OtherChargesRate' => 'nullable|numeric',
            'SheetingCost' => 'nullable|numeric',
        ]);

        try {
            $result = PaperBoardPricing::getPricingForCalculator(
                $validated['site'],
                $validated['StockCode'],
                $validated['PType'],
                $validated['GSM'],
                [
                    'FXRate' => (float) $validated['FXRate'],
                    'DutyRate' => (float) ($validated['DutyRate'] ?? 0),
                    'OtherChargesRate' => (float) ($validated['OtherChargesRate'] ?? 0),
                    'SheetingCost' => (float) ($validated['SheetingCost'] ?? 0),
                    'SheetIN_L' => (float) $validated['SheetIN_L'],
                    'SheetIN_W' => (float) $validated['SheetIN_W'],
                    'SheetMM_L' => (float) $validated['SheetMM_L'],
                    'SheetMM_W' => (float) $validated['SheetMM_W'],
                ]
            );
            $msg = 'Calculation successful.';
            return response()->json(['result' => $result, 'message' => $msg] );
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error calculating price: ' . $e->getMessage()], 500);
        }
    }
}
