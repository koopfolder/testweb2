@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>Profile</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab">Information</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">                
                        {{ Form::open(['url' => route('admin.profile.index'), 'class' => 'form-horizontal', 'files' => true]) }}
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Avatar</label>
                                <div class="col-sm-10">
                                    @if ( auth()->user()->getMedia('avatar')->isNotEmpty())
                                        <img src="{{ asset(auth()->user()->getMedia('avatar')->first()->getUrl()) }}" width="150">
                                        <div>
                                            <a href="{{ route('admin.profile.delete.avatar', auth()->user()->id) }}" 
                                                data-toggle="confirmation" 
                                                data-title="Are You Sure?">Delete</a>
                                        </div>
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}" width="150">
                                    @endif
                                    <br><br>
                                    {{ Form::file('avatar') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Name <span style="color:red;">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('name', auth()->user()->name, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    {{ Form::email('email', auth()->user()->email, ['class' => 'form-control', 'disabled' => true]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    {{ Form::password('password', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">Again Password</label>
                                <div class="col-sm-10">
                                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
