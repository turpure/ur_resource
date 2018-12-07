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
            $maxId = DB::connection('mysql')->table('joom_item_variation_specifics')->where("created", '<', strtotime('2018-12-01'))->max('id');
            $list = $this->getData(1, $maxId);
            if ($list) {
                //插入数据
                DB::table('ibay365_joom_listing')->insert($list);
            }

            //插入2018年12月1日以后的数据
            $step = 400;//获取数据量大小
            //获取数据表最大ID
            $minId = DB::connection('mysql')->table('joom_item_variation_specifics')->where("created", '>=', strtotime('2018-12-01'))->min('id');
            $maxId = DB::connection('mysql')->table('joom_item_variation_specifics')->where("created", '>=', strtotime('2018-12-01'))->max('id');
            $count = ceil(($maxId - $minId - 1) / $step);

            for ($i = 0; $i <= $count; $i++) {
                $min = $i * $step + $minId;
                $max = ($i + 1) * $step + $minId - 1;
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
                    WHEN INSTR(sku,'*') > 0 THEN SUBSTR(sku,1,INSTR(sku,'*') - 1) 
                    WHEN INSTR(sku,'@') > 0 THEN SUBSTR(sku,1,INSTR(sku,'@') - 1) 
                    WHEN INSTR(sku,'#') > 0 THEN SUBSTR(sku,1,INSTR(sku,'#') - 1)
                    ELSE sku
                END) AS newSku,price
                FROM joom_item_variation_specifics 
                WHERE enabled<>'False' AND id BETWEEN " . $min . " AND " . $max;
        $listing = DB::connection('mysql')->select($listingSql);
        return array_map('get_object_vars', $listing);
    }


}