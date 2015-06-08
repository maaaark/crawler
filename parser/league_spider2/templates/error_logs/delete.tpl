<div class="right_options">
    <button onclick="self.location.href = 'index.php?parser=league_spider2&logger&error_logs';">Zur Error-Log Liste</button>
    <button onclick="self.location.href = 'index.php?parser=league_spider2&logger&error_logs&log_name={NAME}';">Zum Error-Log</button>
</div>
<h1 class="site_title">League Spider 2 - Error Log L&ouml;schen <span>> {DATE} - {TIME} Uhr</span></h1>

Den Error-Log <b>{NAME}</b> wirklich l&ouml;schen?

<div style="margin-top: 20px;">
    <form action="index.php?parser=league_spider2&logger&error_logs&log_name={NAME}&delete" method="post">
        <input type="hidden" name="log_name" value="{NAME}">
        <input type="submit" value="L&ouml;schen"> <span style="padding-left: 15px;"><a href="index.php?parser=league_spider2&logger&error_logs">Abbrechen</a></span>
    </form>
</div>