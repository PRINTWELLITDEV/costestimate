<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Query;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Site;
use App\Models\User;

class RegisterController extends Controller
{

    // show Register Form

    public function showRegistrationForm()
    {
        $sites = Site::orderBy('create_date', 'asc')->get();
        return view('auth.register', compact('sites'));
    }
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/ce';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'site' => ['required', 'exists:sites,site'],
            'userid' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    // /**
    //  * Create a new user instance after a valid registration.
    //  *
    //  * @param  array  $data
    //  * @return \App\Models\User
    //  */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'site' => $data['site'],
    //         'userid' => $data['userid'],
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }

    public function register(Request $request)
    {
        $data = $request->validate([
            'site' => ['required', 'string', 'max:8'],
            'userid' => ['required', 'string', 'max:8'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Add other fields as needed
        ]);
        $level = 3; // default level for new users

        \DB::table('users')->insert([
            'site' => $data['site'],
            'userid' => $data['userid'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'email' => $data['email'],
            // 'department' => NULL,
            // 'section' => NULL,
            // 'position' => NULL,
            'level' => $level,
            'gender' => $request->input('gender'),
            'profile_pic_url' => NULL,
            'create_date' => now(),
        ]);

        // DB::statement('EXEC sp_user_register ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
        //     [
        //         $data['site'],
        //         $data['userid'],
        //         $data['name'],
        //         Hash::make($data['password']),
        //         $data['email'],
        //         $request->input('department'),
        //         $request->input('section'),
        //         $request->input('position'),
        //         $level,
        //         $request->input('gender'),
        //         $request->input('profile_pic_url')
        //     ]
        // );

        // Fetch the newly created user (adjust model/class as needed)
        $user = User::where('userid', $data['userid'])->first();

        if ($user) {
            \Auth::login($user);
            return redirect($this->redirectTo)->with('success', 'Registration successful!');
        }

        // Fallback if user not found
        return redirect()->route('login')->with('error', 'Registration failed. Please login.');
    }
}
