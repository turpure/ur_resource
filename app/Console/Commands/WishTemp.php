<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-04-12
 * Time: 16:29
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class WishTemp extends Command
{
    /**
     * The name and signature of the console command.
     *  php artisan wish:temp
     * @var string
     */
    protected $signature = 'wish:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = date('Y-m-d H:i:s');
        echo $start.' 开始获取数据';
        //清空数据表ibay365_wish_listing
        DB::table('guest.wish_itemid_skulist')->truncate();
        //获取ibay365中Wish listing
        $step = 100;//每次取出数据数量
        //var_dump($step);exit;
        for ($i=0; ;$i++){
            /*$listingSql = "SELECT e.itemid,e.sku,er.sku AS subsku,er.inventory,
                (CASE WHEN INSTR(er.sku,'*') <> 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'*') - 1) 
				WHEN INSTR(er.sku,'#') <> 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'#') - 1) 
				WHEN INSTR(er.sku,'@') <> 0 THEN SUBSTR(er.sku,1,INSTR(er.sku,'@') - 1) 
				ELSE er.sku END) AS newSku
                FROM wish_item e 
                LEFT JOIN wish_item_variation_specifics er ON er.itemid=e.itemid
                WHERE e.listingstatus='Active' AND e.sku IS NOT NULL AND er.sku IS NOT NULL " .
                ' LIMIT ' . $step*$i . ',' . $step;*/
            $listingSql = "SELECT * from wish_item_variation_specifics 
                WHERE enabled='True' AND inventory>0 " .
                ' LIMIT ' . $step*$i . ',' . $step;
            $listing = DB::connection('mysql')->select($listingSql);
            $listing = array_map('get_object_vars',$listing);

            //var_dump($listing);exit;
            if(!$listing){
                break;
            }else{
                //插入数据表中
                DB::table('guest.wish_itemid_skulist')->insert($listing);
            }
        }
        echo date('Y-m-d H:i:s').' 数据获取完毕';exit;

        //获取要下架的商品的编码
        /*$sql = "B_WishOffShelfOnTheIbay365";
        $num = DB::connection('sqlsrv')->select($sql);
        $end = date('Y-m-d H:i:s');
        echo $end."  获取Wish平台下架产品数据完毕，数据数量{$num[0]->number}条，详情请查看数据表ibay365_wish_off_shelf";*/




    }
}