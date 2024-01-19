<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  <title>THRC</title>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->

  {{ Html::style('black-dashboard/assets/css/black-dashboard.css?v=1.0.0') }}
  <!-- CSS Just for demo purpose, don't include it in your project -->
  
  <style>
.card-chart .chart-area{
  height: 100% !important;
}

.main-panel>.content{
    padding: 78px 30px 30px 30px !important;
}

.container-fluid{
    margin-top: 10px;
    padding-left: 0px;
}

#chart_month_year{
  width: 100%;
  height: 450px;
}

#chart_urequest{
  width: 100%;
  height: 450px;
}

#chart_issues{
  width: 100%;
  height: 450px;  
}

#chart_target{
  width: 100%;
  height: 450px;  
}

#chart_setting{
  width: 100%;
  height: 450px;  
}

#chart_statistics_api_by_organization{
  width: 100%;
  height: 450px;  
}



#chart_keyword{
  width: 100%;
  height: 450px;  
}

.card-tasks{
  height: 539px !important;
}

.c_year {
    margin-left: 4%;
    margin-right: 7%;
}

  </style>
  @section('style')
    
  @show
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div class="wrapper" id="app">

    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="{{ route('admin.dashboard.index') }}">Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
              <!-- <li class="search-bar input-group">
                <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split" ></i>
                  <span class="d-lg-none d-md-block">Search</span>
                </button>
              </li>
              <li class="dropdown nav-item">
                <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="notification d-none d-lg-block d-xl-block"></div>
                  <i class="tim-icons icon-sound-wave"></i>
                  <p class="d-lg-none">
                    Notifications
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                  <li class="nav-link"><a href="#" class="nav-item dropdown-item">Mike John responded to your email</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">You have 5 more tasks</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Your friend Michael is in town</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another notification</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another one</a></li>
                </ul>
              </li>
              <li class="dropdown nav-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="photo">
                    <img src="../assets/img/anime3.png" alt="Profile Photo">
                  </div>
                  <b class="caret d-none d-lg-block d-xl-block"></b>
                  <p class="d-lg-none">
                    Log out
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-navbar">
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Profile</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Settings</a></li>
                  <li class="dropdown-divider"></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Log out</a></li>
                </ul>
              </li>
              <li class="separator d-lg-none"></li> -->
            </ul>
          </div>
        </div>
      </nav>
      <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- End Navbar -->
      <div class="content">

        <div class="row">
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.chart_month_year') }}</h3>

              </div>
              <div class="card-body">
                <div class="chart-area">
                  {!! Form::select('c1_year',$year,'',['class'=>'c_year']); !!}
                  {!! Form::select('c1_month',$month,'',['class'=>'']); !!}
                  <div id="chart_month_year"></div> 
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.chart_urequest') }}</h3>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  {!! Form::select('c2_year',$year,'',['class'=>'c_year']); !!}
                  {!! Form::select('c2_month',$month,'',['class'=>'']); !!}
                  <div id="chart_urequest"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.chart_issues') }}</h3>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <div id="chart_issues"></div> 
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.chart_target') }}</h3>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <div id="chart_target"></div> 
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-6">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.chart_setting') }}</h3>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <div id="chart_setting"></div> 
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 ">
            <div class="card card-chart">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.statistics_api_by_organization') }}</h3>
              </div>
              <div class="card-body ">
                <div class="chart-area">
                  <div id="chart_statistics_api_by_organization"></div> 
                </div>                       
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="card card-tasks">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.most_viewed_statistics') }}</h3>
              </div>
              <div class="card-body">
                <div class="card-body">
                <most-viewed-statistics-component
                  text_title="{{ trans('dashboard::backend.title') }}"
                  text_number_of_visitors="{{ trans('dashboard::backend.number_of_visitors') }}"
                  url_api_most_viewed_statistic="{{ route('report.logs.api.data-most-viewed-statistic')  }}"
                  access_token="{{ $access_token }}"
                ></most-viewed-statistics-component>                          
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 ">
            <div class="card card-tasks">
              <div class="card-header">
                <h3 class="card-title">{{ trans('dashboard::backend.chart_keyword') }}</h3>
              </div>
              <div class="card-body ">
                <statistic-keyword-component
                  text_keyword="{{ trans('dashboard::backend.keyword') }}"
                  text_count_search="{{ trans('dashboard::backend.count_search') }}"
                  url_api_keyword_search="{{ route('report.logs.api.data-keyword-search')  }}"
                  access_token="{{ $access_token }}"
                ></statistic-keyword-component>                                      
              </div>
            </div>
          </div>
        </div>        


      </div>
    </div>
  </div>
<!-- VueJS -->
{{ Html::script('js/app.js')  }}  
{{ Html::script('black-dashboard/assets/js/core/jquery.min.js')  }}

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script> -->
{{ Html::script('black-dashboard/assets/js/plugins/perfect-scrollbar.jquery.min.js')  }}



<!--  Html::script('black-dashboard/assets/js/core/jquery.min.js') 
 Html::script('black-dashboard/assets/js/core/popper.min.js') 
 Html::script('black-dashboard/assets/js/core/bootstrap.min.js') 
 Html::script('black-dashboard/assets/js/plugins/perfect-scrollbar.jquery.min.js') 
 Html::script('black-dashboard/assets/js/plugins/chartjs.min.js') 
 Html::script('black-dashboard/assets/js/plugins/bootstrap-notify.js') 
 Html::script('black-dashboard/assets/js/black-dashboard.min.js?v=1.0.0') 
 Html::script('black-dashboard/assets/demo/demo.js') -->

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
<script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/dark.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

{{ Html::script('adminlte/bower_components/axios/axios.min.js')  }}
{{ Html::script('adminlte/bower_components/js-base64/base64.min.js')  }}
<script>

const url_api_data_chart_month_year = '{{ route('report.logs.api.data-chart-month-year') }}';
const url_api_data_chart_hour = '{{ route('report.logs.api.data-chart-hour') }}';
const url_api_data_chart_issues = '{{ route('report.logs.api.data-chart-issues') }}';
const url_api_data_chart_target = '{{ route('report.logs.api.data-chart-target') }}';
const url_api_data_chart_setting = '{{ route('report.logs.api.data-chart-setting') }}';
const url_api_data_chart_statistics_api_by_organization = '{{ route('report.logs.api.data-statistics-api-by-organization') }}';

const access_token = '{{ $access_token }}';

const d = new Date();
const year_now = d.getFullYear();
//console.log(year_now);


const headers = {
  'Content-Type': 'application/json; charset=utf-8',
  'authorization': access_token
}


// generate some random data, quite different range
function generateChartData(data,year,month){
  //console.log(data);
  var chartData = [];
  var firstDate = new Date();
  //firstDate.setDate(firstDate.getDate());
  firstDate.setHours(0, 0, 0, 0);
  firstDate.setFullYear(year);
  if(month !='0'){
    firstDate.setMonth(month);
  }
  //console.log(firstDate,firstDate.getDate());

  for(let value of data){
    //console.log(value);
    var newDate = new Date(firstDate);
    //newDate.setDate(newDate.getDate() + i);
    newDate.setHours(newDate.getHours() + value.hour);

    //console.log(newDate.toLocaleDateString('en-US'));
    //console.log(newDate);
    chartData.push({
      date: newDate,
      total:value.total,
    });
    //console.log(chartData);
  }

  return chartData;
}


$(document).ready(function($) {
  //console.log("Tasdasdadssd");



  /*Case c1 */

  $('select[name="c1_year"]').change(function(event) {
      //console.log($(this).val());
      //console.log($('select[name="c1_month"]').val());
      axios.post(url_api_data_chart_month_year,{
        c1_year: Base64.encode($(this).val()),
        c1_month: Base64.encode($('select[name="c1_month"]').val()),
       "device_token":"thrc_backend"
      },{
        headers:headers
      })
      .then(function (response) {
        // handle success
        //console.log(response.data);
       // Create chart instance
          let chart_month_year = am4core.create("chart_month_year", am4charts.XYChart);

          // Add data

          chart_month_year.data =response.data.data;
          //console.log(chart_month_year.data);

          // Create axes
          var dateAxis = chart_month_year.xAxes.push(new am4charts.DateAxis());
          //dateAxis.renderer.grid.template.location = 0;
          //dateAxis.renderer.minGridDistance = 30;

          var valueAxis1 = chart_month_year.yAxes.push(new am4charts.ValueAxis());
          valueAxis1.title.text = "";

          var valueAxis2 = chart_month_year.yAxes.push(new am4charts.ValueAxis());
          valueAxis2.title.text = "";
          valueAxis2.renderer.opposite = true;
          valueAxis2.renderer.grid.template.disabled = true;

          // Create series
          var series1 = chart_month_year.series.push(new am4charts.ColumnSeries());
          series1.dataFields.valueY = "rateuse";
          series1.dataFields.dateX = "date";
          series1.yAxis = valueAxis1;
          series1.name = "Total";
          series1.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
          series1.fill = chart_month_year.colors.getIndex(0);
          series1.strokeWidth = 0;
          series1.clustered = false;
          series1.columns.template.width = am4core.percent(40);
          series1.columns.template.fill = am4core.color("#ADADAD");


          // Add cursor
          chart_month_year.cursor = new am4charts.XYCursor();

          // Add legend
          chart_month_year.legend = new am4charts.Legend();
          chart_month_year.legend.position = "top";

          // Add scrollbar
          chart_month_year.scrollbarX = new am4charts.XYChartScrollbar();
          chart_month_year.scrollbarX.series.push(series1);
          //chart_month_year.scrollbarX.series.push(series3);
          chart_month_year.scrollbarX.parent = chart_month_year.bottomAxesContainer;  
          chart_month_year.logo.disabled = true;

      })
      .catch(function (error) {
        // handle error
        console.log(error);
      })
      .then(function () {
        // always executed
      });


  });

  $('select[name="c1_month"]').change(function(event) {

      axios.post(url_api_data_chart_month_year,{
        c1_year: Base64.encode($('select[name="c1_year"]').val()),
        c1_month: Base64.encode($(this).val()),
        "device_token":"thrc_backend"
      },{
          headers:headers
      })
      .then(function (response) {
        // handle success
        console.log(response.data);

        // Create chart instance
          let chart_month_year = am4core.create("chart_month_year", am4charts.XYChart);

          // Add data

          chart_month_year.data =response.data.data;
          //console.log(chart_month_year.data);

          // Create axes
          var dateAxis = chart_month_year.xAxes.push(new am4charts.DateAxis());
          //dateAxis.renderer.grid.template.location = 0;
          //dateAxis.renderer.minGridDistance = 30;

          var valueAxis1 = chart_month_year.yAxes.push(new am4charts.ValueAxis());
          valueAxis1.title.text = "";

          var valueAxis2 = chart_month_year.yAxes.push(new am4charts.ValueAxis());
          valueAxis2.title.text = "";
          valueAxis2.renderer.opposite = true;
          valueAxis2.renderer.grid.template.disabled = true;

          // Create series
          var series1 = chart_month_year.series.push(new am4charts.ColumnSeries());
          series1.dataFields.valueY = "rateuse";
          series1.dataFields.dateX = "date";
          series1.yAxis = valueAxis1;
          series1.name = "Total";
          series1.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
          series1.fill = chart_month_year.colors.getIndex(0);
          series1.strokeWidth = 0;
          series1.clustered = false;
          series1.columns.template.width = am4core.percent(40);
          series1.columns.template.fill = am4core.color("#ADADAD");


          // Add cursor
          chart_month_year.cursor = new am4charts.XYCursor();

          // Add legend
          chart_month_year.legend = new am4charts.Legend();
          chart_month_year.legend.position = "top";

          // Add scrollbar
          chart_month_year.scrollbarX = new am4charts.XYChartScrollbar();
          chart_month_year.scrollbarX.series.push(series1);
          //chart_month_year.scrollbarX.series.push(series3);
          chart_month_year.scrollbarX.parent = chart_month_year.bottomAxesContainer;  
          chart_month_year.logo.disabled = true;


      })
      .catch(function (error) {
        // handle error
        console.log(error);
      })
      .then(function () {
        // always executed
      });


  });

/*End Case c1 */


/*Case c2 */

  $('select[name="c2_year"]').change(function(event) {
      //console.log($(this).val());
      
    axios.post(url_api_data_chart_hour,{
        c2_year: Base64.encode($(this).val()),
        c2_month: Base64.encode($('select[name="c2_month"]').val()),
        "device_token":"thrc_backend"
      },{
          headers:headers
      })
      .then(function (response) {
        // handle success
        //console.log(response.data);

 // Create chart instance
        var chart_urequest = am4core.create("chart_urequest", am4charts.XYChart);

        // Increase contrast by taking evey second color
        chart_urequest.colors.step = 2;

        // Add data
        chart_urequest.data = generateChartData(response.data.data,year_now,0);

        //console.log(chart_urequest.data);

        // Create axes
        var dateAxis = chart_urequest.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;
        

        // Create series
        function createAxisAndSeries(field, name, opposite, bullet) {
          var valueAxis = chart_urequest.yAxes.push(new am4charts.ValueAxis());
          if(chart_urequest.yAxes.indexOf(valueAxis) != 0){
            valueAxis.syncWithAxis = chart_urequest.yAxes.getIndex(0);
          }

          var series = chart_urequest.series.push(new am4charts.LineSeries());
          series.dataFields.valueY = field;
          series.dataFields.dateX = "date";
          series.strokeWidth = 2;
          series.yAxis = valueAxis;
          series.name = name;
          series.tooltipText = "{name}: [bold]{valueY}[/]";
          series.tensionX = 0.8;
          series.showOnInit = true;

          var interfaceColors = new am4core.InterfaceColorSet();

          switch(bullet) {
                    case "triangle":
                      var bullet = series.bullets.push(new am4charts.Bullet());
                      bullet.width = 12;
                      bullet.height = 12;
                      bullet.horizontalCenter = "middle";
                      bullet.verticalCenter = "middle";

                      var triangle = bullet.createChild(am4core.Triangle);
                      triangle.stroke = interfaceColors.getFor("background");
                      triangle.strokeWidth = 2;
                      triangle.direction = "top";
                      triangle.width = 12;
                      triangle.height = 12;

                      series.stroke = am4core.color("#A4CA39"); 
                      break;
                    case "rectangle":
                      var bullet = series.bullets.push(new am4charts.Bullet());
                      bullet.width = 10;
                      bullet.height = 10;
                      bullet.horizontalCenter = "middle";
                      bullet.verticalCenter = "middle";

                      var rectangle = bullet.createChild(am4core.Rectangle);
                      rectangle.stroke = interfaceColors.getFor("background");
                      rectangle.strokeWidth = 2;
                      rectangle.width = 10;
                      rectangle.height = 10;

                      series.stroke = am4core.color("#3C8DBC"); 
                      break;
                    default:
                      var bullet = series.bullets.push(new am4charts.CircleBullet());
                      bullet.circle.stroke = interfaceColors.getFor("background");
                      bullet.circle.strokeWidth = 2;

                      series.stroke = am4core.color("#ADADAD"); 
                      break;
              }

          valueAxis.renderer.line.strokeOpacity = 1;
          valueAxis.renderer.line.strokeWidth = 2;
          valueAxis.renderer.line.stroke = series.stroke;
          valueAxis.renderer.labels.template.fill = series.stroke;
          valueAxis.renderer.opposite = opposite;
        }


        createAxisAndSeries("total", "Total", false, "circle");
       //createAxisAndSeries("android", "Android", true, "triangle");
        //createAxisAndSeries("total", "Total", true, "rectangle");

        // Add legend
        chart_urequest.legend = new am4charts.Legend();

        // Add cursor
        chart_urequest.cursor = new am4charts.XYCursor();
        chart_urequest.logo.disabled = true;



      })
      .catch(function (error) {
        // handle error
        console.log(error);
      })
      .then(function () {
        // always executed
      });

  });

  $('select[name="c2_month"]').change(function(event) {
  
   //console.log($(this).val());

   axios.post(url_api_data_chart_hour,{
        c2_year: Base64.encode($('select[name="c2_year"]').val()),
        c2_month: Base64.encode($(this).val()),
        "device_token":"thrc_backend"
      },{
          headers:headers
      })
      .then(function (response) {
        // handle success
        //console.log(response.data);

 // Create chart instance
        var chart_urequest = am4core.create("chart_urequest", am4charts.XYChart);

        // Increase contrast by taking evey second color
        chart_urequest.colors.step = 2;

        // Add data
        chart_urequest.data = generateChartData(response.data.data,year_now,0);

        //console.log(chart_urequest.data);

        // Create axes
        var dateAxis = chart_urequest.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;
        

        // Create series
        function createAxisAndSeries(field, name, opposite, bullet) {
          var valueAxis = chart_urequest.yAxes.push(new am4charts.ValueAxis());
          if(chart_urequest.yAxes.indexOf(valueAxis) != 0){
            valueAxis.syncWithAxis = chart_urequest.yAxes.getIndex(0);
          }

          var series = chart_urequest.series.push(new am4charts.LineSeries());
          series.dataFields.valueY = field;
          series.dataFields.dateX = "date";
          series.strokeWidth = 2;
          series.yAxis = valueAxis;
          series.name = name;
          series.tooltipText = "{name}: [bold]{valueY}[/]";
          series.tensionX = 0.8;
          series.showOnInit = true;

          var interfaceColors = new am4core.InterfaceColorSet();

          switch(bullet) {
                    case "triangle":
                      var bullet = series.bullets.push(new am4charts.Bullet());
                      bullet.width = 12;
                      bullet.height = 12;
                      bullet.horizontalCenter = "middle";
                      bullet.verticalCenter = "middle";

                      var triangle = bullet.createChild(am4core.Triangle);
                      triangle.stroke = interfaceColors.getFor("background");
                      triangle.strokeWidth = 2;
                      triangle.direction = "top";
                      triangle.width = 12;
                      triangle.height = 12;

                      series.stroke = am4core.color("#A4CA39"); 
                      break;
                    case "rectangle":
                      var bullet = series.bullets.push(new am4charts.Bullet());
                      bullet.width = 10;
                      bullet.height = 10;
                      bullet.horizontalCenter = "middle";
                      bullet.verticalCenter = "middle";

                      var rectangle = bullet.createChild(am4core.Rectangle);
                      rectangle.stroke = interfaceColors.getFor("background");
                      rectangle.strokeWidth = 2;
                      rectangle.width = 10;
                      rectangle.height = 10;

                      series.stroke = am4core.color("#3C8DBC"); 
                      break;
                    default:
                      var bullet = series.bullets.push(new am4charts.CircleBullet());
                      bullet.circle.stroke = interfaceColors.getFor("background");
                      bullet.circle.strokeWidth = 2;

                      series.stroke = am4core.color("#ADADAD"); 
                      break;
              }

          valueAxis.renderer.line.strokeOpacity = 1;
          valueAxis.renderer.line.strokeWidth = 2;
          valueAxis.renderer.line.stroke = series.stroke;
          valueAxis.renderer.labels.template.fill = series.stroke;
          valueAxis.renderer.opposite = opposite;
        }


        createAxisAndSeries("total", "Total", false, "circle");
       //createAxisAndSeries("android", "Android", true, "triangle");
        //createAxisAndSeries("total", "Total", true, "rectangle");

        // Add legend
        chart_urequest.legend = new am4charts.Legend();

        // Add cursor
        chart_urequest.cursor = new am4charts.XYCursor();
        chart_urequest.logo.disabled = true;



      })
      .catch(function (error) {
        // handle error
        console.log(error);
      })
      .then(function () {
        // always executed
      });      

  });

/*End Case c2 */

});





am4core.ready(function() {
// Themes begin
am4core.useTheme(am4themes_dark);
am4core.useTheme(am4themes_animated);
// Themes end
am4core.options.autoSetClassName = true;

// chart_month_year--------------------------------------------------------------------------------------

  axios.post(url_api_data_chart_month_year,{
      c1_year: Base64.encode(year_now),
      c1_month: Base64.encode('0'),
      "device_token":"thrc_backend"
    },{
      headers:headers
    })
  .then(function(response) {
    //console.log(response.data.data);

    // Create chart instance
    let chart_month_year = am4core.create("chart_month_year", am4charts.XYChart);

    // Add data

    chart_month_year.data =response.data.data;
    //console.log(chart_month_year.data);

    // Create axes
    var dateAxis = chart_month_year.xAxes.push(new am4charts.DateAxis());
    //dateAxis.renderer.grid.template.location = 0;
    //dateAxis.renderer.minGridDistance = 30;

    var valueAxis1 = chart_month_year.yAxes.push(new am4charts.ValueAxis());
    valueAxis1.title.text = "";

    var valueAxis2 = chart_month_year.yAxes.push(new am4charts.ValueAxis());
    valueAxis2.title.text = "";
    valueAxis2.renderer.opposite = true;
    valueAxis2.renderer.grid.template.disabled = true;

    // Create series
    var series1 = chart_month_year.series.push(new am4charts.ColumnSeries());
    series1.dataFields.valueY = "rateuse";
    series1.dataFields.dateX = "date";
    series1.yAxis = valueAxis1;
    series1.name = "Total";
    series1.tooltipText = "{name}\n[bold font-size: 20]{valueY}[/]";
    series1.fill = chart_month_year.colors.getIndex(0);
    series1.strokeWidth = 0;
    series1.clustered = false;
    series1.columns.template.width = am4core.percent(40);
    series1.columns.template.fill = am4core.color("#ADADAD");


    // Add cursor
    chart_month_year.cursor = new am4charts.XYCursor();

    // Add legend
    chart_month_year.legend = new am4charts.Legend();
    chart_month_year.legend.position = "top";

    // Add scrollbar
    chart_month_year.scrollbarX = new am4charts.XYChartScrollbar();
    chart_month_year.scrollbarX.series.push(series1);
    //chart_month_year.scrollbarX.series.push(series3);
    chart_month_year.scrollbarX.parent = chart_month_year.bottomAxesContainer;  
    chart_month_year.logo.disabled = true;



  })
  .catch(function(error){
  console.log(error); // Network Error
  });






// chart_urequest ----------------------------------------------------------------------------------------


  axios.post(url_api_data_chart_hour,{
    c2_year: Base64.encode(year_now),
    c2_month: Base64.encode('0'),
    "device_token":"thrc_backend"
    },{
      headers:headers
    })
  .then(function (response) {
    // handle success
    //console.log(response.data);

     // Create chart instance
        var chart_urequest = am4core.create("chart_urequest", am4charts.XYChart);

        // Increase contrast by taking evey second color
        chart_urequest.colors.step = 2;

        // Add data
        chart_urequest.data = generateChartData(response.data.data,year_now,0);

        //console.log(chart_urequest.data);

        // Create axes
        var dateAxis = chart_urequest.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;
        

        // Create series
        function createAxisAndSeries(field, name, opposite, bullet) {
          var valueAxis = chart_urequest.yAxes.push(new am4charts.ValueAxis());
          if(chart_urequest.yAxes.indexOf(valueAxis) != 0){
            valueAxis.syncWithAxis = chart_urequest.yAxes.getIndex(0);
          }

          var series = chart_urequest.series.push(new am4charts.LineSeries());
          series.dataFields.valueY = field;
          series.dataFields.dateX = "date";
          series.strokeWidth = 2;
          series.yAxis = valueAxis;
          series.name = name;
          series.tooltipText = "{name}: [bold]{valueY}[/]";
          series.tensionX = 0.8;
          series.showOnInit = true;

          var interfaceColors = new am4core.InterfaceColorSet();

          switch(bullet) {
                    case "triangle":
                      var bullet = series.bullets.push(new am4charts.Bullet());
                      bullet.width = 12;
                      bullet.height = 12;
                      bullet.horizontalCenter = "middle";
                      bullet.verticalCenter = "middle";

                      var triangle = bullet.createChild(am4core.Triangle);
                      triangle.stroke = interfaceColors.getFor("background");
                      triangle.strokeWidth = 2;
                      triangle.direction = "top";
                      triangle.width = 12;
                      triangle.height = 12;

                      series.stroke = am4core.color("#A4CA39"); 
                      break;
                    case "rectangle":
                      var bullet = series.bullets.push(new am4charts.Bullet());
                      bullet.width = 10;
                      bullet.height = 10;
                      bullet.horizontalCenter = "middle";
                      bullet.verticalCenter = "middle";

                      var rectangle = bullet.createChild(am4core.Rectangle);
                      rectangle.stroke = interfaceColors.getFor("background");
                      rectangle.strokeWidth = 2;
                      rectangle.width = 10;
                      rectangle.height = 10;

                      series.stroke = am4core.color("#3C8DBC"); 
                      break;
                    default:
                      var bullet = series.bullets.push(new am4charts.CircleBullet());
                      bullet.circle.stroke = interfaceColors.getFor("background");
                      bullet.circle.strokeWidth = 2;

                      series.stroke = am4core.color("#ADADAD"); 
                      break;
              }

          valueAxis.renderer.line.strokeOpacity = 1;
          valueAxis.renderer.line.strokeWidth = 2;
          valueAxis.renderer.line.stroke = series.stroke;
          valueAxis.renderer.labels.template.fill = series.stroke;
          valueAxis.renderer.opposite = opposite;
        }


        createAxisAndSeries("total", "Total", false, "circle");
       //createAxisAndSeries("android", "Android", true, "triangle");
        //createAxisAndSeries("total", "Total", true, "rectangle");

        // Add legend
        chart_urequest.legend = new am4charts.Legend();

        // Add cursor
        chart_urequest.cursor = new am4charts.XYCursor();
        chart_urequest.logo.disabled = true;





  })
  .catch(function (error) {
    // handle error
    console.log(error);
  })
  .then(function () {
    // always executed
  });




// chart_issues ------------------------------------------------------------------------------------------


  axios.post(url_api_data_chart_issues,{
    "device_token":"thrc_backend"
  },{
      headers:headers
  })
  .then(function (response) {
    // handle success
    //console.log(response.data);


    // Create chart instance
    var chart_issues = am4core.create("chart_issues", am4charts.PieChart);


    // Add label
    chart_issues.innerRadius = 100;
    var label = chart_issues.seriesContainer.createChild(am4core.Label);
    label.text = "";
    label.horizontalCenter = "small";
    label.verticalCenter = "small";
    label.fontSize = 20;

    // Add and configure Series
    var pieSeries = chart_issues.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "name";

    // chart_issues.data = [
    //                         { "name": "โรงพยาบาล", "value": 6.6 },
    //                         { "name": "ชุมชน", "value": 0.6 },
    //                         { "name": "ศูนย์พัฒนาเด็กเล็ก", "value": 23.2 },
    //                         { "name": "ศูนย์สุขภาพชุมชน", "value": 2.2 },
    //                         { "name": "สถานศึกษาอื่นๆ", "value": 4.5 },
    //                         { "name": "อาชีวะ", "value": 14.6 },
    //                         { "name": "มหาวิทยาลัย", "value": 9.3 }
    //                       ];

    chart_issues.data = response.data.val;


    //console.log(chart_issues.data);
    chart_issues.invalidateRawData();
    //console.log(chart_issues.data);
    //loop_chart_issues();
    chart_issues.logo.disabled = true;
    // let colorSet = new am4core.ColorSet();
    // colorSet.list = ["#67b7dc", "#fdd400", "#cd82ad", "#2f4074", "#448e4d","#84b761","#cc4748"].map(function(color) {
    //   return new am4core.color(color);
    // });
    // pieSeries.colors = colorSet;





  })
  .catch(function (error) {
    // handle error
    console.log(error);
  })
  .then(function () {
    // always executed
  });





// // chart_target ------------------------------------------------------------------------------------------

  axios.post(url_api_data_chart_target,{
    "device_token":"thrc_backend"
  },{
      headers:headers
  })
    .then(function (response) {
      // handle success
    ///console.log(response.data);

    // Create chart instance
    var chart_target = am4core.create("chart_target", am4charts.PieChart);

    // Add data
    chart_target.data =response.data.val;

    // chart_target.data = [
    //                     { "name": "แรงงานข้ามชาติ", "value": 6.6 },
    //                     { "name": "ประชาชนทั่วไป", "value": 0.6 },
    //                     { "name": "ผู้หญิง", "value": 23.2 },
    //                     { "name": "พระภิกษุ", "value": 2.2 },
    //                     { "name": "ผู้บริหารสสส.", "value": 4.5 },
    //                     { "name": "คณะกรรมการกองทุน", "value": 14.6 },
    //                     { "name": "วัยทำงาน", "value": 9.3 },
    //                     { "name": "มุสลิม", "value": 22.5 }
    //                   ];

    // Add label
    chart_target.innerRadius = 100;
    var label = chart_target.seriesContainer.createChild(am4core.Label);
    label.text = "";
    label.horizontalCenter = "small";
    label.verticalCenter = "small";
    label.fontSize = 40;

    // Add and configure Series
    var pieSeries = chart_target.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "name";

    chart_target.invalidateRawData();
    chart_target.logo.disabled = true;
    // let colorSet = new am4core.ColorSet();
    // colorSet.list = ["#fdd400", "#67b7dc"].map(function(color) {
    //     return new am4core.color(color);
    //   });
    // pieSeries.colors = colorSet;  


    })
    .catch(function (error) {
      // handle error
      console.log(error);
    })
    .then(function () {
      // always executed
    });


// // chart_setting ------------------------------------------------------------------------------------------

axios.post(url_api_data_chart_setting,{
    "device_token":"thrc_backend"
  },{
      headers:headers
  })
  .then(function (response) {
    // handle success
    //console.log(response.data);

    // Create chart instance
    var chart_setting = am4core.create("chart_setting", am4charts.PieChart);

    // Add data
    // chart_setting.data = [
    //                     { "name": "โรงเรียน", "value": 6.6 },
    //                     { "name": "โรงพยาบาล", "value": 0.6 },
    //                     { "name": "ชุมชน", "value": 23.2 },
    //                     { "name": "รพสต/สถานีอนามัย", "value": 2.2 },
    //                     { "name": "ศูนย์พัฒนาเด็กเล็ก", "value": 4.5 },
    //                     { "name": "สถานพยาบาลอื่นๆ", "value": 14.6 },
    //                     { "name": "องค์กร/หน่วยงานรัฐอื่นๆ", "value": 9.3 },
    //                     { "name": "อาชีวะ", "value": 22.5 }
    //                   ];

    chart_setting.data = response.data.val;


    // Add label
    chart_setting.innerRadius = 100;
    var label = chart_setting.seriesContainer.createChild(am4core.Label);
    label.text = "";
    label.horizontalCenter = "small";
    label.verticalCenter = "small";
    label.fontSize = 40;

    // Add and configure Series
    var pieSeries = chart_setting.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "name";

    chart_setting.invalidateRawData();
    chart_setting.logo.disabled = true;
    // let colorSet = new am4core.ColorSet();
    // colorSet.list = ["#fdd400", "#67b7dc"].map(function(color) {
    //     return new am4core.color(color);
    //   });
    // pieSeries.colors = colorSet;  



  })
  .catch(function (error) {
    // handle error
    console.log(error);
  })
  .then(function () {
    // always executed
  });




// // chart_statistics_api_by_organization ------------------------------------------------------------------------------------------

axios.post(url_api_data_chart_statistics_api_by_organization,{
    "device_token":"thrc_backend"
  },{
      headers:headers
  })
  .then(function (response) {
    // handle success
    //console.log(response.data);

    // Create chart instance
    var chart_setting = am4core.create("chart_statistics_api_by_organization", am4charts.PieChart);

    // Add data
    // chart_setting.data = [
    //                     { "name": "โรงเรียน", "value": 6.6 },
    //                     { "name": "โรงพยาบาล", "value": 0.6 },
    //                     { "name": "ชุมชน", "value": 23.2 },
    //                     { "name": "รพสต/สถานีอนามัย", "value": 2.2 },
    //                     { "name": "ศูนย์พัฒนาเด็กเล็ก", "value": 4.5 },
    //                     { "name": "สถานพยาบาลอื่นๆ", "value": 14.6 },
    //                     { "name": "องค์กร/หน่วยงานรัฐอื่นๆ", "value": 9.3 },
    //                     { "name": "อาชีวะ", "value": 22.5 }
    //                   ];

    chart_setting.data = response.data.val;


    // Add label
    chart_setting.innerRadius = 100;
    var label = chart_setting.seriesContainer.createChild(am4core.Label);
    label.text = "";
    label.horizontalCenter = "small";
    label.verticalCenter = "small";
    label.fontSize = 40;

    // Add and configure Series
    var pieSeries = chart_setting.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "name";

    chart_setting.invalidateRawData();
    chart_setting.logo.disabled = true;
    // let colorSet = new am4core.ColorSet();
    // colorSet.list = ["#fdd400", "#67b7dc"].map(function(color) {
    //     return new am4core.color(color);
    //   });
    // pieSeries.colors = colorSet;  



  })
  .catch(function (error) {
    // handle error
    console.log(error);
  })
  .then(function () {
    // always executed
  });





}); // end am4core.ready()

</script>

@section('js')
  
@show
</body>
</html>


