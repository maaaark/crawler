<div class="right_options">
   <button onclick="self.location.href='index.php?module=user_system'">Zur&uuml;ck</button>
</div>
<h1 class="site_title">Neuen Benutzer anlegen</h1>

<form action="index.php?module=user_system&add_edit&add" method="post">
   <div class="main_box">
      <div class="box_title">Benutzerdaten festlegen:</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Benutzername:</div>
            <input type="text" name="username">
         </div>
         
         <div class="input_element">
            <div class="title">Passwort:</div>
            <input type="password" name="password">
         </div>
         
         <div class="input_element">
            <div class="title">Passwort wiederholen:</div>
            <input type="password" name="password2">
         </div>
         
         <div class="input_element">
            <div class="title">Rollen:</div>
            <input type="text" name="roles" value="NORMAL_USER">
         </div>
      </div>
      
      <div class="form-footer">
         <input type="submit" value="Speichern">
      </div>
   </div>
</form>