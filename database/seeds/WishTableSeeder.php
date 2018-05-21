<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-04-03
 * Time: 11:58
 */

use \Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class WishTableSeeder extends Seeder
{
    public function run()
    {
        //清空数据表ibay365_wish_listing
        DB::table('ibay365_wish_listing')->truncate();
        //获取ibay365表中eBay listing
        $step = 500;//获取数据量大小
        $listingSql = DB::raw("itemid,sku,inventory,
                (CASE 
                    WHEN INSTR(sku,'*') > 0 THEN SUBSTR(sku,1,INSTR(sku,'*') - 1) 
                    WHEN INSTR(sku,'@') > 0 THEN SUBSTR(sku,1,INSTR(sku,'@') - 1) 
                    WHEN INSTR(sku,'#') > 0 THEN SUBSTR(sku,1,INSTR(sku,'#') - 1)
                    ELSE sku
                END) AS newSku");
        DB::connection('mysql')->table('wish_item_variation_specifics')
            ->select($listingSql)->where('enabled', 'True')
            ->orderBy('id')
            ->chunk($step, function ($lists) {
                $listing = array_map('get_object_vars', $lists->toArray());
                DB::table('ibay365_wish_listing')->insert($listing);
            });
    }

}