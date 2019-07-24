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
        $idArr = [1,2562000000,2563000000];
        try {
            foreach ($idArr as $k => $v){
                $query = DB::connection('pgsql')->table('joom_item_variation_specifics')
                    ->select(DB::raw("itemid,sku,inventory,(CASE 
                    WHEN strpos(sku,'*') > 0 THEN substring(sku,1,strpos(sku,'*') - 1) 
                    WHEN strpos(sku,'@') > 0 THEN substring(sku,1,strpos(sku,'@') - 1) 
                    WHEN strpos(sku,'#') > 0 THEN substring(sku,1,strpos(sku,'#') - 1)
                    ELSE sku
                END) AS newSku,price"))
                    ->where("enabled", 'True');
                if($k == count($idArr) - 1){
                    $query->where("id", '>', $v);
                }else{
                    $query->whereBetween("id", [$v, $idArr[$k + 1]]);
                }
                $query->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('joom_item')
                            ->whereRaw('joom_item_variation_specifics.itemid = joom_item.itemid')
                            ->where('joom_item.enabled','True');
                    })->orderBy('id')->chunk(200, function ($users) {
                        //print_r($users);exit;
                        if(!$users) return false;
                        $list = $users->map(function ($value) {return (array)$value;})->toArray();
                        DB::table('ibay365_joom_listing')->insert($list);
                    });
            }

            $msg = date('Y-m-d H:i:s') . " JOOM SKU Data migration successful\r\n";
        } catch (Exception $e) {
            $msg = date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\r\n";
        }
        echo $msg;
    }


}