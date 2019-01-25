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
        try {
            //插入2018年12月1日以前的数据
            $maxId = DB::connection('pgsql')->table('joom_item_variation_specifics')->where("created", '<', strtotime('2018-12-01'))->max('id');
            $list1 = $this->getData(1, $maxId);
            if ($list1) {
                //插入数据
                DB::table('ibay365_joom_listing')->insert($list1);
            }

            //插入2018年12月31日以前的数据
            $minId1 = DB::connection('pgsql')->table('joom_item_variation_specifics')->whereBetween("created", [strtotime('2018-12-01'), strtotime('2018-12-31')])->min('id');
            $maxId1 = DB::connection('pgsql')->table('joom_item_variation_specifics')->whereBetween("created", [strtotime('2018-12-01'), strtotime('2018-12-31')])->max('id');
            $list2 = $this->getData($minId1, $maxId1);
            if ($list2) {
                //插入数据
                DB::table('ibay365_joom_listing')->insert($list2);
            }

            //插入2019年1月1日以后的数据
            $step = 400;//获取数据量大小
            //获取数据表最大ID
            $minId = DB::connection('pgsql')->table('joom_item_variation_specifics')->where("created", '>=', strtotime('2019-01-01'))->min('id');
            $maxId = DB::connection('pgsql')->table('joom_item_variation_specifics')->where("created", '>=', strtotime('2018-01-01'))->max('id');
            $count = ceil(($maxId - $minId + 1) / $step);
            //print_r($minId.'  '.$maxId."\r\n");
            for ($i = 0; $i < $count; $i++) {
                $min = $i * $step + $minId;
                $max = ($i + 1) * $step + $minId - 1;
                //print_r($min.'  '.$max."\r\n");
                //if ($i=2);exit();

               $dataList = $this->getData($min, $max);
               if (!$dataList) {
                   continue;
               } else {
                   //插入数据
                   DB::table('ibay365_joom_listing')->insert($dataList);
               }

            }
            $msg = date('Y-m-d H:i:s') . " JOOM SKU Data migration successful\r\n";
        } catch (Exception $e) {
            $msg = date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\r\n";
        }
        echo $msg;
    }

    public function getData($min, $max)
    {
        $listingSql = "SELECT itemid,sku,inventory,
                (CASE 
                    WHEN strpos(sku,'*') > 0 THEN substring(sku,1,strpos(sku,'*') - 1) 
                    WHEN strpos(sku,'@') > 0 THEN substring(sku,1,strpos(sku,'@') - 1) 
                    WHEN strpos(sku,'#') > 0 THEN substring(sku,1,strpos(sku,'#') - 1)
                    ELSE sku
                END) AS newSku,price
                FROM joom_item_variation_specifics 
                WHERE enabled<>'False' AND id BETWEEN " . $min . " AND " . $max;
        $listing = DB::connection('pgsql')->select($listingSql);
        return array_map('get_object_vars', $listing);
    }


}