<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
        $start = date('Y-m-d H:i:s');
        $this->info($start.'  开始获取数据!');

        //$exitCode = Artisan::call('db:seed');

        //
        $sql = "B_ModifyOnlineNumberOfSkuOnTheIbay365";
        $num = DB::connection('sqlsrv')->select($sql);
        $end = date('Y-m-d H:i:s');
        $this->info($end."  获取SKU在线数量数据完毕，数据数量{$num[0]->number}条，详情请查看数据表ibay365_quantity_online");

    }
}
