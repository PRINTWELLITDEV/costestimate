<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Site;

class PType extends Model
{
    //
    protected $table = 'ptype';
    // protected $primaryKey = ['Site', 'PType'];
    protected $primaryKey = 'PType';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'Site',
        'PType',
        'PTypeDesc',
        'DescLabel',
    ];

    public static function siteList()
    {
        return Site::whereNull('deleted_at')->orderBy('create_date', 'asc')->get();
    }

    public static function ptypeList($user)
    {
        $query = "SELECT Site, PType, PTypeDesc, DescLabel FROM ptype";
        if ($user->level != 1) {
            $query .= " WHERE Site = '" . $user->site . "'";
        }
        $query .= " ORDER BY PType";
        return DB::select($query);
    }

    public static function existsPType($site, $ptype)
    {
        return self::where('Site', $site)->where('PType', $ptype)->exists();
    }

    public static function createPType($data, $userid)
    {
        if (self::existsPType($data['Site'], $data['PType'])) {
            return ['error' => true, 'message' => 'Paper Type already exists.'];
        }
        self::create([
            'Site' => $data['Site'],
            'PType' => $data['PType'],
            'PTypeDesc' => $data['PTypeDesc'],
            'DescLabel' => $data['DescLabel'],
            'CreateDate' => now(),
            'CreatedBy' => $userid
        ]);
        return ['error' => false, 'message' => $data['PType'] . ' - ' . $data['PTypeDesc'] . " added successfully!"];
    }

    public static function isUsedInItems($site, $ptype)
    {
        return DB::table('items')->where('PType', $ptype)->where('Site', $site)->exists();
    }

    public static function isUsedInPricing($site, $ptype)
    {
        return DB::table('PaperBoardPricing')->where('PType', $ptype)->where('Site', $site)->exists();
    }

    public static function updatePType($data)
    {
        $ptype = self::where('PType', $data['PType'])->where('Site', $data['Site'])->first();
        if (!$ptype) {
            return ['error' => true, 'message' => 'Paper Type not found.'];
        }
        if (self::isUsedInItems($data['Site'], $data['PType']) || self::isUsedInPricing($data['Site'], $data['PType'])) {
            return ['error' => true, 'message' => 'Cannot update Paper Type because it is being used in other records.'];
        }
        self::where('PType', $data['PType'])->where('Site', $data['Site'])->update([
            'Site' => $data['Site'],
            'PType' => $data['updatePType'],
            'PTypeDesc' => $data['PTypeDesc'],
            'DescLabel' => $data['DescLabel'],
            // 'updated_date' => now(),
            // 'updated_by' => auth()->user()->userid
        ]);
        return ['error' => false, 'message' => $data['updatePType'] . " updated successfully!"];
    }

    public static function deletePType($site, $ptype)
    {
        if (self::isUsedInItems($site, $ptype) || self::isUsedInPricing($site, $ptype)) {
            return ['error' => true, 'message' => 'Cannot delete Paper Type because it is being used in other records.'];
        }
        $deleted = self::where('Site', $site)->where('PType', $ptype)->delete();
        if ($deleted) {
            return ['error' => false, 'message' => 'Paper type: ' . $ptype . ' deleted successfully!'];
        }
        return ['error' => true, 'message' => 'Paper type not found.'];
    }
}
