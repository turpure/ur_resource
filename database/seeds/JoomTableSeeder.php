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
        //清空数据表ibay365_ebay_listing
        DB::table('ibay365_joom_listing')->truncate();
        //获取ibay365表中eBay listing
        $step = 160;//获取数据量大小
        //获取数据表最大ID
        $count = DB::connection('mysql')->table('joom_item_variation_specifics')->count();
        $max = ceil($count/$step);
        //print_r($max);exit;
        try{
            for ($i=0;$i<=$max;$i++){
                $listingSql = "SELECT itemid,sku,inventory,
                (CASE 
                    WHEN INSTR(sku,'*') > 0 THEN SUBSTR(sku,1,INSTR(sku,'*') - 1) 
                    WHEN INSTR(sku,'@') > 0 THEN SUBSTR(sku,1,INSTR(sku,'@') - 1) 
                    WHEN INSTR(sku,'#') > 0 THEN SUBSTR(sku,1,INSTR(sku,'#') - 1)
                    ELSE sku
                END) AS newSku,price
                FROM (
                  SELECT @rownum:=@rownum+1 as rownum,itemid,sku,inventory,price 
                  FROM (SELECT @rownum:=0) r,joom_item_variation_specifics j WHERE enabled<>'False' ORDER BY id ASC 
                ) ss
                 WHERE rownum BETWEEN " . ($i*$step + 1) . " AND " . (($i+1)*$step);
                $listing = DB::connection('mysql')->select($listingSql);
                $list = array_map('get_object_vars',$listing);
                if(!$list){
                    continue;
                }else{
                    //插入数据
                    DB::table('ibay365_joom_listing')->insert($list);
                }
            }
            $msg = date('Y-m-d H:i:s')." JOOM SKU Data migration successful\r\n";
        }catch (Exception $e){
            $msg = date('Y-m-d H:i:s').' '.$e->getMessage()."\r\n";
        }
        echo $msg;
    }
}