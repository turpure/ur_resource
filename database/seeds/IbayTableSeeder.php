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
         //获取ibay365中eBay listing
         $step = 200;//每次取出数据数量
         for ($i=0; ;$i++){
             $listingSql = "SELECT e.itemid,e.sku AS code,er.sku AS sku,listingtype,country,initialnumber,
                (CASE 
                    WHEN INSTR(er.sku,'*') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'*') - 1) 
                    WHEN INSTR(er.sku,'@') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'@') - 1) 
                    WHEN INSTR(er.sku,'#') > 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'#') - 1)
                    ELSE er.sku
                END) AS newSku
                FROM ebay_item e  
                LEFT JOIN ebay_fillquantity er ON er.itemid=e.itemid
                WHERE country='CN' AND e.sku IS NOT NULL AND er.sku IS NOT NULL AND initialnumber<>0 
                AND listingstatus = 'Active' 
                AND listingtype = 'FixedPriceItem' " . ' LIMIT ' . $step*$i . ',' . $step;
             $listing = DB::connection('mysql')->select($listingSql);
             $listing = array_map('get_object_vars',$listing);
             if(!$listing){
                 break;
             }else{
                 //插入数据表中
                 DB::table('ibay365_ebay_listing')->insert($listing);
             }
         }

     }
}