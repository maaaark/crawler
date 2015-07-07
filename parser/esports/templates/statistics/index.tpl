<h1 class="site_title">Esports-Statistiken</h1>

<div style="margin-top: 10px;">
    {LEAGUES}
    <div style="clear: both;"></div>
</div>

<script>
    $(document).ready(function(){
        $(".btn_tournament_dropdown").click(function(){
            var league_id = $(this).attr("data-league");
            $.post("index.php?parser=esports&statistics&load_tournaments_tournaments", {"league": $(this).attr("data-league")}).done(function(data){
                html  = '<form action="index.php" method="get"><input type="hidden" name="parser" value="esports"><input type="hidden" name="statistics" value="true">';
                html += '<div style="padding-top: 15px;"><select name="tournament">'+data+'</select></div>';
                html += '<div style="padding-top: 3px;"><button style="width:100%;padding-left:0px;padding-right:0px;">Anzeigen</button></div>';
                html += '</form>';
                $("#league_ajax_holder_"+league_id).html(html);
            });
        });
    });
</script>