<!-- 存放在 resources/views/child.blade.php -->
@extends('layouts.app')

@section('title', 'Wish')


@section('sidebar')
    @parent
    {{--<p>Laravel学院致力于提供优质Laravel中文学习资源</p>--}}
@endsection

@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Wish
            <small>修改在线数量&下架</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Wish</li>
        </ol>
    </section>
    <section class="content">
        <p>暂无数据</p>
    </section>
</div>
@endsection

<script type="text/javascript">
    window.onload = function(){
        //菜单高亮显示
        $('.sidebar-menu').find('.active').removeClass('active');
        $('.site-wish').addClass('active');

        //按钮提交



    }

</script>

