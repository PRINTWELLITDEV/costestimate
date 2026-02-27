<?php

namespace App\Http\Controllers\CostEstimate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Site;

class MainController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $userLevel = $user->level;
        $userSite = $user->site;    

        $site = Site::where(
            'site', '=', $userSite,
            'AND', 'deleted_at', '=', NULL)->first();
        $userSiteDesc = $site ? $site->site_desc : 'Unknown Site';
        return view('ce.ce-layouts.home', compact('sites', 'userLevel', 'userSite', 'userSiteDesc'));
    }
}
