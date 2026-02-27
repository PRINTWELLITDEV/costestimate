<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UpdateSessionUser
{
    public function handle(Authenticated $event): void
    {
        $user = $event->user;
        $sessionId = Session::getId();

        \Log::info('Authenticated Event User', [
            'userid' => $user->userid ?? null,
            'site' => $user->rssite ?? null,
            'sessionId' => $sessionId
        ]);

        if ($user && $sessionId) {
            DB::table('sessions')
                ->where('id', $sessionId)
                ->update([
                    'site' => $user->rssite ?? null,
                    'user_id' => $user->userid ?? null,
                ]);
        }
    }
}
