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
        $query = "SELECT 
                    s.Site,
                    ProdGroup,
                    s.PType, p.PTypeDesc,
                    StockCode, StockDesc, GSM, Caliper, PPR, Cbnum, Width, Length
                FROM 
                    stocks s
                    INNER JOIN ptype p ON p.PType = s.PType AND p.Site = s.Site
                ORDER BY
                    s.Site ASC, s.StockCode ASC";

        return DB::select($query);
        // return Stocks::orderBy('Site', 'asc')
        //     ->orderBy('StockCode', 'asc')
        //     ->get(['Site', 'PType', 'StockCode', 'StockDesc']);
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

    public static function getPricingForCalculator($site, $stockcode, $ptype, $gsm, $inputs = [])
    {
        $query = "SELECT 
                    pbp.Site,
                    pbp.[Group],
                    pbp.PType,
                    pbp.Vendor,
                    pbp.StockCode,
                    s.StockDesc,
                    s.GSM,
                    pbp.UM,
                    pbp.Currcode,
                    pbp.Price_MT,
                    pbp.Price_Sheet,
                    pbp.Price_Pound,
                    pbp.Price_Bale

                FROM PaperBoardPricing pbp
                    INNER JOIN stocks s ON s.StockCode = pbp.StockCode AND s.site = pbp.Site

                WHERE
                    pbp.StockCode = ?
                    AND pbp.PType = ?
                    AND s.GSM = ?
                    AND pbp.Site = ?";
        
        $result = DB::select($query, [$stockcode, $ptype, $gsm, $site]);

        $calculatedResults = [];
        foreach ($result as $pricing) {
            $row = array_merge((array) $pricing, $inputs);
            $calculatedResults[] = array_merge($row, self::calculatePrice($row));
        }
        return $calculatedResults;
    }

    public static function calculatePrice($data)
    {
        $CFCostInPesos = $data['Price_MT'] * $data['FXRate'];
        $DutyRate = $data['DutyRate']/100 ?? 0.01; //default to 1% if not provided

        // User-controlled (checkbox) to exclude duty from calculation. 
        // This is for special cases where duty is not applicable or should be ignored for comparison purposes.
        // $excludeDuty = filter_var($data['ExcludeDuty'] ?? false, FILTER_VALIDATE_BOOLEAN);
        // if ($excludeDuty) {
        //     $DutyRate = 0;
        // } else {
        //     $DutyRate = $data['DutyRate']/100 ?? 0.01; //default to 1% if not provided
        // }

        $DutyAmount = $CFCostInPesos * $DutyRate;
        $OtherCharges = $CFCostInPesos * (($data['OtherChargesRate'] ?? 0) / 100);
        $LandedCost = $CFCostInPesos + $DutyAmount + $OtherCharges;

        // SH: user-controlled (checkbox). RL: always with sheeting cost by default.
        $applySheeting = false;
        if (($data['UM'] ?? '') === 'RL') {
            $applySheeting = true;
        }

        $SheetingCost = $applySheeting ? (float)($data['SheetingCost'] ?? 0) : 0.0;

        $SheetedCost = $LandedCost + $SheetingCost;

        $SheetSizeMM_L = $data['SheetMM_L'];
        $SheetSizeMM_W = $data['SheetMM_W'];
        $SheetSizeIn_L = $SheetSizeMM_L / 25.4;
        $SheetSizeIn_W = $SheetSizeMM_W / 25.4;

        $AreaInSqIn = $SheetSizeIn_L * $SheetSizeIn_W;
        $AreaInSqMM = $AreaInSqIn / 1550;
        $GramsPerSheet = $AreaInSqMM * $data['GSM'];
        $SheetsPerMT = $GramsPerSheet > 0 ? 1000000 / $GramsPerSheet : 0;
        $CostPerSheet = $SheetsPerMT > 0 ? $SheetedCost / $SheetsPerMT : 0;

        return [
            'CFCostInPesos' => round($CFCostInPesos, 2),
            'DutyRate' => round($DutyRate * 100, 2),
            'DutyAmount' => round($DutyAmount, 2),
            'OtherCharges' => round($OtherCharges, 2),
            'LandedCost' => round($LandedCost, 2),
            'SheetingCost' => round($SheetingCost, 2),
            'SheetedCost' => round($SheetedCost, 2),
            'AreaInSqIn' => round($AreaInSqIn, 2),
            'AreaInSqMM' => round($AreaInSqMM, 8),
            'GramsPerSheet' => round($GramsPerSheet, 8),
            'SheetsPerMT' => round($SheetsPerMT, 0),
            'CostPerSheet' => round($CostPerSheet, 4),
            'ApplySheeting' => $applySheeting,
            // 'ExcludeDuty' => $excludeDuty,
        ];
    }
}
