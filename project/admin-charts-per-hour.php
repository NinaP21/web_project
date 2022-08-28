<!-- This page will show up when admin visits the tab CHARTS -> CHARTS PER HOUR -->
<?php
  require_once "header.php";
?>

<!-- Background image -->
<img class="bgd" src="images/back.jpg" alt="">

<div class = "container-fluid web-form">
  <div class="row">
<!-- Wrapper that contains the canvas for the chart and the two checkboxes,
     each one for a graph. At first this wrapper will be hidden and it will appear
     when admin chooses a specific date, so that the graphs are created. -->
    <div class="col-md-5" id="per_hour_wrapper" style="background-color: #F8F8F8;">
      <input type="checkbox" name="total-visits" value="0" class="total_visits_chart_checkbox" checked>
      <label for="total-visits" class="chart-checkbox">Total visits</label>
      <input type="checkbox" name="covid-visits" value="1" class="covid_visits_chart_checkbox" checked>
      <label for="covid-visits" class="chart-checkbox">COVID-19 confirmed visits</label>
      <canvas id="myCanvas_hour" width="600" height="400"> </canvas>
    </div>

    <div class="col-md-4"> </div>

<!-- Wrapper with an input form so that the admin can choose a date -->
    <div class="col-md-3 profile-info-wrapper form-group" id="per_hour_select_wrapper">
      <h2 class="profile-title">Select date:</h2>
        <div class="form-group">
          <label for="date">Day</label>
          <input class="field form-control" type="date" min="2019-12-20" value="2022-05-20" name="date" id="date_value">
        </div>
        <button class="btn btn-default btn-block form-button" type="button" id="date-search">Search</button>
    </div>
  </div>
</div>

<?php
  require_once "footer.php";
?>
