<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class GetEbayStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //清空数据表ibay365_ebay_listing_of_virtual_store
        DB::table('ibay365_ebay_listing_of_virtual_store')->truncate();
        //获取ibay365表中eBay listing
        $step = 200;//获取数据量大小
        //获取数据表最大ID
        $maxID = DB::connection('mysql')->table('ebay_item_variation_specifics')->max('id');
        $max = ceil($maxID/$step);
        try{
            for ($i=0;$i<=$max;$i++){
                $listingSql = "SELECT e.itemid,e.sku AS code,er.sku AS sku,listingtype,country,location,onlinequantity,selleruserid,
                (CASE 
                    WHEN INSTR(er.sku,'*') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'*') - 1) 
                    WHEN INSTR(er.sku,'@') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'@') - 1) 
                    WHEN INSTR(er.sku,'#') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'#') - 1)
                    ELSE er.sku
                END) AS newSku,
                (CASE 
                    WHEN INSTR(er.sku,'*') > 0 AND INSTR(er.sku,'@') > 0 THEN SUBSTR(SUBSTR(er.sku,1,INSTR(er.sku,'@')-1),INSTR(er.sku,'*')+1)
                    WHEN INSTR(er.sku,'*') > 0 AND INSTR(er.sku,'@') <= 0 THEN SUBSTR(er.sku,INSTR(er.sku,'*') + 1)
                    ELSE 1
                END) AS number
                FROM ebay_item e  
                LEFT JOIN ebay_item_variation_specifics er ON er.itemid=e.itemid
                WHERE country='GB' AND location='London' AND e.sku IS NOT NULL AND er.sku IS NOT NULL 
                AND listingstatus = 'Active' 
                AND e.itemid NOT IN ('202123578166','162858075066') 
                AND listingtype = 'FixedPriceItem' AND id BETWEEN " . ($step*$i+1) . ' AND ' . $step*($i+1);
                $listing = DB::connection('mysql')->select($listingSql);
                $listing = array_map('get_object_vars',$listing);
                if(!$listing){
                    continue;
                }else{
                    //插入数据
                    DB::table('ibay365_ebay_listing_of_virtual_store')->insert($listing);
                }
            }
            $msg = date('Y-m-d H:i:s')." Ebay virtual store data migration successful\r\n";
        }catch (Exception $e){
            $msg = date('Y-m-d H:i:s').' '.$e->getMessage()."\r\n";
        }
        echo $msg;
    }
}
