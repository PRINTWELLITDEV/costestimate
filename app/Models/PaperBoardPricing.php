<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Site;
use App\Models\PType;
use App\Models\Stocks;
use App\Models\Vendors;
use App\Models\U_M;

class PaperBoardPricing extends Model
{
    //
    protected $table = 'PaperBoardPricing';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'Site',
        'Group',
        'PType',
        'Vendor',
        'StockCode',
        'UM',
        'EffectiveDate',
        'Currcode',
        'Price_MT',
        'Price_Sheet',
        'Price_Pound',
        'Price_Bale',
        'CreateDate',
        'CreatedBy',
    ];

    public static function siteList()
    {
        return Site::whereNull('deleted_at')->orderBy('create_date', 'asc')->get();
    }

    public static function pricingList($user)
    {
        $query = "SELECT
                    pbp.Site, pbp.id, pbp.[Group] AS [VendorGroup],
                    pbp.PType, p.PTypeDesc,
                    pbp.Vendor, v.[Name] AS [VendName], 
                    pbp.StockCode, s.StockDesc,
                    pbp.UM,
                    u.UMDesc,
                    pbp.Currcode,
                    pbp.Price_MT,
                    pbp.Price_Sheet,
                    pbp.Price_Pound,
                    pbp.Price_Bale,
                    pbp.EffectiveDate
                FROM
                    PaperBoardPricing pbp
                    INNER JOIN vendors v ON v.Vendnum = pbp.Vendor AND v.Site = pbp.Site
                    INNER JOIN ptype p ON p.PType = pbp.PType AND p.Site = pbp.Site
                    INNER JOIN stocks s ON s.StockCode = pbp.StockCode AND s.Site = pbp.Site
                    LEFT JOIN u_m u ON u.UM = pbp.UM";
        if ($user->level != 1) {
            $query .= " WHERE pbp.Site = '" . $user->site . "'";
        }
        $query .= " ORDER BY pbp.EffectiveDate DESC";
        return \DB::select($query);
    }

    public static function getPTypes()
    {
        return PType::orderBy('Site', 'asc')
            ->orderBy('PType', 'asc')
            ->get(['Site', 'PType', 'PTypeDesc']);
    }

    public static function getVendors()
    {
        return Vendors::orderBy('Site', 'asc')
            ->orderBy('Vendnum', 'asc')
            ->get(['Site', 'Group', 'Vendnum', 'Name', 'Currcode']);
    }

    public static function getStocks()
    {
        return Stocks::orderBy('Site', 'asc')
            ->orderBy('StockCode', 'asc')
            ->get(['Site', 'PType', 'StockCode', 'StockDesc']);
    }

    public static function getStockCode($site, $ptype)
    {
        return Stocks::where('Site', $site)
            ->where('PType', $ptype)
            ->orderBy('StockCode', 'asc')
            ->get(['StockCode', 'StockDesc']);
    }

    public static function getUM()
    {
        $UM = U_M::select('UM', 'UMDesc')
            ->orderBy('UM', 'asc')
            ->get();
        return $UM;
    }

    public static function getVendorCurrency($site, $vendnum)
    {
        $vendor = Vendors::where('Vendnum', $vendnum)
            ->where('Site', $site)
            ->first();

        if ($vendor && $vendor->Currcode) {
            return ['success' => true, 'currcode' => $vendor->Currcode];
        } else {
            return ['success' => false, 'message' => 'Vendor not found or no currency code available'];
        }
    }

    public static function storePricing($data, $username)
    {
        self::create([
            'Site' => $data['Site'],
            'Group' => $data['Group'],
            'PType' => $data['PType'],
            'Vendor' => $data['Vendor'],
            'StockCode' => $data['StockCode'],
            'UM' => $data['UM'],
            'Currcode' => $data['Currcode'],
            'Price_MT' => $data['Price_MT'],
            'Price_Sheet' => $data['Price_Sheet'],
            'Price_Pound' => $data['Price_Pound'],
            'Price_Bale' => $data['Price_Bale'],
            'EffectiveDate' => $data['EffectiveDate'],
            'CreateDate' => now(),
            'CreatedBy' => $username,
        ]);
        return ['message' => 'Paper Board Pricing added successfully!'];
    }

    public static function updatePricing($data)
    {
        $pricing = self::where('Site', $data['Site'])
            ->where('id', $data['id'])
            ->first();

        if (!$pricing) {
            return ['error' => true, 'message' => 'Pricing not found.'];
        }

        $pricing->update([
            'Group' => $data['Group'],
            'PType' => $data['PType'],
            'Vendor' => $data['Vendor'],
            'StockCode' => $data['StockCode'],
            'UM' => $data['UM'],
            'Currcode' => $data['Currcode'],
            'Price_MT' => $data['Price_MT'],
            'Price_Sheet' => $data['Price_Sheet'],
            'Price_Pound' => $data['Price_Pound'],
            'Price_Bale' => $data['Price_Bale'],
            'EffectiveDate' => $data['EffectiveDate'],
        ]);
        return ['error' => false, 'message' => $data['Vendor'].' '. $data['PType'].' '. $data['StockCode'] .' updated successfully!'];
    }

    public static function deletePricing($site, $id)
    {
        $pricing = self::where('Site', $site)
            ->where('id', $id)
            ->first();

        if (!$pricing) {
            return ['error' => true, 'message' => 'Pricing ' . $site . ' ' . $id . ' not found.'];
        }

        $pricing->delete();
        return ['error' => false, 'message' => 'Pricing deleted successfully.'];
    }
}
