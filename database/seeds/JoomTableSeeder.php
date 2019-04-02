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
        DB::table('ibay365_joom_listing')->truncate();
        try {

            DB::connection('pgsql')->table('joom_item_variation_specifics')
                ->select(DB::raw("itemid,sku,inventory,(CASE 
                    WHEN strpos(sku,'*') > 0 THEN substring(sku,1,strpos(sku,'*') - 1) 
                    WHEN strpos(sku,'@') > 0 THEN substring(sku,1,strpos(sku,'@') - 1) 
                    WHEN strpos(sku,'#') > 0 THEN substring(sku,1,strpos(sku,'#') - 1)
                    ELSE sku
                END) AS newSku,price"))
                ->where("enabled", '<>', 'False')
                ->orderBy('id')->chunk(400, function ($users) {
                    if(!$users) return false;
                    $list = [];
                    foreach ($users as $user) {
                        $list[] = get_object_vars($user);
                    }
                    DB::table('ibay365_joom_listing')->insert($list);
                });

            $msg = date('Y-m-d H:i:s') . " JOOM SKU Data migration successful\r\n";
        } catch (Exception $e) {
            $msg = date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\r\n";
        }
        echo $msg;
    }


}