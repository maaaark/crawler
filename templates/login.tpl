<div class="login_box" id="login_box">
   <div class="login_title">Einloggen</div>
   <form action="index.php" method="post">
      {INSTANT_MESSAGES}
      <div class="login_content">
         <div class="input-full-form">
            <div class="input-label">Benutzername:</div>
            <input type="text" name="username">
         </div>
         
         <div class="input-full-form">
            <div class="input-label">Passwort:</div>
            <input type="password" name="password" class="input-full">
         </div>
      </div>
      <div class="form-footer">
         <input type="submit" value="Einloggen">
      </div>
   </form>
</div>
<script>
   $(document).ready(function(){
      login_box = $("#login_box");
      login_box.css("opacity", "0");
      login_box.css("margin-top", "-50px");
      
      login_box.show();
      login_box.animate({"opacity": "1", "margin-top": "0px"}, 1000, "swing");
   });
</script>