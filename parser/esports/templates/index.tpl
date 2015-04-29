<div class="right_options">
   <!--<button onclick="self.location.href='index.php?parser=esports&updateTournamentIDs'">Tournament IDs aktualisieren</button>
   <button onclick="self.location.href='index.php?parser=esports&loadteamids'">Team IDs aktualisieren</button>-->
   <button onclick="self.location.href='index.php?parser=esports&loadleagues'">Ligen aktualisieren</button>
</div>

<h1 class="site_title">Esports-Parser</h1>

<table class="table">
	<thead>
		<th>ID</th>
		<th>Name</th>
		<th>Optionen</th>
	</thead>
	<tbody>
		{LEAGUES_LIST}
    </tbody>
</table>