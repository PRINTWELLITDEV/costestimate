<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $createdate = now();
        $createdby = 'sa';
        $data = [
            ['site' => 'PI-SP', 'PType' => 'BP', 'PTypeDesc' => 'BookPaper', 'DescLabel' => 'BookPaper', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CAS', 'PTypeDesc' => 'Coated Adhesives-Satin', 'DescLabel' => 'CA Satin', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CAH', 'PTypeDesc' => 'Coated Adhesives-Hi-gloss', 'DescLabel' => 'CA Hi-gloss', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CB', 'PTypeDesc' => 'Carrier Board', 'DescLabel' => 'CB', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CCG', 'PTypeDesc' => 'CC Greyblack', 'DescLabel' => 'CC Greyblack', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CC', 'PTypeDesc' => 'Clay Coated', 'DescLabel' => 'CC', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CH', 'PTypeDesc' => 'Chipboard', 'DescLabel' => 'Chipboard', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CM', 'PTypeDesc' => 'Corrugating Medium', 'DescLabel' => 'CMX', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'C1S', 'PTypeDesc' => 'C1S', 'DescLabel' => 'C1S', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'C2S', 'PTypeDesc' => 'C2S', 'DescLabel' => 'C2S', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'C2SM', 'PTypeDesc' => 'C2S/Matt', 'DescLabel' => 'C2S/Matt', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'CS', 'PTypeDesc' => 'Cupstock', 'DescLabel' => 'Cupstock', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'FB', 'PTypeDesc' => 'FBB/Sandwich', 'DescLabel' => 'FBB', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'FC', 'PTypeDesc' => 'Foldcote', 'DescLabel' => 'Foldcote', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'FI', 'PTypeDesc' => 'Special Paper', 'DescLabel' => 'Special Paper', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'GC', 'PTypeDesc' => 'Glaze Coat/Mirror Coat', 'DescLabel' => 'Glaze Coat', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'GS', 'PTypeDesc' => 'Glassine', 'DescLabel' => 'Glassine', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'KB', 'PTypeDesc' => 'Kraft Board', 'DescLabel' => 'Kraft Board', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'KL', 'PTypeDesc' => 'Kraft Liner', 'DescLabel' => 'Kraft Liner', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'LW', 'PTypeDesc' => 'Light Weight', 'DescLabel' => 'Light Weight', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'MB', 'PTypeDesc' => 'Mettalized Board', 'DescLabel' => 'Mettalized Board', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'MC', 'PTypeDesc' => 'Matte Coated', 'DescLabel' => 'Matte Coated', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'MH', 'PTypeDesc' => 'Holo Film Pillars/Rainbow', 'DescLabel' => 'Holo Film', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'MT', 'PTypeDesc' => 'Metallized', 'DescLabel' => 'Metallized', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'NP', 'PTypeDesc' => 'Newsprint', 'DescLabel' => 'Newsprint', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'P1S', 'PTypeDesc' => 'Polycoated Board One Side', 'DescLabel' => 'Polycoated 1S', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'P2S', 'PTypeDesc' => 'Polycoated Board Two Sides', 'DescLabel' => 'Polycoated 2S', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'RP', 'PTypeDesc' => 'Rock Paper', 'DescLabel' => 'Rock Paper', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'SB', 'PTypeDesc' => 'SBS', 'DescLabel' => 'SBS', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'SC', 'PTypeDesc' => 'Super Calendered', 'DescLabel' => 'Super Calendered', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'SF', 'PTypeDesc' => 'Single Face', 'DescLabel' => 'Single Face', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'TB', 'PTypeDesc' => 'Tag Board', 'DescLabel' => 'Tag Board', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'TL', 'PTypeDesc' => 'Test Liner', 'DescLabel' => 'Test Liner', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'UC', 'PTypeDesc' => 'Uncoated Cupstock', 'DescLabel' => 'Uncoated Cupstock', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
            ['site' => 'PI-SP', 'PType' => 'WL', 'PTypeDesc' => 'White Liner', 'DescLabel' => 'White Liner', 'CreateDate' => $createdate, 'CreatedBy' => $createdby],
        ];

        foreach ($data as $row) {
            DB::table('ptype')->updateOrInsert(
                ['site' => $row['site'], 'PType' => $row['PType']],
                $row
            );
        }
    }
}
