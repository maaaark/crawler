<h1 class="site_title">{PARSER_NAME} <span>> Einstellungen</span></h1>

<form action="index.php?parser=league_spider&settings" method="post">
   <div class="main_box">
      <div class="box_title">League Spider Einstellungen &auml;ndern</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Patch-Version:</div>
            <input type="text" name="game_version" value="{SETTINGS_GAME_VERSION}">
            <div class="input_hint">Patchversion von der Spiele geladen werden. Zum Beispiel 5.6</div>
         </div>
         
         <div class="input_element">
            <div class="title">Summoner Limitierung:</div>
            <input type="text" name="summoner_limit" value="{SETTINGS_SUMMONER_LIMIT}">
            <div class="input_hint">0 = kein Limit</div>
         </div>
         
         <div class="input_element">
            <div class="title">Summoner Parse Limitierung:</div>
            <input type="text" name="summoner_parse_limit" value="{SETTINGS_SUMMONER_PARSE_LIMIT}">
            <div class="input_hint">0 = kein Limit (wie viele Summoner aus der Features Game List geladen werden)</div>
         </div>
         
         <div class="input_element">
            <div class="title">Wartezeit nach Summoner aktualisierung:</div>
            <input type="text" name="summoner_update_waiting" value="{SETTINGS_SUMMONER_UPDATE_WAITING}">
            <div class="input_hint">Wartezeit bis dieser Summoner nach einer Aktualisierung wieder aktualisiert wird.</div>
         </div>
         
         <div class="input_element">
            <div class="title">Erlaubte Ligen zum crawlen:</div>
            <input type="text" name="allowed_leagues" value="{SETTINGS_ALLOWED_LEAGUES}">
            <div class="input_hint">Kommagetrennt die Namen der zu durchsuchenden Ligen angeben.</div>
         </div>
         
         <div class="input_element">
            <div class="title">Cronjob Interval:</div>
            <input type="text" name="cronjob_interval" value="{SETTINGS_CRONJOB_INTERVAL}">
            <div class="input_hint">Alle X Minuten League Spider aufrufen (Standard ist 3)</div>
         </div>
         
         <h2>SID-Mode</h2>
         <div class="input_element">
            <div class="title">SID-Modus verwenden:</div>
            <label><input type="radio" name="manual_sid_mode" value="true" {if SETTINGS_MANUAL_SID_MODE==TRUE}checked{/if}> Ja</label>
            <label><input type="radio" name="manual_sid_mode" value="false"{if SETTINGS_MANUAL_SID_MODE==TRUE}{else}checked{/if}> Nein</label>
            <div class="input_hint">Sollen die Bekannten Summoner-IDs durchlaufen werden anstelle der Featured-Games List?</div>
         </div>
         
         <div class="input_element">
            <div class="title">SID-Modus Summoner pro Aufruf:</div>
            <input type="text" name="manual_sid_mode_count" value="{SETTINGS_MANUAL_SID_MODE_COUNT}">
            <div class="input_hint">Wie viele Summoners sollen pro Aufruf analysiert werden?</div>
         </div>
      </div>
      <div class="form-footer">
         <input type="hidden" name="change_league_spider_settings" value="true">
         <input type="submit" value="Einstellungen speichern">
      </div>
   </div>
</form>