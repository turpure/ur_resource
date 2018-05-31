<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-04-03
 * Time: 11:58
 */
use \Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class IbayTableSeeder extends Seeder
{
    public function run()
     {
         //清空数据表ibay365_ebay_listing
         DB::table('ibay365_ebay_listing')->truncate();
         //获取ibay365表中eBay listing
         $step = 200;//获取数据量大小
         //获取数据表最大ID
         $maxID = DB::connection('mysql')->table('ebay_item_variation_specifics')->max('id');
         $max = ceil($maxID/$step);
         try{
             for ($i=0;$i<=$max;$i++){
                 $listingSql = "SELECT e.itemid,e.sku AS code,er.sku AS sku,listingtype,country,onlinequantity AS initialnumber,
                (CASE 
                    WHEN INSTR(er.sku,'*') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'*') - 1) 
                    WHEN INSTR(er.sku,'@') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'@') - 1) 
                    WHEN INSTR(er.sku,'#') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'#') - 1)
                    ELSE er.sku
                END) AS newSku
                FROM ebay_item e  
                LEFT JOIN ebay_item_variation_specifics er ON er.itemid=e.itemid
                WHERE country='CN' AND e.sku IS NOT NULL AND er.sku IS NOT NULL 
                AND listingstatus = 'Active' 
                AND listingtype = 'FixedPriceItem' AND id BETWEEN " . ($step*$i+1) . ' AND ' . $step*($i+1);
                 $listing = DB::connection('mysql')->select($listingSql);
                 $listing = array_map('get_object_vars',$listing);
                 if(!$listing){
                     continue;
                 }else{
                     //插入数据
                     DB::table('ibay365_ebay_listing')->insert($listing);
                 }
             }
             $msg = date('Y-m-d H:i:s')." Data migration successful\r\n";
         }catch (Exception $e){
             $msg = date('Y-m-d H:i:s').' '.$e->getMessage()."\r\n";
         }
         echo $msg;
     }
}