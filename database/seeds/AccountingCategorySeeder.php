<?php

use Illuminate\Database\Seeder;
use App\Models\AccountingCategory;

class AccountingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountingCategory::truncate();

        $csvFile = fopen(base_path("database/data/AccountingCategory.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                AccountingCategory::create([
                    "code" => $data['0'],
                    "name" => $data['1'],
                    "updated_at" => date("Y-m-d"),
                    "created_at" => date("Y-m-d")
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
