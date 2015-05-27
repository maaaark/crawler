<h1 class="site_title">Teams</h1>

<style>
   .team_element {
      float: left;
      width: 100%;
      max-width: 240px;
      padding: 8px;
      background: #fff;
      margin: 5px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: all 200ms;
   }
   
   .team_element .icon {
      width: 100%;
      height: 120px;
      background-size: auto 100px;
      background-position: center center;
      background-repeat: no-repeat;
   }
   
   .team_element .name {
      color: rgba(0,0,0,0.6);
      text-align: center;
   }
   
   .team_element:hover {
      background: #F7F7F7;
   }
</style>

{if TEAMS_LIST}
   <div>
      {TEAMS_LIST}
      <div style="clear: both;"></div>
   </div>
{else}
   Es sind leider keine Teams vorhanden.
{/if}