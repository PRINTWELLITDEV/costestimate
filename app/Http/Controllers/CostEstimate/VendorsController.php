<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendors;

class VendorsController extends Controller
{
    //
    public function index()
    {
        $sites = Vendors::siteList();
        $currencies = Vendors::currencyList();
        return view('ce.ce-layouts.vendors', compact('sites', 'currencies'));

    }

    public function vendorList()
    {
        $vendors = Vendors::vendorList(auth()->user());
        return view('ce.ce-tables.vendor-list', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Site' => 'required|max:8',
            'Group' => 'nullable|max:10',
            'Vendnum' => 'required|max:10',
            'Name' => 'required|max:255',
            'Currcode' => 'required|max:3',
        ]);

        $result = Vendors::createVendor($validated, auth()->user()->userid);
        if ($result['error']) {
            if ($request->ajax()) {
                return response()->json(['message' => $result['message']], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $result['message']]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => $result['message']]);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'Site' => 'required|max:8',
            'Group' => 'nullable|max:10',
            'Vendnum' => 'required|max:10',
            'Name' => 'required|max:255',
            'Currcode' => 'required|max:3',
        ]);

        $result = Vendors::updateVendor($validated);
        if ($result['error']) {
            if ($request->ajax()) {
                return response()->json(['message' => $result['message']], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $result['message']]);
        }

        if ($request->ajax()) {
            return response()->json(['message' => $result['message']]);
        }
    }

    public function delete(Request $request)
    {
        $result = Vendors::deleteVendor($request->Site, $request->Vendnum);
        if ($result['error']) {
            return response()->json(['message' => $result['message']], 422);
        }
        return response()->json(['message' => $result['message']]);
    }
}
