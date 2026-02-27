<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $filepath = database_path('seeders/csv/vendors.csv');
        if (!file_exists($filepath)) {
            throw new \Exception("CSV file not found at path: $filepath");
        }

        $file = fopen($filepath, 'r');
        if ($file === false) {
            throw new \Exception("Failed to open CSV file at path: $filepath");
        }

        fgetcsv($file, 0, ','); // Skip the header row

        $batch = [];
        $batchSize = 1000; // Adjust batch size as needed

        while (($row = fgetcsv($file, 0, ',')) !== false) {
            // Process each line of the CSV file
            if (count($row) < 5) {
                continue; // Skip rows that don't have enough columns
            }
            $batch[] = [
                'Site' => $row[0],
                'Group' => $row[1],
                'Vendnum' => $row[2],
                'Name' => $row[3],
                'Currcode' => $row[4],
                'CreateDate' => now(),
                'CreatedBy' => 'sa',
            ];

            if (count($batch) >= $batchSize) {
                \DB::table('vendors')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            \DB::table('vendors')->insert($batch);
        }

        fclose($file);
    }
}
