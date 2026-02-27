<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $currencies = [
            ['Currcode' => 'USD', 'CurrDesc' => 'US Dollar', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'EUR', 'CurrDesc' => 'Euro', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'JPY', 'CurrDesc' => 'Japanese Yen', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'GBP', 'CurrDesc' => 'British Pound', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'AUD', 'CurrDesc' => 'Australian Dollar', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'CAD', 'CurrDesc' => 'Canadian Dollar', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'CHF', 'CurrDesc' => 'Swiss Franc', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'CNY', 'CurrDesc' => 'Chinese Yuan', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'SEK', 'CurrDesc' => 'Swedish Krona', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'NZD', 'CurrDesc' => 'New Zealand Dollar', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
            ['Currcode' => 'PHP', 'CurrDesc' => 'Philippine Peso', 'CreateDate' => now(), 'CreatedBy' => 'sa'],
        ];

        foreach ($currencies as $currency) {
            \DB::table('currency')->insert($currency);
        }
    }
}
