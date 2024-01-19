@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>แก้ไขสินค้า</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
        <li><a href="{{ route('admin.product.index') }}">สินค้า</a></li>
        <li class="active">แก้ไข</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.product.edit', $post->id), 'files' => true]) }}
        {{ Form::hidden('redirect', Request::get('category')) }}
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">รายละเอียด</a></li>
                            <li><a href="#tab_image" data-toggle="tab">รูปภาพ</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">SEO</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Revision</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">ชื่อสินค้า (ไทย) <span style="color:red;">*</span></label>
                                    {{ Form::text('title', $post->title ?? old('title'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">ชื่อสินค้า (อังกฤษ) <span style="color:red;">*</span></label>
                                    {{ Form::text('title_en', $post->title_en ?? old('title_en'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">ราคา</label>
                                    {{ Form::text('excerpt', $post->excerpt ?? old('excerpt'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputEmailAddress">รายละเอียด (ไทย)</label>
                                    {{ Form::textarea('content', $post->content ?? old('content'), ['class' => 'form-control ckeditor', 'rows' => 4]) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputEmailAddress">รายละเอียด (อังกฤษ)</label>
                                    {{ Form::textarea('content_en', $post->content_en ?? old('content_en'), ['class' => 'form-control ckeditor', 'rows' => 4]) }}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_image">
                                <div class="form-group">
                                    <label>รูปสินค้า (Desktop) <span style="color:red;">*</span> (560px * 338px)</label>
                                    @if (!$post->getMedia('desktop')->isEmpty())
                                    <div style="margin-bottom:5px;"><img src="{{ asset($post->getMedia('desktop')->first()->getUrl()) }}" width="50%"></div>
                                    @endif
                                    <div>{!! Form::file('desktop') !!}</div>
                                    <p class="help-block">ประเภทไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                                </div> 
                                <div class="form-group">
                                    <label>รูปสินค้า (Mobile) <span style="color:red;">*</span> (560px * 338px)</label>
                                    @if (!$post->getMedia('mobile')->isEmpty())
                                    <div style="margin-bottom:5px;"><img src="{{ asset($post->getMedia('mobile')->first()->getUrl()) }}" width="50%"></div>
                                    @endif
                                    <div>{!! Form::file('mobile') !!}</div>
                                    <p class="help-block">ประเภทไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                                </div> 
                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    {!! Form::text('meta_title', $post->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    {!! Form::text('meta_keywords', $post->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    {!! Form::textarea('meta_description', $post->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>  
                            <div class="tab-pane" id="tab_2">
                                @include('product::partials.revision', ['pages' => $pages])
                            </div>
                            <a href="{{ route('admin.product.index', ['category' => Request::get('category')]) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>                 
                        </div>
                    </div>
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
                        <label>หมวดหมู่ <span style="color:red;">*</span></label>
                        {!! Form::select('category', $categories, old('category'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>สถานะ <span style="color:red;">*</span></label>
                        @if (Auth::user()->can('manage-approve'))
                            <?php $statuses = ['publish' => 'เปิดใช้งาน', 'approve' => 'รออนุมัติ', 'draft' => 'ปิดใช้งาน']; ?>
                        @else
                            <?php $statuses = ['approve' => 'รออนุมัติ', 'draft' => 'ปิดใช้งาน']; ?>
                        @endif
                        {{ Form::select('status', $statuses, $post->status, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

