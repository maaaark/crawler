<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/design.css">
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/inputs.css">
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/login.css">
   <link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/styles.css">
	
	<script type="text/javascript" src="{DOMAIN}/assets/js/jquery-1.11.2.min.js"></script>
</head>
<body>
   <div class="login_box" id="login_box">
      <div class="login_title">Neuen Admin erstellen</div>
      
      <form action="?setAdmin" method="post">
      <div class="login_content">
         {INSTANT_MESSAGES}
         <div class="input-full-form">
            <div class="input-label">Benutzername:</div>
            <input type="text" name="username">
         </div>
         
         <div class="input-full-form">
            <div class="input-label">Passwort:</div>
            <input type="password" name="password" class="input-full">
         </div>
         
         <div class="input-full-form">
            <div class="input-label">Passwort wiederholen:</div>
            <input type="password" name="password2" class="input-full">
         </div>
      </div>
      <div class="form-footer">
         <a href="{DOMAIN}">Abbrechen</a> <button>Erstellen</button>
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
</body>
</html>