<div class="right_options">
   <button onclick="self.location.href='index.php?module=user_system'">Zur&uuml;ck</button>
</div>
<h1 class="site_title">Benutzer bearbeiten <span>> {USERNAME}</span></h1>

<form action="index.php?module=user_system&add_edit&edit={ID}" method="post">
   <div class="main_box">
      <div class="box_title">Benutzerdaten &auml;ndern</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Benutzername:</div>
            <input type="text" name="username" value="{USERNAME}">
         </div>
         <div class="input_element">
            <div class="title">Rollen:</div>
            <input type="text" name="roles" value="{ROLES_TRANSFORM}">
         </div>
         
         <div style="margin-top: 25px;">
            <div class="input_element">
               <div class="title">Neues Passwort:</div>
               <input type="password" name="password">
            </div>
            
            <div class="input_element">
               <div class="title">Neues Passwort wiederholen:</div>
               <input type="password" name="password2">
            </div>
         </div>
      </div>
      <div class="form-footer">
         <input type="hidden" value="{ID}" name="id">
         <input type="submit" value="Speichern">
      </div>
   </div>
</form>