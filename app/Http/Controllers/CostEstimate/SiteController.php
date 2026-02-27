<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site;

class SiteController extends Controller
{
    //
    public function index()
    {
        if (auth()->user()->level != 1) {
            abort(401, 'Unauthorized');
        }
        return view('ce.ce-layouts.sites');
    }

    public function sitelist()
    {
        $sites = Site::siteList();
        return view('ce.ce-tables.site-list', compact('sites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|max:8',
            'site_desc' => 'required|max:255',
            'address' => 'nullable|max:255',
            'site_link' => 'nullable|max:255',
            'logo_pic_url' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $result = Site::createSite($validated, $request->file('logo_pic_url'));
        if ($result['error']) {
            if ($request->ajax()) {
                return response()->json(['message' => $result['message']], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $result['message']]);
        }

        $msg = $validated['site'] . " added successfully!";
        if ($request->ajax()) {
            return response()->json(['message' => $msg]);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|max:8',
            'site_desc' => 'required|max:255',
            'address' => 'nullable|max:255',
            'site_link' => 'nullable|max:255',
            'logo_pic_url' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $result = Site::updateSite($validated, $request->file('logo_pic_url'));
        if ($result['error']) {
            if ($request->ajax()) {
                return response()->json(['message' => $result['message']], 404);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $result['message']]);
        }

        $msg = $validated['site'] . " updated successfully!";
        if ($request->ajax()) {
            return response()->json(['message' => $msg]);
        }
    }

    public function delete($sitecode)
    {
        $result = Site::deleteSite($sitecode, auth()->user()->userid);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
