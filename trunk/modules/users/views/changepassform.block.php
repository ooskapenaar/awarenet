<? /*

<div class='block'>

[[:theme::navtitlebox::label=Change Password::toggle=divChangePassForm:]]
<div id='divChangePassForm'>
<form name='changeUserPass' method='POST' action='%%serverPath%%users/changepassword/'>
<input type='hidden' name='action' value='changeUserPass' />
<input type='hidden' name='UID' value='%%UID%%' />
<table noborder width='100%'>
  <tr>
    <td><b>Current Password:</b></td>
    <td><b><input type='password' name='pwdCurrent' style='width: 100%;' /></b></td>
  </tr>
  <tr>
    <td><b>New Password:</b></td>
    <td><b><input type='password' name='pwdNew' style='width: 100%;' /></b></td>
  </tr>
  <tr>
    <td><b>Confirm New Password:</b></td>
    <td><b><input type='password' name='pwdConfirm'  style='width: 100%;' /></b></td>
  </tr>
  <tr>
    <td></td>
    <td><b><input type='submit' value='Change Password' /></b></td>
  </tr>
</table>
</form>
</div>
<div class='foot'></div>
</div>
<br/>

*/ ?>