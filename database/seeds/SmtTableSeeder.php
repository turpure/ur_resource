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
        DB::table('ibay365_smt_listing')->truncate();
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
                    DB::table('ibay365_smt_listing')->insert($listing);
                }
            }*/
            DB::connection('pgsql')->table(DB::raw('aliexpress_items_variation_specifics AS s'))
                ->join('aliexpress_items', 'productid', '=', 'itemid')
                ->join('aliexpress_user', 'aliexpress_user.selleruserid', '=', 'aliexpress_items.selleruserid')
                ->select(DB::raw("itemid,aliexpress_items.selleruserid,s.sku,s.quantity"))
                ->where([
                    ["platform", '=', 'aliexpress'],
                    ['state1', '=', '1'],
                ])
                ->whereIn('aliexpress_items.listingstatus',['onSelling','auditing'])
                ->orderBy('s.id')->chunk(400, function ($users) {
                    if(!$users) return false;
                    $list = [];
                    foreach ($users as $user) {
                        $list[] = get_object_vars($user);
                    }
                    DB::table('ibay365_smt_listing')->insert($list);
                });
            $msg = date('Y-m-d H:i:s')." SMT SKU data migration successful\r\n";
        }catch (Exception $e){
            $msg = date('Y-m-d H:i:s').' '.$e->getMessage()."\r\n";
        }
        echo $msg;
    }

}