<div class="right_options">
   <button onclick="self.location.href='index.php?parser=esports&amp;teams_overview'">Zur&uuml;ck</button>
</div>
<h1 class="site_title">Team bearbeiten <span>> {NAME}</h1>

<form action="index.php?parser=esports&teams_overview&edit={ID}" method="post">
   <div class="main_box">
      <div class="box_title">Team bearbeiten</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Name:</div>
            <input type="text" name="name" value="{NAME}">
            <div class="input_hint">Der ausgeschriebene Name des Teams (wird auf der Webseite angezeigt)</div>
         </div>
         
         <div class="input_element">
            <div class="title">Region:</div>
            <input type="text" name="region" value="{REGION}">
            <div class="input_hint">Regionsk&uuml;rzel (z.B. "EUW")</div>
         </div>
         
         <div class="input_element">
            <div class="title">Riot Team-ID:</div>
            <input type="text" name="team_id_riot" value="{TEAM_ID_RIOT}">
            <div class="input_hint">Bei 0 k&ouml;nnen die Crawler in der Regel nicht mit diesem Team arbeiten</div>
         </div>
      </div>
      <div class="form-footer">
         <input type="hidden" value="{ID}" name="id">
         <input type="submit" value="Speichern">
      </div>
   </div>
</form>