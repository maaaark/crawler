<h1 class="site_title">{PARSER_NAME} <span>> Einstellungen</span></h1>

<form action="index.php?parser=league_spider2&settings" method="post">
   <div class="main_box">
      <div class="box_title">League Spider Einstellungen &auml;ndern</div>
      <div class="box_content">
         <div class="input_element">
            <div class="title">Patch-Version:</div>
            <input type="text" name="game_version" value="{SETTINGS_GAME_VERSION}">
            <div class="input_hint">Patchversion von der Spiele geladen werden. Zum Beispiel 5.6</div>
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
            <div class="title">Neue Summoner speichern:</div>
            <select name="save_new_summoner">
               <option value="true" {if SETTINGS_SAVE_NEW_SUMMONER}selected{/if}>Ja</option>
               <option value="false" {if SETTINGS_SAVE_NEW_SUMMONER}{else}selected{/if}>Nein</option>
            </select>
            <div class="input_hint">Sollen neue Summoner-IDs gespeichert werden, die man über die einzelnen Matche und Mathhistories erhält.</div>
         </div>
         
         <div class="input_element">
            <div class="title">Spider Modus:</div>
            <select name="league_spider_mode">
               <option value="normal" {if SETTINGS_LEAGUE_SPIDER_MODE==normal}selected{/if}>Normal Mode</option>
               <option value="queue" {if SETTINGS_LEAGUE_SPIDER_MODE==queue}selected{/if}>Queue Mode</option>
            </select>
            <div class="input_hint">Der zu verwendene Modus zum Crawlen der Ligen.</div>
         </div>
      
         <h2>Normal Mode</h2>
         <div class="input_element">
            <div class="title">Summoner Limitierung:</div>
            <input type="text" name="summoner_limit" value="{SETTINGS_SUMMONER_LIMIT}">
            <div class="input_hint">0 = kein Limit</div>
         </div>
      
         <h2>Queue Mode</h2>
         <div class="input_element">
            <div class="title">Matches Limit:</div>
            <input type="text" name="queue_matches_limit" value="{SETTINGS_QUEUE_MATCHES_LIMIT}">
            <div class="input_hint">Anzahl der Matches die der Queue Mode auf einmal aktualisiert</div>
         </div>
         
         <div class="input_element">
            <div class="title">Summoner Limit:</div>
            <input type="text" name="queue_summoner_limit" value="{SETTINGS_QUEUE_SUMMONER_LIMIT}">
            <div class="input_hint">Anzahl der Summoner-Histories die aktualisiert werden wenn die Queue leer ist</div>
         </div>
      </div>
      <div class="form-footer">
         <input type="hidden" name="change_league_spider_settings" value="true">
         <input type="submit" value="Einstellungen speichern">
      </div>
   </div>
</form>