<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Site;


class Vendors extends Model
{
    //
    protected $table = 'vendors';
    // protected $primaryKey = ['Site', 'Vendnum'];
    protected $primaryKey = 'Vendnum';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'Site',
        'Group',
        'Vendnum',
        'Name',
        'Currcode',
    ];

    public static function siteList()
    {
        return Site::whereNull('deleted_at')->orderBy('create_date', 'asc')->get();
    }

    public static function currencyList()
    {
        return DB::table('currency')->get();
    }

    public static function vendorList($user)
    {
        $query = "SELECT v.Site, s.site_desc, v.[Group], v.Vendnum, v.Name, v.Currcode
            FROM vendors v
            LEFT JOIN site s ON s.site = v.site";
        if ($user->level != 1) {
            $query .= " WHERE v.site = '" . $user->site . "'";
        }
        $query .= " ORDER BY v.Vendnum";
        return DB::select($query);
    }

    public static function vendorExists($site, $vendnum)
    {
        return self::where('Site', $site)->where('Vendnum', $vendnum)->exists();
    }

    public static function createVendor($data, $userid)
    {
        if (self::vendorExists($data['Site'], $data['Vendnum'])) {
            return ['error' => true, 'message' => 'Vendor already exists.'];
        }
        self::create([
            'Site' => $data['Site'],
            'Group' => $data['Group'],
            'Vendnum' => $data['Vendnum'],
            'Name' => $data['Name'],
            'Currcode' => $data['Currcode'],
            'CreateDate' => now(),
            'CreatedBy' => $userid
        ]);
        return ['error' => false, 'message' => $data['Vendnum'] . ' - ' . $data['Name'] . " added successfully!"];
    }

    public static function isUsedInPricing($site, $vendnum)
    {
        return DB::table('PaperBoardPricing')
            ->where('Vendor', $vendnum)
            ->where('Site', $site)
            ->exists();
    }

    public static function updateVendor($data)
    {
        if (self::isUsedInPricing($data['Site'], $data['Vendnum'])) {
            return ['error' => true, 'message' => 'Cannot update Vendor because it is being used in other records.'];
        }
        $vendor = self::where('Vendnum', $data['Vendnum'])->where('Site', $data['Site'])->first();
        if (!$vendor) {
            return ['error' => true, 'message' => 'Vendor not found.'];
        }
        self::where('Vendnum', $data['Vendnum'])->where('Site', $data['Site'])->update([
            'Site' => $data['Site'],
            'Group' => $data['Group'],
            'Vendnum' => $data['Vendnum'],
            'Name' => $data['Name'],
            'Currcode' => $data['Currcode'],
        ]);
        return ['error' => false, 'message' => $data['Vendnum'] . " updated successfully!"];
    }

    public static function deleteVendor($site, $vendnum)
    {
        if (self::isUsedInPricing($site, $vendnum)) {
            return ['error' => true, 'message' => 'Cannot delete Vendor because it is being used in other records.'];
        }
        $deleted = self::where('Site', $site)->where('Vendnum', $vendnum)->delete();
        if ($deleted) {
            return ['error' => false, 'message' => 'Vendor: ' . $vendnum . ' deleted successfully!'];
        }
        return ['error' => true, 'message' => 'Vendor not found.'];
    }
}
