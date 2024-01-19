@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>เพิ่มหมวดหมู่</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
        <li><a href="{{ route('admin.product.categories.index') }}">หมวดหมู่</a></li>
        <li class="active">เพิ่มหมวดหมู่</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.product.categories.create'), 'files' => true]) }}
        {{ Form::hidden('user_id', auth()->user()->id) }}
        {{ Form::hidden('module', 'product') }}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="InputName">ชื่อ (ไทย) <span style="color:red">*</span></label>
                        {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">ชื่อ (อังกฤษ)</label>
                        {{ Form::text('title_en', old('title_en'), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.product.categories.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
                    <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">ตั้งค่า</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>สถานะ <span style="color:red">*</span></label>
                        {!! Form::select('status', ['publish' => 'เปิดใช้งาน', 'draft' => 'ปิดใช้งาน'], old('status'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>เรียง</label>
                        {!! Form::text('order', old('order') ?? 0, ['class' => 'form-control']) !!}
                    </div>
                     <div class="form-group">
                        <label>ภาพพื้นหลัง</label>
                        <div>{!! Form::file('bg') !!}</div>
                        <p class="help-block">ประเภทไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                    </div> 
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>
@endsection


