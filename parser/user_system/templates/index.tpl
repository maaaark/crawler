<div class="right_options">
   <button onclick="self.location.href='index.php?module=user_system&add_edit&add'">Neuen Benutzer anlegen</button>
</div>
<h1 class="site_title">Benutzersystem</h1>

<table class="table">
   <thead>
      <th>ID</th>
      <th>Benutzername</th>
      <th>Letzte Aktion</th>
      <th>Optionen</th>
   </thead>
   <tbody>
      {USERS_LIST}
   </tbody>
</table>