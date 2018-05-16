<!-- 存放在 resources/views/child.blade.php -->
@extends('layouts.app')

@section('title', 'eBay')


@section('sidebar')
    @parent
@endsection

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                eBay
                <small>修改在线数量&下架</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">eBay</li>
            </ol>
        </section>

        <section class="content">
            <div class="row" style="margin-left: 10px">
                <button class="btn btn-primary submit">执行</button>

            </div>
            <div class="row msg" style="margin-left: 10px;margin-top: 20px;font-size: 18px"></div>
        </section>
    </div>
@endsection

<script type="text/javascript">
    window.onload = function () {
        //菜单高亮显示
        $('.sidebar-menu').find('.active').removeClass('active');
        $('.site-ebay').addClass('active');

        //按钮提交
        $('.submit').on('click', function () {
            var myDate = new Date();

            var year  = myDate.getFullYear(); //获取当前年
            var month = myDate.getMonth()+1;  //获取当前月
            var date  = myDate.getDate();     //获取当前日
            var h = myDate.getHours();        //获取当前小时数(0-23)
            var m = myDate.getMinutes();      //获取当前分钟数(0-59)
            var s = myDate.getSeconds();

            var now=year+'-'+month+"-"+date+" "+h+':'+m+":"+s;
            var str = now + 'Start getting data... <br> In the calculation, do not close the web page!'
            $('.msg').html(str);
            $.post("{{ url('site/doEbay') }}",
                {'_token': '{{ csrf_token() }}'}, function (data) {
                    $('.msg').append(data);
            });
        })
    }
</script>
