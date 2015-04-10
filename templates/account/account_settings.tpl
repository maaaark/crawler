<div class="right_options">
   <button onClick="self.location.href='index.php'">Zur&uuml;ck</button>
</div>

<h1 class="site_title">Account Einstellungen</h1>

<form action="index.php?account_settings" method="post">
   <div class="main_box">
      <div class="box_title">Passwort &auml;ndern</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Neues Passwort:</div>
            <input type="password" name="password1">
         </div>
         
         <div class="input_element">
            <div class="title">Neues Passwort wiederholen:</div>
            <input type="password" name="password2">
         </div>
         
         <div class="input_element" style="margin-top: 20px;">
            <div class="title">Aktuelles Passwort</div>
            <input type="password" name="current_pw">
         </div>
      </div>
      <div class="form-footer">
         <a href="index.php">Abbrechen</a> <input type="submit" value="Speichern">
      </div>
   </div>
</form>