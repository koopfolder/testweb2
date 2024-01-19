@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('roles::backend.edit_group') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('roles::backend.home') }}</a></li>
        <li><a href="{{ route('admin.roles.index') }}">{{ trans('roles::backend.group') }}</a></li>
        <li class="active">{{ trans('roles::backend.edit') }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.roles.edit', $role->id)]) }}
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_group" data-toggle="tab">{{ trans('roles::backend.group') }}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_group">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>{{ trans('roles::backend.name') }} <span style="color:red;">*</span></label>
                    						{!! Form::text('name', $role->name ? $role->name : old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Name']) !!}
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="col-md-12">
                                                <input type="checkbox" id="select-all"> เลือกทั้งหมด
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                    $isRoles = array();
                                                    if ($role->abilities()->get()->isNotEmpty()) {
                                                        $isRoles = $role->abilities()->get()->pluck('name', 'name')->toArray();
                                                    }
                                                ?>
                                                @foreach($abilities as $ability)
                                                    <div>
                                                        <input type="checkbox"
                                                                name="abilities[]"
                                                                value="{{ $ability }}"
                                                                @if (in_array($ability, $isRoles))
                                                                checked="checked"
                                                                @endif
                                                                > {{ $ability }}</div>
                                                    @if ( ($loop->iteration%11) == 0)
                                                    </div>
                                                    <div class="col-md-4">
                                                    @endif
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> {{ trans('roles::backend.back') }}</a>
                                    <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> {{ trans('roles::backend.submit') }}</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('roles::backend.setting') }}</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Status <span style="color:red;">*</span></label>	
						{{ Form::select('status', ['publish' =>trans('roles::backend.publish'), 'draft' =>trans('roles::backend.draft')], $role->status ? $role->status : old('status'), ['class' => 'form-control']) }}
					</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

