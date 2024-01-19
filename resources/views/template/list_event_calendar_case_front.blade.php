@inject('request', 'Illuminate\Http\Request')
@php
	//dd("Test");
@endphp
@if(method_exists('App\Modules\Article\Http\Controllers\EventCalendarController', 'getDataEventCalendar')) 
    @php
        $data = App\Modules\Article\Http\Controllers\EventCalendarController::getDataEventCalendar($request->all());  
        //dd($data);
    @endphp
@endif
@extends('layouts.app')
@section('content')
@include('partials.search')
<section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside">
                    <h1>ปฏิทินกิจกรรม</h1>
                </div>
            </div>
            <div class="row">
                <div id='calendar'></div>
            </div>
        </div>
</section>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('meta')
    @parent
    <title>ปฏิทินกิจกรรม</title>
    <meta charset="UTF-8">
    <meta name="description" content="ปฏิทินกิจกรรม">
    <meta name="keywords" content="ปฏิทินกิจกรรม">
    <meta name="author" content="">
@endsection
@section('style')
	@parent
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/fullcalendar-3.6.2/dist/fullcalendar.min.css') }}">
    <style>
        #calendar{
            max-width: 700px;
            margin: 40px auto;
            font-size:20px;
        }     
    </style>
@endsection
@section('js')
	@parent
<script type="text/javascript" src="{{ asset('adminlte/bower_components/fullcalendar-3.6.2/dist/lib/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminlte/bower_components/fullcalendar-3.6.2/dist/fullcalendar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminlte/bower_components/fullcalendar-3.6.2/dist/locale/th.js') }}"></script>
<script>
$(function(){
    data = <?php echo $data; ?>;
    console.log(data,typeof(data));
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',  //  prevYear nextYea
            center: 'title',
            right: 'month,agendaWeek,agendaDay',
        },  
        buttonIcons:{
            prev: 'left-single-arrow',
            next: 'right-single-arrow',
            prevYear: 'left-double-arrow',
            nextYear: 'right-double-arrow'         
        },       
//        theme:false,
//        themeButtonIcons:{
//            prev: 'circle-triangle-w',
//            next: 'circle-triangle-e',
//            prevYear: 'seek-prev',
//            nextYear: 'seek-next'            
//        },
//        firstDay:1,
//        isRTL:false,
//        weekends:true,
//        weekNumbers:false,
//        weekNumberCalculation:'local',
//        height:'auto',
//        fixedWeekCount:false,
        events:data,    
        eventLimit:true,
//        hiddenDays: [ 2, 4 ],
        lang: 'th',
        dayClick: function() {
//            alert('a day has been clicked!');
//            var view = $('#calendar').fullCalendar('getView');
//            alert("The view's title is " + view.title);
        }        
    });
  
});
</script>
@endsection