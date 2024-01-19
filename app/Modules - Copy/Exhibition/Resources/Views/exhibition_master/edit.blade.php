@extends('layout::app')

@section('content')
<section class="content-header">
    <h1>{{ trans('exhibition::backend.edit_exhibition_master') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}<</a></li>
        <li><a href="{{ route('admin.exhibition.master.index') }}">{{ trans('exhibition::backend.exhibition_master') }}</a></li>
        <li class="active">{{ trans('exhibition::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.exhibition.master.edit', $data->id), 'files' => true]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">{{ trans('exhibition::backend.description') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.title') }}<span style="color:red;">*</span></label>
                                    {{ Form::text('title', $data->title ?? old('title'), ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    <label for="InputName">{{ trans('exhibition::backend.description') }}</label>
                                        {{ Form::textarea('description',$data->description ?? old('description'), ['class' => 'form-control ckeditor']) }}
                                </div>
                            </div>                           
                            <a href="{{ route('admin.exhibition.master.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('exhibition::backend.back') }}</a>
                            <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('exhibition::backend.submit') }}</button>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('exhibition::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>{{ trans('exhibition::backend.parent') }}</label>
                        <select name="parent_id" class="form-control">
                            <option value="0" @if($data->parent_id == "0") selected="selected" @endif>Root</option>
                            @foreach($exhibition_masters as $exhibition_master)                                
                                    <option value="{{ $exhibition_master->id }}" @if($data->parent_id == $exhibition_master->id) selected="selected" @endif @if ($exhibition_master->id == $data->id) disabled @endif>
                                        {{ $exhibition_master->title }}
                                    </option>
                                    @if ($exhibition_master->children->isNotEmpty())
                                        @foreach($exhibition_master->children->sortBy('order') as $children)
                                        <option value="{{ $children->id }}" @if($data->parent_id == $children->id) selected="selected" @endif @if ($children->id == $data->id) disabled @endif>
                                            - {{ $children->title }}
                                        </option>
                                        @endforeach
                                    @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputName">{{ trans('exhibition::backend.order') }}</label>
                        {{ Form::text('order', $data->order, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        <label>{{ trans('exhibition::backend.status') }} <span style="color:red;">*</span></label>
                        {!! Form::select('status', ['publish' =>trans('exhibition::backend.publish'),  'draft' => trans('exhibition::backend.draft')], $data->status ?? old('status'), ['class' => 'form-control']) !!}
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
       
        $('.select2').select2({
            tags: false
        });
        $(".select2").on("select2:select", function (evt) {
              var element = evt.params.data.element;
              var $element = $(element);
              $element.detach();
              $(this).append($element);
              $(this).trigger("change");
        });

    });

</script>

@stop

