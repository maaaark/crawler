<div class="right_options">
   <button onClick="self.location.href='index.php?account_settings'">Account Einstellungen</button>
</div>

<h1 class="site_title">Dashboard</h1>

{if DASHBOARD_ALERT}
   <div class="dashboard_alert">
      <div class="dashboard_alert_title">{DASHBOARD_ALERT_TITLE}</div>
      <div class="dashboard_alert_content">{DASHBOARD_ALERT}</div>
   </div>
{/if}

Eingeloggt als {LOGGED_USERNAME}

<form action="index.php" method="post">
   <div class="main_box">
      <div class="box_title">
         <div class="options"><input type="submit" value="&Auml;nderungen speichern"></div>
         Dashboard Nachricht
      </div>
      <div class="box_content">
         <textarea name="dashboard_message">{DASHBOARD_MESSAGE}</textarea>
      </div>
   </div>
</form>