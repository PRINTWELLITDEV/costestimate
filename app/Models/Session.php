<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string'; // session id is nvarchar(255)

    public $timestamps = false;

    protected $dates = ['last_activity'];

    // 🔗 Relationship: session belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userid')
                    ->whereColumn('sessions.site', 'users.site');
    }

}
