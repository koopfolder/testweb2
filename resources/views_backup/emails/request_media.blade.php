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
      <td colspan="2" align="center"><img src="{{ asset('themes/thrc/images/admin-login-logo.png')}}" /></td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td height="20px">Name</td>
      <td height="20px">{{ $name }}</td>
    </tr>
    <tr>
    	<td height="20px">Phone</td>
    	<td height="20px">{{ $phone }}</td>
    </tr>
    <tr>
      <td height="20px">Email</td>
      <td height="20px">{{ $email }}</td>
    </tr>
    <tr>
    	<td height="20px">Message</td>
    	<td height="20px">{{ $description }}</td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td colspan="2" height="10px"></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
            
            <div style="color:#2d3691; font-size:15px; font-weight:bold; margin-bottom:5px;">
                {{ (new App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('site') }}
            </div>
            <div style="color:#2c2c2c; font-size:12px;">
                {{ (new App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('address') }}
            </div>
      </td>
    </tr>

  </table>
</body>
<html>
