<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/data-points.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('datapoints')->insert(array(
                'datapoint_category_id' => $obj->category_id,
                'name' => $obj->name,
                'question' => $obj->question,
                'sort' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ));
        }
    }
}
