<!DOCTYPE html>
<html>
<head>
   <meta content="width=device-width,height=device-height, user-scalable=no" name="viewport">
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/design.css">
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/inputs.css">
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/main_navi.css">
	<link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/styles.css">
	
	<script type="text/javascript" src="{DOMAIN}/assets/js/jquery-1.11.2.min.js"></script>
	<script>
      $(document).ready(function(){
         $(".main_navigation .navi_el.subnavi .title").click(function(){
            parent = $(this).parent();
            if(parent.hasClass("open")){
               parent.removeClass("open");
            } else {
               parent.addClass("open");
            }
         });
         
         $(".mobile_top_bar .mobile_navi_icon").click(function(){
            main_navi = $("#main_navigation");
            if(main_navi.hasClass("opened")){
               main_navi.removeClass("opened");
            } else {
               main_navi.addClass("opened");
            }
         });
         
         $("#content_holder").click(function(){
            console.log("asd");
            main_navi = $("#main_navigation");
            if(main_navi.hasClass("opened")){
               main_navi.removeClass("opened");
            }
         });
      });
	</script>
</head>
<body>
<div class="mobile_top_bar"><div class="mobile_navi_icon"></div></div>

<div class="main_navigation" id="main_navigation">
	Hier erscheint die Seiten-Navi
	<div style="margin-top: 10px;">
      {NAVIGATION_PARSER}
	</div>
</div>
<div class="content_holder" id="content_holder">
	<div class="page_title_bar">{SITE_TITLE}</div>
	<div class="content">
      {INSTANT_MESSAGES}
      {CONTENT}
   </div>
</div>
</body>
</html>