<? /*
<h1>Edit User: %%username%%</h1>
<form name='editUser' method='POST' action='%%serverPath%%users/save/'>
<input type='hidden' name='action' value='saveUserRecord' />
<input type='hidden' name='UID' value='%%UID%%' />
<table noborder>
  <tr>
    <td><b>School:</b></td>
    <td>
        [[:schools::select::default=%%school%%:]] 
        <select name='grade'>
         <option value='%%grade%%'>%%grade%%</option>
         <option value='Grade 1'>Grade 1</option>
         <option value='Grade 2'>Grade 2</option>
         <option value='Grade 3'>Grade 3</option>
         <option value='Grade 4'>Grade 4</option>
         <option value='Grade 5'>Grade 5</option>
         <option value='Grade 6'>Grade 6</option>
         <option value='Grade 7'>Grade 7</option>
         <option value='Grade 8'>Grade 8</option>
         <option value='Grade 9'>Grade 9</option>
         <option value='Grade 10'>Grade 10</option>
         <option value='Grade 11'>Grade 11</option>
         <option value='Grade 12'>Grade 12</option>
         <option value='1. Klasse'>1. Klasse</option>
         <option value='2. Klasse'>2. Klasse</option>
         <option value='3. Klasse'>3. Klasse</option>
         <option value='4. Klasse'>4. Klasse</option>
         <option value='5. Klasse'>5. Klasse</option>
         <option value='6. Klasse'>6. Klasse</option>
         <option value='7. Klasse'>7. Klasse</option>
         <option value='8. Klasse'>8. Klasse</option>
         <option value='9. Klasse'>9. Klasse</option>
         <option value='10. Klasse'>10. Klasse</option>
         <option value='11. Klasse'>11. Klasse</option>
         <option value='12. Klasse'>12. Klasse</option>
         <option value='13. Klasse'>13. Klasse</option>
         <option value='Std. 1'>Std. 1</option>
         <option value='Std. 2'>Std. 2</option>
         <option value='Std. 3'>Std. 3</option>
         <option value='Std. 4'>Std. 4</option>
         <option value='Std. 5'>Std. 5</option>
         <option value='Std. 6'>Std. 6</option>
         <option value='Std. 7'>Std. 7</option>
         <option value='Std. 8'>Std. 8</option>
         <option value='Std. 9'>Std. 9</option>
         <option value='Std. 10'>Std. 10</option>
         <option value='Std. 11'>Std. 11</option>
         <option value='Std. 12'>Std. 12</option>
        </select>
    </td>
  </tr>
  <tr>
    <td><b>Forename:</b></td>
    <td><input type='text' name='firstname' value='%%firstname%%' /></td>
  </tr>
  <tr>
    <td><b>Surname:</b></td>
    <td><input type='text' name='surname' value='%%surname%%' /></td>
  </tr>
  <tr>
    <td><b>Username:</b></td>
    <td><input type='text' name='username' value='%%username%%' /></td>
  </tr>
  <tr>
    <td><b>Language:</b></td>
    <td><input type='text' name='lang' value='%%lang%%' /></td>
  </tr>
  <tr>
    <td><b>Group:</b></td>
    <td>[[:users::selectgroup::default=%%role%%:]]</td>
  </tr>
  <tr>
    <td></td>
    <td><input type='submit' value='Save' /></td>
  </tr>
</table>
</form>
<small>UID: %%UID%% recordAlias: %%alias%%</small>
*/ ?>
