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
        try {
            DB::connection('pgsql')->table(DB::raw('ebay_item_variation_specifics AS v'))
                ->join('ebay_item', 'ebay_item.itemid', '=', 'v.itemid')
                ->join('ebay_user', 'ebay_user.selleruserid', '=', 'ebay_item.selleruserid')
                ->select(DB::raw("v.itemid,ebay_item.selleruserid,ebay_item.sku as parentSKU,v.sku,v.onlinequantity AS inventory,now()::timestamp(0)without time zone AS updateDate"))
                ->where([
                    ["ebay_item.country", '<>', 'CN'],
                    ['state1', '=', '1'],
                    ['listingtype', '=', 'FixedPriceItem'],
                ])
                ->whereIn('ebay_item.listingstatus', ['Active'])
                ->orderBy('v.id')->chunk(400, function ($users) {
                    //print_r($users);exit;
                    if (!$users) return false;
                    $list = [];
                    foreach ($users as $user) {
                        $list[] = get_object_vars($user);
                    }
                    DB::table('ibay365_ebay_listing_of_virtual_store')->insert($list);
                });
            $msg = date('Y-m-d H:i:s') . " Ebay virtual store data migration successful\r\n";
        } catch (Exception $e) {
            $msg = date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\r\n";
        }
        echo $msg;
    }
}
