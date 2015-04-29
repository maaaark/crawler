<tr{if TOURNAMENT_ID==0} style="opacity:0.5;"{/if}>
	<td style="text-align:center;color:rgba(0,0,0,0.6);">{LEAGUE_ID}</td>
	<td>
		{LABEL}
		<div style="font-size:12px;color:rgba(0,0,0,0.5);">{SHORT_NAME}</div>
	</td>
	<td>
		<button onclick="self.location.href='index.php?parser=esports&league={ID}'">Turniere anzeigen</button>
	</td>
</tr>