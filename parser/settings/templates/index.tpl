<h1 class="site_title">Einstellungen</h1>

<div class="main_box">
      <form action="index.php?module=settings" method="post">
      <div class="box_title">Dashboard Alert</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Titel:</div>
            <input type="text" name="dashboard_alert_title" value="{DASHBOARD_ALERT_TITLE}">
         </div>
         <div class="input_element">
            <div class="title">Nachricht:</div>
            <textarea name="dashboard_alert">{DASHBOARD_ALERT}</textarea>
            <div class="input_hint">Wird keine Nachricht angegeben, wird die Meldung nicht angezeigt</div>
         </div>
      </div>
      <div class="form-footer">
         <input type="submit" value="Speichern">
      </div>
   </form>
</div>