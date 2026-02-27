<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserProfileController extends Controller
{
    //
    public function show($userid)
    {
        if ((auth()->user()->level > 3) && auth()->user()->level == null) {
            abort(401, 'Unauthorized');
        }

        $user = User::getUserProfile($userid);
        $siteDetails = User::getSiteDetails($user->site);
        $levelrole = User::getLevelRole($user->level);
        $onlineUser = User::isOnline($user->userid);

        return view('ce.ce-layouts.user-profile', [
            'user' => $user,
            'siteDesc' => $siteDetails['siteDesc'],
            'siteAddress' => $siteDetails['siteAddress'],
            'levelrole' => $levelrole,
            'onlineUser' => $onlineUser
        ]);
    }

    public function update(Request $request, $userid)
    {
        $validated = $request->validate([
            'name' => 'nullable|max:255',
            'gender' => 'nullable|max:10',
            'department' => 'nullable|max:50',
            'section' => 'nullable|max:50',
            'position' => 'nullable|max:50',
            'profile_pic_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        User::updateProfile($userid, $validated, $request->file('profile_pic_url'));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function changePassword(Request $request, $userid)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $result = User::changeUserPassword($userid, $request->current_password, $request->new_password);

        if ($result['error']) {
            return response()->json(['message' => $result['message']], 422);
        }

        return response()->json(['message' => $result['message']]);
    }
}
