<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-04-03
 * Time: 11:58
 */

use \Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class SmtTableSeeder extends Seeder
{
    public function run()
    {
        //清空数据表ibay365_ebay_listing
        DB::connection('sqlsrv')->table('ibay365_smt_listing')->truncate();
        /*
        //获取ibay365表中eBay listing
        $step = 400;//获取数据量大小
        //获取数据表最大ID
        $maxID = DB::connection('pgsql')->table('aliexpress_items_variation_specifics')->max('id');
        $max = ceil($maxID/$step);
        try{
            /*for ($i=0;$i<=$max;$i++){
                $listingSql = "SELECT e.sku AS code,er.sku AS sku,
                (CASE 
                    WHEN strpos(er.sku,'*') > 0 THEN substring(er.sku,1,strpos(er.sku,'*') - 1) 
                    WHEN strpos(er.sku,'@') > 0 THEN substring(er.sku,1,strpos(er.sku,'@') - 1) 
                    WHEN strpos(er.sku,'#') > 0 THEN substring(er.sku,1,strpos(er.sku,'#') - 1)
                    ELSE er.sku
                END) AS newSku,concat(er.itemid,'') AS itemid,listingstatus,quantity,e.selleruserid
                FROM aliexpress_items_variation_specifics er  
                INNER JOIN aliexpress_items e ON er.itemid=e.productid
                WHERE listingstatus NOT IN ('offline') AND er.id BETWEEN " . ($step*$i+1) . ' AND ' . $step*($i+1);
                $listing = DB::connection('pgsql')->select($listingSql);
                $listing = array_map('get_object_vars',$listing);
                if(!$listing){
                    continue;
                }else{
                    //var_dump($listing);exit;
                    //插入数据
                    DB::connection('sqlsrv')->table('ibay365_smt_listing')->insert($listing);
                }
            }
        */
        try {
            $sql = "select productid itemid, listingstatus,selleruserid, regexp_replace(split_part(sku,'_',1) ,'(0[1-9]{1}$)','','g' ) as sku  from aliexpress_items where listingstatus != 'offline'";
            $listing = DB::connection('pgsql')->select($sql);
            $listing = array_map('get_object_vars',$listing);
            $number = count($listing);
            $size = 500;
            $step = ceil($number / $size);
            $reminder = $number % $size ;
            for ($i=0; $i< $step; $i++ ) {
                $size = $i*$size < $number ? $size : $reminder -1;
                $rows = array_slice($listing, $i* $size, $size);
                DB::connection('sqlsrv')->table('ibay365_smt_listing')->insert($rows);
                echo date('Y-m-d H:i:s')." SMT $i SKU data migration successful\r\n";
            }

        }catch (Exception $e){
            echo date('Y-m-d H:i:s').' '.$e->getMessage()."\r\n";
        }
    }

}