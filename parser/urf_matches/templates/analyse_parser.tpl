<h1 class="site_title">{PARSER_NAME} <span>> MatchIDs analysieren</span></h1>

<div style="margin-bottom: 15px;">
   <button id="parser_stop">Stoppen</button>
   <span style="padding-left: 25px;color: rgba(0,0,0,0.5);">
      <span id="parser_status_msg">Der Parser <b>l&auml;uft</b> aktuell.</span>
      <span style="color: rgba(0,0,0,0.4);">(Schlie&szlig;en des Browserfensters beendet den Crawler)</span>
   </span>
</div>

<div class="main_box">
   <div class="box_title">Letzte Meldung vom Parser:</div>
   <div class="box_content">
      <div id="parser_log"><div class="message">Parsen wird gestartet ...</div></div>
      N&auml;chster Parser-Request in <span id="next_request_secs"></span> Sekunden.
   </div>
</div>

<script>
   var speed  = 10000;
   var status = "running";
   $("#next_request_secs").html(speed / 1000);
   function runParser(){
      $.get("index.php", { parser: "urf_matches", analyse_parser: "true", run: "true" }).done(function(data){
         $("#parser_log").html(data);
         $("#next_request_secs").html(speed / 1000);
      });
   }
   
   function updateParserSeconds(){
      if(status == "running"){
         countdown = $("#next_request_secs");
         val = parseInt(countdown.html());
         if(val > 0){
            countdown.html(val - 1);
         } else {
            countdown.html("0");
         }
      }
   }
   var interval = window.setInterval(runParser, speed);
   var inerval_seconds = window.setInterval(updateParserSeconds, 1000);
   
   $(document).ready(function(){
      $("#parser_stop").click(function(){
         if(status == "running"){
            status = "stop";
            $(this).html("Starten");
            clearInterval(interval);
            $("#parser_status_msg").html("Der Parser wurde <b>gestoppt</b>.");
         } else {
            status = "running";
            $(this).html("Stoppen");
            interval = window.setInterval(runParser, speed);
            $("#parser_status_msg").html("Der Parser <b>l&auml;uft</b> aktuell.");
         }
      });
   });
   
   window.onbeforeunload = function (e){
      if(status == "running"){
         message = "Der Crawler ist noch aktiv. Der Crawler wird beim Verlassen der Seite beendet.",
         e = e || window.event;
         // For IE and Firefox
         if(e){
            e.returnValue = message;
         }
         // For Safari
         return message;
      }
   };
</script>