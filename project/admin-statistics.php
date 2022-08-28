<!-- This page appears when admin visits the tab STATISTICS -->
<?php
  require_once "header.php";
?>

<!-- Background image -->
<img class="bgd" src="images/back.jpg" alt="">

<div class = "container-fluid web-form">
  <div class="row">
<!-- Wrapper with three small panels, each one for a statistic result.
     The required statistic values will be filled by the appropriate JS
     scripts in file scripts/statistics_scripts.js -->
    <div class="col-md-4 wrapper">
      <div class="panel panel-primary">
        <div class="panel-heading">Total number of recorded visits</div>
          <div class="panel-body statistics" id="nr-visits"> </div>
      </div>


      <div class="panel panel-primary">
        <div class="panel-heading">Total number of COVID-19 confirmed cases</div>
          <div class="panel-body statistics" id="nr-covid-cases"> </div>
      </div>


      <div class="panel panel-primary">
        <div class="panel-heading">Total number of visits by users positive to COVID-19</div>
          <div class="panel-body statistics" id="nr-covid-visits"> </div>
      </div>
    </div>

    <div class="col-md-4"> </div>

<!-- A wrapper that contains two tabs that contain tables with the ranking of POIs.
     The admin has to click on the tab's title to see the appropriate table.
     Moreover, the inactive tab has blueish background on its title. -->
    <div class="col-md-5 profile-info-wrapper form-group">
      <h2 class="profile-title">Ranking of POIs according to:</h2>
      <div class="btn-group btn-group-justified" role="group" class="tab" >
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-lg btn-default activeTab" id="user_visitsTab">Number of users' visits</button>
        </div>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-lg btn-default inactiveTab" id="active_covid_visitsTab">Number of active COVID-19 visits</button>
        </div>
      </div>
<!-- In the two following blocks, the user will be able to see
     the tables that show their history data.-->
      <div class="activeTab_content" id="user_visits"> </div>
      <div class="inactiveTab_content" id="active_covid_visits"> </div>
    </div>

  </div>
</div>

<?php
  require_once "footer.php";
?>
