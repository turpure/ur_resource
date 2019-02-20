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
        $maxID = DB::connection('pgsql')->table('ebay_item_variation_specifics')->max('id');
        $max = ceil($maxID/$step);
        try{
            for ($i=0;$i<=$max;$i++){
                $listingSql = "SELECT e.itemid,e.sku AS code,er.sku AS sku,listingtype,country,location,onlinequantity,selleruserid,
                (CASE 
                    WHEN strpos(er.sku,'*') > 0 THEN substring(er.sku,1,strpos(er.sku,'*') - 1) 
                    WHEN strpos(er.sku,'@') > 0 THEN substring(er.sku,1,strpos(er.sku,'@') - 1) 
                    WHEN strpos(er.sku,'#') > 0 THEN substring(er.sku,1,strpos(er.sku,'#') - 1)
                    ELSE er.sku
                END) AS newSku,
                (CASE 
                    WHEN strpos(er.sku,'*') > 0 AND strpos(er.sku,'@#') > 0 THEN substring(substring(er.sku,1,strpos(er.sku,'@#')-1),strpos(er.sku,'*')+1)
                    WHEN strpos(er.sku,'*') > 0 AND strpos(er.sku,'#@') > 0 THEN substring(substring(er.sku,1,strpos(er.sku,'#@')-1),strpos(er.sku,'*')+1)
                    WHEN strpos(er.sku,'*') > 0 AND strpos(er.sku,'@') <= 0 THEN substring(er.sku,strpos(er.sku,'*') + 1)
                    ELSE 1::TEXT 
                END) AS number
                FROM ebay_item e  
                LEFT JOIN ebay_item_variation_specifics er ON er.itemid=e.itemid
                WHERE selleruserid IN ('actinoliteye3','aufhjsnn','bookhaha3','cherishfu5','cloudwhiteha0','coolskyna2',
                'danheopk','doublecoor2','enjoyhappyha9','global_saler','greengrassha0','gundenzi','happyfebruaryha2',
                'happyjanuary1','hezhilei9','leinvdyad','littlemay93','Mailingsa','njlapaa','okfqava','piandages','purityhand7',
                'qianleihe9','sfsdfdaa','shuaiwsu-0','simplecooller2','smartmilitary5','springyinee6','stairhaha2','sunseeke6',
                'uisehdn','urnotchrisleer4','vesaxoun','vitalityang1','willyerxie08','wooddiyy3','yingerop')
                AND e.sku IS NOT NULL AND er.sku IS NOT NULL 
                AND listingstatus = 'Active' 
                AND e.itemid NOT IN ('202123578166','162858075066','273227558403','232656400769') 
                AND listingtype = 'FixedPriceItem' AND id BETWEEN " . ($step*$i+1) . ' AND ' . $step*($i+1);
                $listing = DB::connection('pgsql')->select($listingSql);
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
