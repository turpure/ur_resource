<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * eBay页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ebay()
    {
        return view('ebay');
    }
    /**
     * Wish页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wish()
    {
        return view('wish');
    }

    /**
     * 处理ebay数据
     * @param Request $request
     * @return string
     */
    public function doEbay(Request $request)
    {
        if($request->isMethod('post')){
            set_time_limit(0);//0表示不限时
            ini_set('memory_limit', '-1');
            try{
                //Artisan::call('db:seed --class=IbayTableSeeder');//已加入定时任务，每日5点执行
                $sql = "B_ModifyOnlineNumberOfSkuOnTheIbay365";
                $num = DB::connection('sqlsrv')->select($sql);
                $data = '<br>'.date('Y-m-d H:i:s') . "  Getting the online number of SKU data is successful.Look at the data table ibay365_quantity_online for details.";

            }catch (\Exception $e){
                $data = $e->getMessage();
            }
            return $data;
            /*if ($num){
                return $data;
            }else{
                return '程序错误，请联系管理员！';
            }*/
        }
    }
    /**
     * 处理ebay数据
     * @param Request $request
     * @return string
     */
    public function doWish(Request $request)
    {
        if($request->isMethod('post')){
            set_time_limit(0);//0表示不限时
            ini_set('memory_limit', '-1');
            try{
                //Artisan::call('db:seed --class=WishTableSeeder');//已加入定时任务，每日5点执行
                $sql = "B_wish_ModifyOnlineNumberOnTheIbay365";
                $num = DB::connection('sqlsrv')->select($sql);
                $data = '<br>'.date('Y-m-d H:i:s') . "  Getting the online number of SKU data is successful.Look at the data table ibay365_wish_quantity for details.";

            }catch (\Exception $e){
                $data = $e->getMessage();
            }
            return $data;
            /*if ($num){
                return $data;
            }else{
                return '程序错误，请联系管理员！';
            }*/
        }
    }

}
