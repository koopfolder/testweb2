@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ Request::segment(3) }}</h1>
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
                <div class="box-body">
                    <p class="lead">เก็บข้อมูลผู้ใช้งานทั้งหมด เช่น Administrator, Admin, Sale เป็นต้น</p>
                    <p> 
                        <h3>คุณสมบัติ</h3>
                        <ul>
                            <li>เพิ่ม ลบ แก้ ข้อมูล Users</li>
                            <li>ค้นหาจากชื่อ อีเมล</li>
                            <li>ลบได้ทีละหลายรายการ</li>
                            <li>เพิ่มรูปประจำตัวได้</li>
                            <li>เพิ่ม Roles ได้ (User 1 คนสามารถอยู่ได้หลาย Role)</li>
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