<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PType;

class PTypesController extends Controller
{
    //
    public function index()
    {
        $sites = PType::siteList();
        return view('ce.ce-layouts.ptypes', compact('sites'));
    }

    public function PTypeList()
    {
        $ptypes = PType::ptypeList(auth()->user());
        return view('ce.ce-tables.ptype-list', compact('ptypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Site' => 'required|max:8',
            'PType' => 'required|max:7',
            'PTypeDesc' => 'required|max:40',
            'DescLabel' => 'required|max:40',
        ]);

        $result = PType::createPType($validated, auth()->user()->userid);
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
            'PType' => 'required|max:7',
            'updatePType' => 'required|max:7',
            'PTypeDesc' => 'required|max:40',
            'DescLabel' => 'required|max:40',
        ]);

        $result = PType::updatePType($validated);
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
        $result = PType::deletePType($request->Site, $request->PType);
        if ($result['error']) {
            return response()->json(['message' => $result['message']], 422);
        }
        return response()->json(['message' => $result['message']]);
    }

}
