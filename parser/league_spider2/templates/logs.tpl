<div class="right_options">
    <button onclick="self.location.href = 'index.php?parser=league_spider2&logger&error_logs';">Error-Logs</button>
</div>
<h1 class="site_title">League Spider 2.0 <span>> Logs</span></h1>

<div class="main_box">
	<div class="box_title">
      <div class="options">
         <button onclick="self.location.href='index.php?parser=league_spider2&logger&delete_success_log'">Log leeren</button>
      </div>
      Success-Log 
   </div>
	<div class="box_content"><textarea style="height: 400px;">{SUCCESS_LOG}</textarea></div>
</div>

<script>
$(document).ready(function(){
	$("textarea").each(function(){
		txtArea = $(this);
		if(txtArea.length){
        	$(this).scrollTop(txtArea[0].scrollHeight - txtArea.height());
		}
	});
});
</script>