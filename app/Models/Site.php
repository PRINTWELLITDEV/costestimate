<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'site';
    protected $primaryKey = 'site';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'site',
        'site_desc',
        'address',
        'logo_pic_url',
        'site_link',
    ];

    public static function siteList()
    {
        return self::whereNull('deleted_at')
            ->whereNull('deleted_by')
            ->orderBy('create_date', 'asc')
            ->get();
    }

    public static function createSite($data, $logoPic = null)
    {
        if (self::where('site', $data['site'])->exists()) {
            return ['error' => true, 'message' => 'Site code already exists.'];
        }

        if ($logoPic) {
            $filename = uniqid() . '_' . $data['site'] . '.png';
            $logoPic->move(public_path('uploads/sites-img'), $filename);
            $data['logo_pic_url'] = 'uploads/sites-img/' . $filename;
        } else {
            $data['logo_pic_url'] = null;
        }

        $data['create_date'] = now();
        $data['create_by'] = auth()->user()->userid;

        self::create($data);
        return ['error' => false, 'message' => 'Site added successfully!'];
    }

    public static function updateSite($data, $logoPic = null)
    {
        $site = self::where('site', $data['site'])->first();
        if (!$site) {
            return ['error' => true, 'message' => 'Site not found.'];
        }

        $updateData = [
            'site_desc' => $data['site_desc'],
            'address' => $data['address'],
            'site_link' => $data['site_link'],
            'updated_date' => now(),
            'updated_by' => auth()->user()->userid
        ];

        if ($logoPic) {
            $filename = uniqid() . '_' . $data['site'] . '.png';
            $logoPic->move(public_path('uploads/sites-img'), $filename);
            $updateData['logo_pic_url'] = 'uploads/sites-img/' . $filename;
        } else {
            $updateData['logo_pic_url'] = $site->logo_pic_url;
        }

        self::where('site', $data['site'])->update($updateData);
        return ['error' => false, 'message' => 'Site updated successfully!'];
    }

    public static function deleteSite($sitecode, $deletedBy)
    {
        $site = self::where('site', $sitecode)->first();
        if (!$site) {
            return ['message' => 'Site not found.', 'status' => 404];
        }

        self::where('site', $sitecode)->update([
            'deleted_at' => now(),
            'deleted_by' => $deletedBy
        ]);
        return ['message' => $sitecode . ' deleted successfully.', 'status' => 200];
    }
}
