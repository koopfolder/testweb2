@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Modules</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.modules.index') }}"> Modules</a></li>
        <li class="active">Detail</li>
    </ol>
</section>

<section class="content">   
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ Request::segment(3) }}</h3>
                </div>
                <div class="box-body">
                    <p class="lead">แสดงผลโดยรวมของระบบ</p>
                    <p>
                        <ul>
                            <li>เลือกรูปแบบ Widgets มาแสดงผลได้ เช่น สรุปยอดขายรายวัน, จำนวนสมาชิกเว็บ</li>
                            <li>เพิ่มข้อมูลทางลัด</li>
                        </ul>
                    </p>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.modules.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection