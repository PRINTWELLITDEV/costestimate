<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Site;
use App\Models\PType;
use App\Models\U_M;

class Stocks extends Model
{
    //
    protected $table = 'stocks';
    // protected $primaryKey = ['Site', 'ItemCode'];
    protected $primaryKey = 'StockCode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'Site',
        'ProdGroup',
        'PType',
        'StockCode',
        'StockDesc',
        'GSM',
        'Caliper',
        'PPR',
        'Cbnum',
        'Width',
        'Length',
    ];

    public static function siteList()
    {
        return Site::whereNull('deleted_at')->orderBy('create_date', 'asc')->get();
    }

    public static function stockList($user)
    {
        $query = "SELECT i.Site, s.site_desc, i.ProdGroup, i.PType, i.StockCode, i.StockDesc, i.GSM, i.Caliper, i.PPR, i.Cbnum, i.Width, i.Length
            FROM stocks i
            INNER JOIN site s ON s.site = i.site
            INNER JOIN ptype p ON p.ptype = i.ptype";
        if ($user->level != 1) {
            $query .= " WHERE i.Site = '" . $user->site . "'";
        }
        $query .= " ORDER BY i.StockCode";
        return DB::select($query);
    }

    public static function getPtypes($site = null)
    {
        $query = PType::query();
        if ($site) {
            $query->where('site', $site);
        }
        return $query->orderBy('PType', 'asc')->get();
    }

    public static function unitsList()
    {
        return U_M::orderBy('UM', 'asc')->get();
    }

    public static function addStock($data, $userid)
    {
        $stock = new self();
        $stock->Site = $data['Site'];
        $stock->PType = $data['ptype'];
        $stock->ProdGroup = $data['product_group'];
        $stock->GSM = $data['gsm'] ?? 0;
        $stock->Caliper = $data['caliper'] ?? 0;
        $stock->PPR = $data['pounds_ream'] ?? 0;
        $stock->Cbnum = $data['chipboard_no'] ?? 0;
        $stock->Width = $data['width'] ?? 0;
        $stock->Length = $data['length'] ?? 0;
        $stock->StockCode = $data['stock_code'];
        $stock->StockDesc = $data['stock_description'];
        $stock->CreateDate = now();
        $stock->CreatedBy = $userid;
        $stock->save();

        return [
            'success' => true,
            'message' => "Stock " . $stock->StockCode . " - " . $stock->StockDesc . " has been added successfully."
        ];
    }

    public static function getStockByCode($stockCode)
    {
        return self::where('StockCode', $stockCode)->firstOrFail();
    }

    public static function updateStock($data, $userid)
    {
        $isUsedInPricing = DB::table('PaperBoardPricing')
            ->where('PType', $data['ptype'])
            ->where('StockCode', $data['stockcode'])
            ->where('Site', $data['site'])
            ->exists();
        if ($isUsedInPricing) {
            return [
                'success' => false,
                'message' => "Cannot update stock because it is being used in other records."
            ];
        }

        $stock = self::where('StockCode', $data['stockcode'])
            ->where('Site', $data['site'])
            ->firstOrFail();
        $stock->Site = $data['site'];
        $stock->PType = $data['ptype'];
        $stock->ProdGroup = strtoupper($data['product_group']);
        $stock->GSM = $data['gsm'] ?? 0;
        $stock->Caliper = $data['caliper'] ?? 0;
        $stock->PPR = $data['pounds_ream'] ?? 0;
        $stock->Cbnum = $data['chipboard_no'] ?? 0;
        $stock->Width = $data['width'] ?? 0;
        $stock->Length = $data['length'] ?? 0;
        $stock->StockCode = $data['stock_code'];
        $stock->StockDesc = $data['stock_description'];
        // $stock->UpdatedDate = now();
        // $stock->UpdatedBy = $userid;
        $stock->save();

        return [
            'success' => true,
            'message' => "Stock " . $data['stock_code'] . " has been updated successfully."
        ];
    }

    public static function deleteStock($site, $stockCode)
    {
        $deleted = self::where('Site', $site)
            ->where('StockCode', $stockCode)
            ->delete();

        if ($deleted) {
            return [
                'success' => true,
                'message' => 'Stock: ' . $stockCode . ' deleted successfully!'
            ];
        }
        return [
            'success' => false,
            'message' => 'Stock: ' . $stockCode . ' not found.'
        ];
    }
}
