<style>
.league_image_box {
   float: left;
   font-size: 12px;
   color: rgba(0,0,0,0.5);
   max-width: 200px;
   text-align: center;
   margin-right: 20px;
}

.league_image {
   width: 100%;
   margin-bottom: 10px;
}

@media(max-width: 500px){
   .league_image_box {
      margin-right: 0px;
      max-width: 100%;
      margin-bottom: 25px;
   }
}
</style>

<h1 class="site_title">Liga bearbeiten <span>> {LABEL}</h1>

<form action="index.php?parser=esports&edit_league={ID}" method="post" enctype="multipart/form-data">
<div class="main_box">
   <div class="box_title">Liga-Bild bearbeiten</div>
   <div class="box_content">
      <div class="league_image_box">
         {if CUSTOM_LEAGUE_IMAGE}
            <img src="{FLASHIGNITE_URL}{CUSTOM_LEAGUE_IMAGE}" class="league_image">
            <div>Es wird momentan ein Custom-League Bild verwendet.</div>
         {else}
            <img src="{LEAGUE_IMAGE}" class="league_image">
            <div>Es wird momentan das Liga Bild von Riot verwendet.</div>
         {/if}
      </div>
      
      {if CUSTOM_LEAGUE_IMAGE}
         <h2>Custom Bild l&ouml;schen?</h2>
         <label><input type="checkbox" name="delete_custom_league_image" value="true"> Aktuelles Custom Bild l&ouml;schen</label>
      {/if}
      
      <h2>Custom Bild hochladen/&auml;ndern</h2>
      Hier kann ein anderes Bild hochgeladen werden, welches dann automatisch auf der Webseite genutzt wird.
      <div style="padding: 10px;">
         <input type="file" name="custom_image">
      </div>
      
      <div style="clear: both;"></div>
   </div>
   <div class="form-footer">
      <input type="hidden" name="id" value="{ID}">
      <input type="submit" value="Speichern">
   </div>
</div>
