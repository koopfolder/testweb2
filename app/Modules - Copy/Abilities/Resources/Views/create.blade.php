@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Create Ability</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.abilities.index') }}">Abilities</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        {{ Form::open(['url' => route('admin.abilities.create')]) }}
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
						{!! Form::label('name', 'Name *', ['class' => 'control-label']) !!}
						{!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Name']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    <a href="{{ route('admin.abilities.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
                    <button class="btn btn-success pull-right"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Setting</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
						<label>Status *</label>
						{{ Form::select('status', ['publish' => 'Publish', 'draft' => 'Draft'], old('status'), ['class' => 'form-control']) }}
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
		 $("#select-all").click(function () {
		     $('input:checkbox').not(this).prop('checked', this.checked);
		 });
	});
</script>
@stop

