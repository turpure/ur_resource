<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *  php artisan ebay:modify
     * @var string
     */
    protected $signature = 'ebay:modify';

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

        /*$sql = "SELECT itemid,sku,listingduration,country,
                (CASE INSTR(sku,'@#') WHEN 0 THEN sku ELSE SUBSTR(sku,1,INSTR(sku,'@#') - 1) END) AS newSku
                FROM ebay_item WHERE country='CN' AND sku IS NOT NULL
                AND listingduration IN('GTC','Days_7','Days_30')";

        $res = DB::connection('mysql')->select($sql);*/

        $sql = "SELECT top 10 * FROM oa_goodsinfo";

       $res = DB::connection('sqlsrv')->select($sql);
        var_dump($res);exit;
    }
}
