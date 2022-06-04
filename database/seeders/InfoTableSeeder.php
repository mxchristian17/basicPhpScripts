<?php

namespace Database\Seeders;
use App\Models\Info;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $info = new Info();
        $info->attribute_name = 'Site_title';
        $info->attribute_value = 'Basic PHP Scripts';
        $info->save();
    }
}
