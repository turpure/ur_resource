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
        $step = 400;//获取数据量大小
        //获取数据表最大ID
        $maxID = DB::connection('pgsql')->table('wish_item_variation_specifics')->max('id');
        $max = ceil($maxID/$step);
        try{
            for ($i=0;$i<=$max;$i++){
                $listingSql = "SELECT itemid,sku,inventory,
                (CASE 
                    WHEN strpos(sku,'*') > 0 THEN substring(sku,1,strpos(sku,'*') - 1) 
                    WHEN strpos(sku,'@') > 0 THEN substring(sku,1,strpos(sku,'@') - 1) 
                    WHEN strpos(sku,'#') > 0 THEN substring(sku,1,strpos(sku,'#') - 1)
                    ELSE sku
                END) AS newSku,price
                FROM wish_item_variation_specifics 
                WHERE enabled<>'False' AND id BETWEEN " . ($step*$i + 1) . " AND " . $step*($i+1);
                $listing = DB::connection('pgsql')->select($listingSql);
                $listing = array_map('get_object_vars',$listing);
                if(!$listing){
                    continue;
                }else{
                    //插入数据
                    DB::table('ibay365_wish_listing')->insert($listing);
                }
            }
            $msg = date('Y-m-d H:i:s')." Wish SKU data migration successful\r\n";
        }catch (Exception $e){
            $msg = date('Y-m-d H:i:s').' '.$e->getMessage()."\r\n";
        }
        echo $msg;
    }

}