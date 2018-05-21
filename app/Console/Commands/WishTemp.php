<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-04-12
 * Time: 16:29
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
        $this->info($start.'  Start getting data...');

        $exitCode = Artisan::call('db:seed --class=WishTableSeeder');

        //
        //$sql = "B_ModifyOnlineNumberOfSkuOnTheIbay365";
        //$num = DB::connection('sqlsrv')->select($sql);
        //$end = date('Y-m-d H:i:s');
        //$this->info($end."  Getting the online number of SKU data is successful.The number of data is {$num[0]->number}.Look at the data table ibay365_quantity_online for details.");


    }
}