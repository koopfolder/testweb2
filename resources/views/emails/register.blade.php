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
      <td height="20px">ยินดีต้อนรับสู่ THRC</td>
      <td height="20px"></td>
    </tr>
    <tr>
    	<td height="20px" colspan="2">คุณสามารถเข้าสู่ระบบในเว็บของเราได้ทันทีโดยการคลิ๊กที่เมนู เข้าสู่ระบบ ซึ่งเมนูนี้จะอยู่ด้านบนของทุกๆ หน้า, ให้ทำการเข้าสู่ระบบโดยใช้ชื่อผู้ใช้และรหัสผ่านของคุณ</td>
    </tr>
    <tr>
    	<td height="20px">ใช้ข้อมูลดังต่อไปนี้ในการเข้าสู่ระบบ</td>
    	<td height="20px"></td>
    </tr>
    <tr>
      <td height="20px">ชื่อผู้ใช้</td>
      <td height="20px">{{ $email }}</td>
    </tr>
    <tr>
      <td height="20px">รหัสผ่าน</td>
      <td height="20px">{{ $password_old }}</td>
    </tr>
    <tr>
      <td height="20px">เปิดใช้งานบัญชีคลิกที่นี่</td>
      <td height="20px">{{ $activate_token_url }}</td>
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
                {{ (new App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('organization_name_th') }}
            </div>
            <div style="color:#2c2c2c; font-size:12px;">
                {{ (new App\Modules\Setting\Http\Controllers\FrontController)->getKeyAndReturnValue('address') }}
            </div>
      </td>
    </tr>

  </table>
</body>
<html>
