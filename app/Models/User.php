<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Site;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Use SQL Server connection
    protected $connection = 'sqlsrv';

    // Table name (no schema prefix needed for Laravel migrations)
    protected $table = 'users';

    // Composite primary key
    // protected $primaryKey = ['site', 'userid'];
    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'string';

    // No timestamps
    public $timestamps = false;

    // Fillable columns (match migration)
    protected $fillable = [
        'site',
        'userid',
        'name',
        'password',
        'email',
        'email_verified_at',
        'department',
        'section',
        'position',
        'level',
        'status',
        'create_date',
        'updated_date',
        'updated_by',
        'updated_by_sql',
        'gender',
        'profile_pic_url',
        'remember_token'
    ];

    // Accessor for profile image
    public function getProfilePicUrlAttribute()
    {
        if (!empty($this->attributes['profile_pic_url'])) {
            return asset($this->attributes['profile_pic_url']);
        }
        return asset('uploads/user-profile/noprofile.png');
    }

    // Composite PK save logic
    protected function setKeysForSaveQuery($query)
    {
        $keyName = $this->getKeyName();
        if (is_array($keyName)) {
            foreach ($keyName as $keyField) {
                $query->where($keyField, '=', $this->getAttribute($keyField));
            }
            return $query;
        }
        return $query->where($keyName, '=', $this->getAttribute($keyName));
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    // Auth identifier for Laravel
    public function getAuthIdentifierName()
    {
        return 'userid';
    }

    public function getAuthIdentifier()
    {
        return $this->userid;
    }

    // Optional: update 'updated_by' on update
    protected static function booted()
    {
        static::updating(function ($user) {
            if (Auth::check()) {
                $user->updated_by = Auth::user()->userid;
            }
        });
    }

    public static function activeSites()
    {
        return Site::whereNull('deleted_at')->orderBy('create_date', 'asc')->get();
    }

    public static function getLevels()
    {
        return DB::table('level')->orderBy('level', 'asc')->get();
    }

    public static function totalUsers()
    {
        return self::whereNull('deleted_at')->where('userid', '<>', 'sa')->count();
    }

    public static function totalStatusActive()
    {
        return self::where('status', 1)->whereNull('deleted_at')->where('userid', '<>', 'sa')->count();
    }

    public static function totalLevel1()
    {
        return self::where('level', '<=', 2)->whereNull('deleted_at')->where('userid', '<>', 'sa')->count();
    }

    public static function userList($currentUserId)
    {
        $query = "SELECT u.userid, u.name, u.email,
            u.department, u.section, u.position, 
            u.level, l.role, u.status, u.gender, u.profile_pic_url,
            u.site, u.create_date, s.site_desc 
            FROM users u
            LEFT JOIN site s ON s.site = u.site
            LEFT JOIN level l ON l.level = u.level
            WHERE u.deleted_at IS NULL AND u.deleted_by IS NULL";
        if ($currentUserId != 'sa') {
            $query .= " AND u.userid <> 'sa' ";
        }
        $query .= " ORDER BY u.name";
        return DB::select($query);
    }

    public static function createUser($data, $profilePic = null)
    {
        if (self::where('userid', $data['userid'])->exists()) {
            return ['error' => true, 'message' => 'User ID already in use.'];
        }
        if (self::where('email', $data['email'])->exists()) {
            return ['error' => true, 'message' => 'Email already in use.'];
        }

        if ($profilePic) {
            $filename = uniqid() . '_' . $data['userid'] . '.png';
            $profilePic->move(public_path('uploads/user-profile'), $filename);
            $data['profile_pic_url'] = 'uploads/user-profile/' . $filename;
        } else {
            $data['profile_pic_url'] = null;
        }
        $data['name'] = ucfirst($data['name']);
        $data['password'] = bcrypt($data['password']);
        $data['level'] = $data['level'] ?? 3;
        $data['status'] = 1;
        $data['create_date'] = now();

        self::create($data);
        return ['error' => false, 'message' => 'User created successfully!'];
    }

    public static function updateUser($data, $profilePic = null, $password = null)
    {
        if (!self::where('userid', $data['userid'])->exists()) {
            return ['error' => true, 'message' => 'User not found.'];
        }
        if (self::where('email', $data['email'])->where('userid', '<>', $data['userid'])->exists()) {
            return ['error' => true, 'message' => 'Email already in use by another user.'];
        }

        $userid = $data['userid'];
        $updateData = [
            'site' => $data['site'],
            'userid' => $data['userid'],
            'name' => ucwords(strtolower($data['name'])),
            'email' => $data['email'],
            'department' => $data['department'] ?? null,
            'section' => $data['section'] ?? null,
            'position' => $data['position'] ?? null,
            'level' => $data['level'] ?? null,
            'status' => $data['status'] ?? null,
            'gender' => $data['gender'] ?? null,
            'updated_date' => now(),
            'updated_by' => auth()->user()->userid ?? 'system'
        ];

        if ($profilePic) {
            $filename = uniqid() . '_' . $data['userid'] . '.png';
            $profilePic->move(public_path('uploads/user-profile'), $filename);
            $updateData['profile_pic_url'] = 'uploads/user-profile/' . $filename;
        }

        if ($password) {
            $updateData['password'] = bcrypt($password);
        }

        self::where('userid', '=', $userid)->where('site', '=', $data['site'])->update($updateData);
        return ['error' => false, 'message' => $userid . ' updated successfully!'];
    }

    public static function deleteUser($userid, $deletedBy)
    {
        $user = self::where('userid', $userid)->first();
        if (!$user) {
            return ['message' => 'User not found.', 'status' => 404];
        }
        if ($user->userid == 'sa') {
            return ['message' => 'This action cannot be done.', 'status' => 404];
        }
        self::where('userid', $userid)->update([
            'email' => $user->email . '_deleted_' . strtoupper(now()->format('dMY_H:i:s')),
            'status' => 0,
            'deleted_at' => now(),
            'deleted_by' => $deletedBy
        ]);
        return ['message' => 'User deleted successfully.', 'status' => 200];
    }

    public static function getUserProfile($userid)
    {
        return self::where('userid', $userid)->firstOrFail();
    }

    public static function getSiteDetails($siteCode)
    {
        $site = \App\Models\Site::where('site', $siteCode)->first();
        return [
            'siteDesc' => $site ? $site->site_desc : $siteCode,
            'siteAddress' => $site ? $site->address : 'N/A'
        ];
    }

    public static function getLevelRole($level)
    {
        return \DB::table('level')->where('level', $level)->value('role');
    }

    public static function isOnline($userid)
    {
        return \DB::table('sessions')
            ->where('user_id', $userid)
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
            ->exists();
    }

    public static function updateProfile($userid, $data, $profilePic = null)
    {
        $updateData = [
            'name' => $data['name'] ?? null,
            'gender' => $data['gender'] ?? null,
            'department' => $data['department'] ?? null,
            'section' => $data['section'] ?? null,
            'position' => $data['position'] ?? null,
            'updated_by' => auth()->user()->userid ?? 'sa'
        ];

        if ($profilePic) {
            $filename = uniqid() . '_' . $userid . '.' . $profilePic->getClientOriginalExtension();
            $profilePic->move(public_path('uploads/user-profile'), $filename);
            $updateData['profile_pic_url'] = 'uploads/user-profile/' . $filename;
        }

        self::where('userid', $userid)->update(array_filter($updateData, function($v) { return !is_null($v); }));
        return true;
    }

    public static function changeUserPassword($userid, $currentPassword, $newPassword)
    {
        $user = self::where('userid', $userid)->firstOrFail();

        if (!\Hash::check($currentPassword, $user->password)) {
            return ['error' => true, 'message' => 'Current password is incorrect.'];
        }

        $user->password = bcrypt($newPassword);
        $user->save();

        return ['error' => false, 'message' => 'Password changed successfully.'];
    }
}
