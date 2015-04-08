<h1 class="site_title">Logs</h1>

<div class="main_box">
	<div class="box_title">
      <div class="options">
         <button onclick="self.location.href='index.php?logs&delete_cron_log'">Log leeren</button>
      </div>
      Cronjob-Log
   </div>
	<div class="box_content"><textarea>{CRONJOB_LOG}</textarea></div>
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