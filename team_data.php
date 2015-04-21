<?php

if(isset($_GET["loadTeamData"])):
   set_time_limit(0);

   $leagues    = file_get_contents("http://na.lolesports.com:80/api/league.json?parameters%5Bmethod%5D=all");
   $leagues    = json_decode($leagues, true);
   $team_array = array();

   foreach($leagues["leagues"] as $league){
      foreach($league["leagueTournaments"] as $tournament){
         $tournament_json = file_get_contents("http://na.lolesports.com:80/api/tournament/".trim($tournament).".json");
         $tournament_json = json_decode($tournament_json, true);
         if(isset($tournament_json["contestants"])){
            foreach($tournament_json["contestants"] as $team){
               if(isset($team["acronym"]) && trim($team["acronym"]) != ""){
                  $team_array[$team["acronym"]] = array("id" => $team["id"], "name" => $team["name"]);
               }
            }
         }
      }
   }

   ksort($team_array);
   file_put_contents("team_data.txt", json_encode($team_array));
   echo "geladen";
else:
   $data = file_get_contents("team_data.txt");
   $data = json_decode($data, true);
?>

<!DOCTYPE html>
<html>
<head>
   <meta content="width=device-width,height=device-height, user-scalable=no" name="viewport">
   <title>Team-Liste</title>
   <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
   <link rel="stylesheet" type="text/css" href="assets/css/design.css">
   <link rel="stylesheet" type="text/css" href="assets/css/inputs.css">
   <link rel="stylesheet" type="text/css" href="assets/css/main_navi.css">
   <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
	
	<style>
      body {
         overflow: auto;
      }
      
      .table_main_holder {
         width: 80%;
         margin: auto;
      }
      
      @media(max-width: 1200px){
         .table_main_holder {
            width: 95%;
         }
      }
	</style>
</head>
<body>
   <div class="table_main_holder">
      <div class="main_box">
         <div class="box_title">Liste der Teams:</div>
         <div class="box_content">
            <table id="teams_table" class="table">
               <thead>
                  <th>Team Key</th>
                  <th>Name</th>
                  <th>Riot Team-ID</th>
               </thead>
               <tbody>
                  <?php foreach($data as $team_key => $team): ?>
                     <tr>
                        <td><?php echo $team_key; ?></td>
                        <td><?php echo $team["name"]; ?></td>
                        <td><?php echo $team["id"]; ?></td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</body>
</html>

<?php
endif;
?>