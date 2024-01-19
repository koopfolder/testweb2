@extends('layout::app')

@section('content')
<section class="content-header">
	<h1>{{ trans('exhibition::backend.book_an_exhibition') }}</h1>
	<ol class="breadcrumb">
    	<li><a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('exhibition::backend.home') }}</a></li>
    	<li><a href="{{ route('admin.book-an-exhibition.index') }}">{{ trans('exhibition::backend.book_an_exhibition') }}</a></li>
	</ol>
</section
>
@php
    //dd($items);
@endphp
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ trans('exhibition::backend.exhibition') }}</th>
                                <th>{{ trans('exhibition::backend.name') }}</th>
                                <th>{{ trans('exhibition::backend.description') }}</th>
                                <th>{{ trans('exhibition::backend.email') }}</th>
                                <th>{{ trans('exhibition::backend.created') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            @php
                                //dd($item);
                            @endphp
                            <tr data-entry-id="{{ $item->id }}">
                                <td>{{ (isset($item->exhibition->title) ? $item->exhibition->title:'') }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection