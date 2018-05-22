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
        //清空数据表ibay365_ebay_listing
        DB::table('ibay365_wish_listing')->truncate();
        //获取ibay365表中eBay listing
        $step = 500;//获取数据量大小
        for ($i=0; ;$i++){
            $listingSql = "SELECT itemid,sku,inventory,
                (CASE 
                    WHEN INSTR(sku,'*') > 0 THEN SUBSTR(sku,1,INSTR(sku,'*') - 1) 
                    WHEN INSTR(sku,'@') > 0 THEN SUBSTR(sku,1,INSTR(sku,'@') - 1) 
                    WHEN INSTR(sku,'#') > 0 THEN SUBSTR(sku,1,INSTR(sku,'#') - 1)
                    ELSE sku
                END) AS newSku
                FROM wish_item_variation_specifics   
                WHERE enabled='True' " . ' LIMIT ' . $step*$i . ',' . $step;
            $listing = DB::connection('mysql')->select($listingSql);
            $listing = array_map('get_object_vars',$listing);
            if(!$listing){
                break;
            }else{
                //插入数据
                DB::table('ibay365_wish_listing')->insert($listing);
            }
        }
    }

}