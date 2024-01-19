@extends('layout::app')

@section('content')

<section class="content-header">
    <h1>{{ trans('dashboard::backend.dashboard') }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('dashboard::backend.home') }}</a></li>
        <li class="active">{{ trans('dashboard::backend.dashboard') }}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-body">

            <section>
                <div class="box no-border">
                    <div class="box-header">
                        <h3 class="less no-margin">Admin THRC</h3>
                    </div>
                </div>
            </section>

            <section>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="info-box bg-yellow-gradient">
                            <span class="info-box-icon"><i class="ion-ios-people-outline"></i></span>
                            <div class="info-box-content bg-yellow">
                                <span class="info-box-text">{{ trans('dashboard::backend.users') }}</span>
                                <span class="info-box-number">{{ $users->count() }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 0%"></div>
                                </div>
                                <span class="progress-description">
                                    @can('manage-users')
                                    <a href="{{ route('admin.users.index') }}" style="color:white">{{ trans('dashboard::backend.all') }}</a>
                                    @endcan
                                </span>
                            </div>
                        </div>
                    </div>


                </div>
            </section>
        </div>
    </div>
</section>

@endsection
