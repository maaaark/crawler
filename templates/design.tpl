<!DOCTYPE html>
<html>
<head>
   <meta content="width=device-width,height=device-height, user-scalable=no" name="viewport">
   <link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/font-awesome.css">
   <link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/design.css">
   <link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/inputs.css">
   <link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/main_navi.css">
   <link rel="stylesheet" type="text/css" href="{DOMAIN}/assets/css/styles.css">

	<script type="text/javascript" src="{DOMAIN}/assets/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="{DOMAIN}/assets/js/jquery.tablesorter.js"></script>
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
   <div class="mobile_top_bar"><div class="logo"></div><div class="mobile_navi_icon"></div></div>
   <div class="main_navigation" id="main_navigation">
      <div style="margin-top: 10px;">
         <div class="main_title">Account:</div>
         <a href="index.php">
            <div class="navi_el{if IS_INDEX} active{/if}">
               <div class="title"><i class="fa fa-home"></i>Dashboard</div>
            </div>
         </a>
         <a href="index.php?logout">
            <div class="navi_el">
               <div class="title"><i class="fa fa-sign-out"></i>Ausloggen</div>
            </div>
         </a>

         <div class="main_title">Parser:</div>
         {NAVIGATION_PARSER}
         
         <div class="main_title">Module:</div>
         {NAVIGATION_MODULES}

         <div class="main_title">Logs:</div>
         <a href="index.php?logs">
            <div class="navi_el{if REQUEST.LOGS} active{/if}">
               <div class="title"><i class="fa fa-file-text"></i>Cronjob-Log</div>
            </div>
         </a>
      </div>
   </div>
   <div class="content_holder" id="content_holder">
      <div class="page_title_bar"><div class="logo"></div><span>{SITE_TITLE}</span></div>
      <div class="content">
         {INSTANT_MESSAGES}
         {CONTENT}
      </div>
   </div>
</body>
</html>