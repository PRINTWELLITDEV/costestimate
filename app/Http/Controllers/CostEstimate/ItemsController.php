<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Items;

class ItemsController extends Controller
{
    //
    public function index()
    {
        $sites = Items::siteList();
        return view('ce.ce-layouts.items', compact('sites'));
    }

    public function itemList()
    {
        $items = Items::itemList(auth()->user());
        return view('ce.ce-tables.item-list', compact('items'));
    }

    public function getPtypes(Request $request)
    {
        $site = $request->input('site');
        $ptypes = Items::getPtypes($site);
        return response()->json($ptypes);
    }

    public function additemForm()
    {
        $sites = Items::siteList();
        $units = Items::unitsList();
        return view('ce.ce-layouts.items-add', compact('sites', 'units'));
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
            'unit' => 'required|max:3',
            'item_code' => 'required|unique:items,ItemCode|max:30',
            'item_description' => 'required|max:50',
        ]);
        $result = Items::addItem($validated, auth()->user()->userid);
        if ($request->ajax()) {
            return response()->json(['message' => $result['message']], 200);
        }
    }

    public function editItemForm(Request $request)
    {
        $itemCode = $request->query('item_code');
        $item = Items::getItemByCode($itemCode);
        $sites = Items::siteList();
        $units = Items::unitsList();
        return view('ce.ce-layouts.items-edit', compact('item', 'sites', 'units'));
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
            'unit' => 'required|max:3',
            'item_code' => 'required|max:30',
            'item_description' => 'required|max:50',
            'itemcode' => 'required|max:30',
        ]);
        $result = Items::updateItem($validated, auth()->user()->userid);
        if ($request->ajax()) {
            $status = $result['success'] ? 200 : 422;
            return response()->json(['message' => $result['message']], $status);
        }
    }

    public function delete(Request $request)
    {
        $result = Items::deleteItem($request->Site, $request->ItemCode);
        $status = $result['success'] ? 200 : 404;
        return response()->json(['message' => $result['message']], $status);
    }
}
