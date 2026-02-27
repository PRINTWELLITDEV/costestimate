<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Site;
use App\Models\PType;
use App\Models\U_M;

class Items extends Model
{
    //
    protected $table = 'items';
    // protected $primaryKey = ['Site', 'ItemCode'];
    protected $primaryKey = 'ItemCode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'Site',
        'ProdGroup',
        'PType',
        'ItemCode',
        'ItemDesc',
        'UM',
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

    public static function itemList($user)
    {
        $query = "SELECT i.Site, s.site_desc, i.ProdGroup, i.PType, i.ItemCode, i.ItemDesc, i.UM, i.GSM, i.Caliper, i.PPR, i.Cbnum, i.Width, i.Length
            FROM items i
            INNER JOIN site s ON s.site = i.site
            INNER JOIN ptype p ON p.ptype = i.ptype";
        if ($user->level != 1) {
            $query .= " WHERE i.Site = '" . $user->site . "'";
        }
        $query .= " ORDER BY i.ItemCode";
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

    public static function addItem($data, $userid)
    {
        $item = new self();
        $item->Site = $data['Site'];
        $item->PType = $data['ptype'];
        $item->ProdGroup = $data['product_group'];
        $item->GSM = $data['gsm'] ?? 0;
        $item->Caliper = $data['caliper'] ?? 0;
        $item->PPR = $data['pounds_ream'] ?? 0;
        $item->Cbnum = $data['chipboard_no'] ?? 0;
        $item->Width = $data['width'] ?? 0;
        $item->Length = $data['length'] ?? 0;
        $item->UM = $data['unit'];
        $item->ItemCode = $data['item_code'];
        $item->ItemDesc = $data['item_description'];
        $item->CreateDate = now();
        $item->CreatedBy = $userid;
        $item->save();

        return [
            'success' => true,
            'message' => "Item " . $item->ItemCode . " - " . $item->ItemDesc . " has been added successfully."
        ];
    }

    public static function getItemByCode($itemCode)
    {
        return self::where('ItemCode', $itemCode)->firstOrFail();
    }

    public static function updateItem($data, $userid)
    {
        $isUsedInPricing = DB::table('PaperBoardPricing')
            ->where('PType', $data['ptype'])
            ->where('ItemCode', $data['itemcode'])
            ->where('Site', $data['site'])
            ->exists();
        if ($isUsedInPricing) {
            return [
                'success' => false,
                'message' => "Cannot update item because it is being used in other records."
            ];
        }

        $item = self::where('ItemCode', $data['itemcode'])
            ->where('Site', $data['site'])
            ->firstOrFail();
        $item->Site = $data['site'];
        $item->PType = $data['ptype'];
        $item->ProdGroup = strtoupper($data['product_group']);
        $item->GSM = $data['gsm'] ?? 0;
        $item->Caliper = $data['caliper'] ?? 0;
        $item->PPR = $data['pounds_ream'] ?? 0;
        $item->Cbnum = $data['chipboard_no'] ?? 0;
        $item->Width = $data['width'] ?? 0;
        $item->Length = $data['length'] ?? 0;
        $item->UM = $data['unit'];
        $item->ItemCode = $data['item_code'];
        $item->ItemDesc = $data['item_description'];
        // $item->UpdatedDate = now();
        // $item->UpdatedBy = $userid;
        $item->save();

        return [
            'success' => true,
            'message' => "Item " . $data['item_code'] . " has been updated successfully."
        ];
    }

    public static function deleteItem($site, $itemCode)
    {
        $deleted = self::where('Site', $site)
            ->where('ItemCode', $itemCode)
            ->delete();

        if ($deleted) {
            return [
                'success' => true,
                'message' => 'Item: ' . $itemCode . ' deleted successfully!'
            ];
        }
        return [
            'success' => false,
            'message' => 'Item: ' . $itemCode . ' not found.'
        ];
    }
}
