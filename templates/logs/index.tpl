<h1 class="site_title">Logs</h1>

<div class="main_box">
	<div class="box_title">Cronjob-Log</div>
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