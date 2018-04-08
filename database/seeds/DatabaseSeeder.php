<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IbayTableSeeder::class);
        $this->command->info('ibay365_ebay_listing数据表数据更新完毕!');
    }
}
