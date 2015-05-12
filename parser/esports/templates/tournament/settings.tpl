<div class="right_options">
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}&tournament={ID}'">Spielplan anzeigen</button>
   <button onclick="self.location.href='index.php?parser=esports&league={LEAGUE_ID_INTERN}'">Zur Liga &Uuml;bersicht</button>
</div>
<h1 class="site_title">Turnier Einstellungen <span>> {NAME}</span></h1>

{if SHOW_STANDINGS_ON_FRONT==0}
	Die Tabelle dieses Turniers wird momentan <b>nicht</b> auf der Esports-Startseite angezeigt.
{else}
	Die Tabelle dieses Turniers <b>wird</b> momentan auf der Esports-Startseite <b>angezeigt</b>.
{/if}

<div class="main_box">
	<form action="index.php?parser=esports&league={LEAGUE_ID_INTERN}&tournament={ID}&settings" method="post">
		<div class="box_title">Tabelle anzeigen</div>
		<div class="box_content">
			<div class="input_element">
	            <div class="title">Soll die Tabelle auf der Startseite angezeigt werden?</div>
	            <select name="show_table_on_front">
	        		{if SHOW_STANDINGS_ON_FRONT==1}
	            		<option value="0">Nein</option>
	            		<option value="1" selected>Ja</option>
	        		{else}
	        			<option value="0" selected>Nein</option>
	            		<option value="1">Ja</option>
	        		{/if}
	        	</select>
	         </div>
		</div>
		<div class="form-footer">
			<input type="submit" value="Speichern">
		</div>
	</form>
</div>