<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
  body{
    margin:0 auto;
  }
  @import url('https://fonts.googleapis.com/css?family=Roboto');
</style>
<body>
  <table border="0" cellpadding="0" cellpadding="0" width="508px" style="font-family: 'Roboto', sans-serif;">
    <tr>
      <td colspan="2" align="center"><img src="{{ asset('themes/pylon/images/logo-pylon.png')}}" /></td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><img src="{{ asset('themes/pylon/images/line-mail.jpg')}}" /></td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td height="20px">Position Applied For</td>
      <td height="20px">{{ $position_id }}</td>
    </tr>
    <tr>
    	<td height="20px">Name - Surname</td>
    	<td height="20px">{{ $prefix." ".$name." ".$surname }}</td>
    </tr>
    <tr>
    	<td height="20px">Sex</td>
    	<td height="20px">{{ $sex }}</td>
    </tr>
    <tr>
      <td height="20px">Birthdate</td>
      <td height="20px">{{ $birthdate }}</td>
    </tr>
    <tr>
      <td height="20px">Nationality</td>
      <td height="20px">{{ $birthdate }}</td>
    </tr>
    <tr>
      <td height="20px">Email Address</td>
      <td height="20px">{{ $email }}</td>
    </tr>
    <tr>
      <td height="20px">Phone Number</td>
      <td height="20px">{{ $phone }}</td>
    </tr>
    <tr>
      <td height="20px">Identification</td>
      <td height="20px">{{ $id_type.":".$id_no }}</td>
    </tr>
    <tr>
      <td height="20px">Education</td>
      <td height="20px">{{ $education }}</td>
    </tr>
    <tr>
      <td height="20px">Educational Institutions</td>
      <td height="20px">{{ $educational_institutions }}</td>
    </tr>
    <tr>
      <td height="20px">Other</td>
      <td height="20px">{{ $educational_institutions_other }}</td>
    </tr>

    <tr>
      <td height="20px">Field of Study/Major</td>
      <td height="20px">{{ $field_of_study_major }}</td>
    </tr>

    <tr>
      <td height="20px">Work Experience (1)</td>
      <td height="20px">Position:{{ $position_1 }}</td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px">Company:{{ $company_1 }}</td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px">Duration of Service : {{ ($duration_of_service_year_1 !='' ? $duration_of_service_year_1.' Year(s)':'') }} {{ ($duration_of_service_month_1 !='' ? $duration_of_service_month_1.' Month(s)':'') }}</td>
    </tr>
    <tr>
      <td height="20px">Work Experience (2)</td>
      <td height="20px">Position:{{ $position_2 }}</td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px">Company:{{ $company_2 }}</td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px">Duration of Service : {{ ($duration_of_service_year_2 !='' ? $duration_of_service_year_2.' Year(s)':'') }} {{ ($duration_of_service_month_2 !='' ? $duration_of_service_month_2.' Month(s)':'') }}</td>
    </tr>
    <tr>
      <td height="20px">Work Experience (3)</td>
      <td height="20px">Position:{{ $position_3 }}</td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px">Company:{{ $company_3 }}</td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px">Duration of Service : {{ ($duration_of_service_year_3 !='' ? $duration_of_service_year_3.' Year(s)':'') }} {{ ($duration_of_service_month_3 !='' ? $duration_of_service_month_3.' Month(s)':'') }}</td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><img src="{{ asset('themes/pylon/images/line-mail.jpg')}}" /></td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
            
            <div style="color:#2d3691; font-size:15px; font-weight:bold; margin-bottom:5px;">
                {{ (new App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('company_name_th') }}
            </div>
            <div style="color:#2c2c2c; font-size:12px;">
                {{ (new App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('address_head_office_th') }}
            </div>
      </td>
    </tr>

  </table>
</body>
<html>
