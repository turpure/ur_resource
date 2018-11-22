<?php
/**
 * @desc PhpStorm.
 * @author: turpure
 * @since: 2018-11-21 13:58
 */

use \Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetPayPalSeeder extends Seeder
{
    public function run()
    {
        $onlineSql = 'SELECT DISTINCT paypal FROM ebay_item where paypal is not null';
        $localSql = 'SELECT DISTINCT paypalName AS paypal FROM oa_paypal';
        try {
            $onlinePayPalNames = DB::connection('mysql')->select($onlineSql);
            $localPayPalNames = DB::connection('sqlsrv')->select($localSql);
            $localPayPal = [];
            foreach ($localPayPalNames as $name) {
                $localPayPal[] = $name->paypal;
            }
            // 不在本地列表中的为异常payPal
            foreach ($onlinePayPalNames as $name) {
                if (!in_array($name->paypal, $localPayPal, false)) {
                    print_r($name->paypal);
                    print_r("\r\n");
                    $exceptionPayPal[] = $name->paypal;
                }
            }

            $insertSql = 'INSERT INTO exceptionPaypal (itemid,paypal,selleruserid,createdTime) VALUES(?,?,?,?)
                ON DUPLICATE KEY UPDATE createdTime=values(createdtime)';
            if (!empty($exceptionPayPal)) {
                foreach ($exceptionPayPal as $paypal) {
                    $itemSql = "select  itemid,paypal,selleruserid from ebay_item where paypal='$paypal' limit 100";
                    $ret = DB::connection('mysql')->select($itemSql);
                    foreach ($ret as $row) {
                        DB::connection('oauthoa')->insert($insertSql, [
                            $row->itemid, $row->paypal, $row->selleruserid, date('Y-m-d H:i:s')
                        ]);
//                    print_r("putting exeception paypal $row->paypal \r\n ");
                        Log::info("putting exception paypal $row->paypal");
                    }

                }
            }
            else {
                DB::connection('oauthoa')->insert($insertSql, [
                    0, 'none', 'none', date('Y-m-d H:i:s')
                ]);
                Log::info('there are not any exception paypal today');
            }
        } catch (Exception  $why) {
            Log::error("failed to get exception paypal cause of $why");

    }

}
}