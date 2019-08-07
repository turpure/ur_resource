<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-04-03
 * Time: 11:58
 */

use \Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class JoomTableSeeder extends Seeder
{
    public function run()
    {
        //echo date('Y-m-d H:i:s') . " start get JOOM SKU Data \r\n";
        //清空数据表ibay365_ebay_listing
        DB::connection('sqlsrv')->table('ibay365_joom_listing')->truncate();
        $sql = "select  jv.itemid,jv.sku,jv.inventory,(CASE 
                    WHEN strpos(jv.sku,'*') > 0 THEN substring(jv.sku,1,strpos(jv.sku,'*') - 1) 
                    WHEN strpos(jv.sku,'@') > 0 THEN substring(jv.sku,1,strpos(jv.sku,'@') - 1) 
                    WHEN strpos(jv.sku,'#') > 0 THEN substring(jv.sku,1,strpos(jv.sku,'#') - 1)
                    ELSE jv.sku
                END) AS newSku,jv.price  from joom_item as jt LEFT JOIN joom_item_variation_specifics as jv 
                on jt.itemid = jv.itemid where jt.enabled='True' and jv.enabled='True' ";
        try {
            $listing = DB::connection('pgsql')->select($sql);
            $listing = array_map('get_object_vars',$listing);
            $number = count($listing);
            $size = 100;
            $step = ceil($number / $size);
            $reminder = $number % $size ;
            for ($i=0; $i< $step; $i++ ) {
                $size = $i*$size < $number ? $size : $reminder -1;
                $rows = array_slice($listing, $i* $size, $size);
                DB::connection('sqlsrv')->table('ibay365_joom_listing')->insert($rows);
            }
            echo date('Y-m-d H:i:s')." Joom $i SKU data migration successful\r\n";
        } catch (Exception $e) {
            echo date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\r\n";
        }
    }


}