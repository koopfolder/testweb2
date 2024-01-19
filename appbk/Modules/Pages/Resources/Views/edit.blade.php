@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>แก้ไขหน้า</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> หน้าแรก</a></li>
        <li><a href="{{ route('admin.single-page.index') }}">หน้า</a></li>
        <li class="active">แก้ไข</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.pages.edit', $page->id), 'files' => true]) }}
        @if (Request::has('redirect-back'))
            {{ Form::hidden('redirect-back', Request::get('redirect-back')) }}
        @endif
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">รายละเอียด</a></li>
                            <li><a href="#tab_image" data-toggle="tab">รูปภาพ</a></li>
                            <li><a href="#tab_seo" data-toggle="tab">SEO</a></li>
                            <li><a href="#tab_revision" data-toggle="tab">Revision</a></li>                            
<!--                             <li class="pull-right"><a href="{{ route('admin.single-page.preview.index', $page->id) }}" class="text-muted"><i class="fa fa-eye"></i></a></li> -->
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">ชื่อ (ไทย) <span style="color:red">*</span></label>
                                    {{ Form::text('title', $page->title ?? old('title'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">ชื่อ (อังกฤษ)</label>
                                    {{ Form::text('title_en', $page->title_en ?? old('title_en'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="Excerpt">รายละเอียด (ไทย)</label>
                                    {{ Form::textarea('excerpt', $page->excerpt ?? old('excerpt'), ['class' => 'form-control ckeditor']) }}
                                </div>
                                <div class="form-group">
                                    <label for="Excerpt">รายละเอียด (อังกฤษ)</label>
                                    {{ Form::textarea('excerpt_en', $page->excerpt_en ?? old('excerpt_en'), ['class' => 'form-control ckeditor']) }}
                                </div>                 
                            </div>
                            <div class="tab-pane" id="tab_image">
                                @if (!$page->getMedia('desktop')->isEmpty())
                                 <div class="form-group">
                                    @if ($page->getMedia('desktop')->isNotEmpty())
                                        <div>
                                            <a href="{{ route('admin.single-page.deleteImage', [$page->id, 'desktop']) }}" data-toggle="confirmation" data-title="Are You Sure?">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <img src="{{ asset($page->getMedia('desktop')->first()->getUrl()) }}" style="max-width: 100%">
                                        </div>
                                    @endif
                                </div>
                                @endif                                
                                <div class="form-group">
                                    <label>รูปภาพ (Desktop)</label>
                                    <div><input type="file" name="desktop"></div>
                                    <p class="help-block">ประเภทไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                                </div>

                                @if (!$page->getMedia('mobile')->isEmpty())
                                 <div class="form-group">
                                    @if ($page->getMedia('mobile')->isNotEmpty())
                                        <div>
                                            <a href="{{ route('admin.single-page.deleteImage', [$page->id, 'mobile']) }}" data-toggle="confirmation" data-title="Are You Sure?">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                        <div><img src="{{ asset($page->getMedia('mobile')->first()->getUrl()) }}" style="max-width: 100%"></div>
                                    @endif
                                </div>
                                @endif
                                <div class="form-group">
                                    <label>รูปภาพ (Mobile)</label>
                                    <div><input type="file" name="mobile"></div>
                                    <p class="help-block">ประเภทไฟล์: jpeg, png (ไม่เกิน 2M)</p>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab_seo">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    {!! Form::text('meta_title', $page->meta_title ?? old('meta_title'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    {!! Form::text('meta_keywords', $page->meta_keywords ?? old('meta_keywords'), ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    {!! Form::textarea('meta_description', $page->meta_description ?? old('meta_description'), ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_revision">
                                @include('pages::partials.revision', ['pages' => $pages])
                            </div>      
                            <div class="form-group">
                                <a href="{{ route('admin.single-page.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
                                <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
                            </div>                                  
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
                        <label>สถานะ</label>
                        {!! Form::select('status', ['publish' => 'เปิดใช้งาน', 'draft' => 'ปิดใช้งาน'], $page->status ?? old('status'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>ประเภทลิ้ง</label>
                        {!! Form::select('link_type', ['' => '', 'external' => 'ภายนอก', 'internal' => 'ภายใน'], $page->link_type ?? old('link_type'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group" id="link_external">
                        <label for="InputName">ลิ้ง</label>
                        {{ Form::text('link_external', $page->link ?? old('url_external'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group" id="link_internal">
                        <label>รายการเมนู</label>
                        <?php $selectMenuId = null; ?>
                        @if ($page->link != '')
                            <?php
                                $m = App\Modules\Menus\Models\Menu::where('url_internal', $page->link)->first();
                                $selectMenuId = $m ? $m->id : null;
                            ?>
                        @endif
                        {!! Form::select('link_internal', $menus, $selectMenuId ?? old('link_internal'), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('javascript')
<script type="text/javascript">
    $(function() {        
        function link() {
            var link_type = $("select[name=link_type]").val();   
            if (link_type == 'external') {
                $("#link_external").show();
                $("#link_internal").hide();
            } else if(link_type == 'internal') {
                $("#link_external").hide();
                $("#link_internal").show();
            } else {
                $("#link_external").hide();
                $("#link_internal").hide();
            }
        }
        link();
        $("select[name=link_type]").on('change', function(event) {
            event.preventDefault();
            link() 
        });

    });
</script>
@stop
