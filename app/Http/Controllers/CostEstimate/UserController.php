<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\Level;


class UserController extends Controller
{
    //
    public function index()
    {
        if (auth()->user()->level != 1) {
            abort(401, 'Unauthorized');
        }
        $sites = User::activeSites();
        $levels = User::getLevels();

        $totalUsers = User::totalUsers();
        $totalStatusActive = User::totalStatusActive();
        $totalLevel1 = User::totalLevel1();

        return view('ce.ce-layouts.users', compact('sites', 'levels', 'totalUsers', 'totalStatusActive', 'totalLevel1'));
    }

    public function userlist()
    {
        $users = User::userList(auth()->user()->userid);
        return view('ce.ce-tables.user-list', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|max:8',
            'userid' => 'required|max:8',
            'name' => 'required|max:255',
            'password' => 'required|max:255',
            'email' => 'required|email|max:255',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $result = User::createUser($validated, $request->file('profile_pic_url'));
        if ($result['error']) {
            if ($request->ajax()) {
                return response()->json(['message' => $result['message']], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $result['message']]);
        }

        $msg = "{$validated['userid']} user successfully!";
        if ($request->ajax()) {
            return response()->json(['message' => $msg]);
        }
        // return redirect('/ce/users')->with('success', $msg);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|max:8',
            'userid' => 'required|max:8',
            'name' => 'required|max:255',
            'password' => 'nullable|max:255',
            'email' => 'required|email|max:255',
            'department' => 'nullable|max:255',
            'section' => 'nullable|max:255',
            'position' => 'nullable|max:255',
            'level' => 'nullable|integer',
            'status' => 'nullable|integer',
            'gender' => 'nullable|max:10',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $result = User::updateUser($validated, $request->file('profile_pic_url'), $request->input('password'));
        if ($result['error']) {
            if ($request->ajax()) {
                return response()->json(['message' => $result['message']], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $result['message']]);
        }

        return response()->json(['message' => $result['message']]);
    }

    public function delete($userid)
    {
        $result = User::deleteUser($userid, auth()->user()->userid);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
