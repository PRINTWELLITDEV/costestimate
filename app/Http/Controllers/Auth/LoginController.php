<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    // public function showhomeForm()
    // {
    //     if (Auth::check()) {
    //         return redirect()->route('home');
    //     }

    //     return view('home');
    // }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // return view('home');
        return redirect()->route('home');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'userid'   => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Find user by userid
        $user = User::where('userid', $credentials['userid'])->first();

        // If user ID does not exist
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Credentials'
                ], 401);
            }
            throw ValidationException::withMessages([
                'userid' => [trans('auth.failed')],
            ]);
        }

        // If user is deleted
        if ($user->deleted_at <> NULL) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Credentials.'
                ], 403);
            }
            throw ValidationException::withMessages([
                'userid' => ['Invalid Credentials.'],
            ]);
        }

        // If user exists, check status
        if ($user->status != 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is inactive. <br> Please contact administrator.'
                ], 403);
            }
            throw ValidationException::withMessages([
                'userid' => ['Account is inactive. <br> Please contact administrator.'],
            ]);
        }

        // Check password

        try {
            if (Hash::check($credentials['password'], $user->password)) {
                if ($request->expectsJson()) {
                    Auth::login($user, $request->filled('remember'));
                    $request->session()->regenerate();
                    return response()->json([
                        'success' => true,
                        'redirect' => route('home')
                    ]);
                }
                return $this->doLogin($request, $user);
            }
        } catch (\RuntimeException $e) {
            // Optionally log the error
        }

        // Wrong password
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials'
            ], 401);
        }

        throw ValidationException::withMessages([
            'userid' => [trans('auth.failed')],
        ]);
    }

    /**
     * Perform login and redirect.
     */
    protected function doLogin(Request $request, User $user)
    {
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        session([
            'user' => [
                'rssite' => $user->rssite,
                'name'   => $user->name,
                'userid' => $user->userid,
                'profile_pic_url'  => $user->profile_pic_url ? $user->profile_pic_url : 'uploads/user-profile/noprofile.png',
            ]
        ]);

        Session::save();

        // 🔥 manually update sessions table
        $sessionId = Session::getId();

        DB::table('sessions')
        ->where('id', $sessionId)
        ->update([
            'site'  => $user->site,
            // 'userid' => (string) $user->getAttribute('userid'),
        ]);

        // ✅ store checkbox preference in a cookie (30 days)
        if ($request->filled('remember')) {
            Cookie::queue('remember_checked', true, 60 * 24 * 30); // 30 days
        } else {
            Cookie::queue(Cookie::forget('remember_checked'));
        }


        return redirect()->intended(route('home'));
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
