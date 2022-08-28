/* This file contains scripts that are executed when admin chooses to visit
   the pages CHARTS PER HOUR and CHARTS PE DAY. */

/* myChart and isVisible are global variables that represent the Chart object
   and a boolean value about whether a graph is visible or not. */
   var myChart;
   var isVisible;


/* This script gets executed when admin is on page CHARTS PER HOUR and
   they choose a specific date in the relevant form on the right of the page. */
$(document).ready( function() {
/* date-search is the id of submit button */
  $('#date-search').click( function() {
/* show div box that contains the chart canvas and the two checkboxes */
    $("#per_hour_wrapper").show();
    var date = $('#date_value').val();
/* AJAX query to get the necessary data.
   If the AJAX query succeeds, it returns the chart x-labels (hours of a day),
   the values for visits variation graph and the ones for covid visits variation graph. */
    $.ajax( {
      type: "POST",
      url: "includes/per_hour_chart.inc.php",
      dataType: "json",
      data: {
        date: date
      },
      success: function(data) {
        labels_hour = data[0];
        visits_variation_per_hour = data[1];
        covid_visits_variation_per_hour = data[2];
/* create_per_hour_chart() is called to create the requested chart */
        create_per_hour_chart(labels_hour, visits_variation_per_hour, covid_visits_variation_per_hour);
      }
    } );
  } );
} );


/* This function is called by a JS script in order to create the per hour chart. */
function create_per_hour_chart(labels_hour, visits_variation_per_hour, covid_visits_variation_per_hour) {
/* If there is already a chart, destroy it so there will not be any error. */
  if ( myChart instanceof Chart) {
    myChart.destroy();
  }
/* myCanvas_hour is the id of the div block that contains the chart canvas and the two checkboxes. */
  const ctx = $('#myCanvas_hour');
  myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels_hour,
      datasets: [{
          label: 'Variation of Visits per hour',
          data: visits_variation_per_hour,
          backgroundColor: 'rgba(255, 206, 86, 0.3)',
          borderColor: 'rgba(255, 206, 86, 1)',
          borderWidth: 2
        },
        {
          label: 'Variation of COVID-19 Visits per hour',
          data: covid_visits_variation_per_hour,
          backgroundColor: 'rgba(153, 102, 255, 0.3)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 2
      }]
    },
    options: {
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: 'Variation of Visits per Hour',
        }
      },
/* Finds all of the items that intersect the point. */
      interaction: {
        mode: 'index'
      },
      scaleShowValues: true,
      scales: {
        x: {
          ticks: {
            autoSkip: false,
          },
          title: {
            display: true,
            text: "Day"
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "# Visits"
          }
        },
      }
    }
  } );
/* When creating a chart, the two checkboxes are checked.
   That means that both graphs will be shown. */
  $('.total_visits_chart_checkbox').prop("checked", true);
  $('.covid_visits_chart_checkbox').prop("checked", true);
}



/* This script gets executed when admin is on page CHARTS PER DAY and
   they choose a specific week in the relevant form on the right of the page. */
$(document).ready( function() {
  $('#week-search').click( function() {
/* show div box that contains the chart canvas and the two checkboxes */
    $("#per_day_wrapper").show();
    var week = $('#week_value').val();
/* AJAX query to get the necessary data.
   If the AJAX query succeeds, it returns the chart x-labels (hours of a day),
   the values for visits variation graph and the ones for covid visits variation graph. */
    $.ajax( {
      type: "POST",
      url: "includes/per_day_week_chart.inc.php",
      dataType: "json",
      data: {
        week: week
      },
      success: function(data) {
        labels_date = data[0];
        visits_per_date = data[1];
        covid_visits_per_date = data[2];
/* create_per_day_chart() is called to create the requested chart */
        create_per_day_chart(labels_date, visits_per_date, covid_visits_per_date);
      }
    } );
  } );
} );


/* This script gets executed when admin is on page CHARTS PER DAY and
   they choose a specific month in the relevant form on the right of the page. */
$(document).ready( function() {
  $('#month-search').click( function() {
/* show div box that contains the chart canvas and the two checkboxes */
    $("#per_day_wrapper").show();
    var month = $('#month_value').val();
/* AJAX query to get the necessary data.
   If the AJAX query succeeds, it returns the chart x-labels (hours of a day),
   the values for visits variation graph and the ones for covid visits variation graph. */
    $.ajax( {
      type: "POST",
      url: "includes/per_day_month_chart.inc.php",
      dataType: "json",
      data: {
        month: month
      },
      success: function(data) {
        labels_date = data[0];
        visits_per_date = data[1];
        covid_visits_per_date = data[2];
/* create_per_day_chart() is called to create the requested chart */
        create_per_day_chart(labels_date, visits_per_date, covid_visits_per_date);
      }
    } );
  } );
} );


/* This function is called by a JS script in order to create the per day chart. */
function create_per_day_chart(labels_date, visits_per_date, covid_visits_per_date) {
/* If there is already a chart, destroy it so there will not be any error. */
        if ( myChart instanceof Chart) {
          myChart.destroy();
        }
/* myCanvas_day is the id of the div block that contains the chart canvas and the two checkboxes. */
        const ctx = $('#myCanvas_day');
        myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: labels_date,
              datasets: [{
                  label: '# of Visits per day',
                  data: visits_per_date,
                  backgroundColor: 'rgba(255, 206, 86, 0.3)',
                  borderColor: 'rgba(255, 206, 86, 1)',
                  borderWidth: 2
              },
              {
                  label: '# of COVID-19 Visits per day',
                  data: covid_visits_per_date,
                  backgroundColor: 'rgba(153, 102, 255, 0.3)',
                  borderColor: 'rgba(153, 102, 255, 1)',
                  borderWidth: 2
              }]
          },
          options: {
              plugins: {
                legend: {
                  display: false
                },
                title: {
                  display: true,
                  text: 'Number of Visits per Day',
                }
              },
/* Finds all of the items that intersect the point. */
              interaction: {
                mode: 'index'
              },
              scaleShowValues: true,
              scales: {
                x: {
                    ticks: {
                      autoSkip: false,
                    },
                    title: {
                      display: true,
                      text: "Day"
                    }
                  },
                y: {
                    beginAtZero: true,
                    title: {
                      display: true,
                      text: "# Visits"
                    }
                },
          }
        }
        } );
/* When creating a chart, the two checkboxes are checked.
   That means that both graphs will be shown. */
        $('.total_visits_chart_checkbox').prop("checked", true);
        $('.covid_visits_chart_checkbox').prop("checked", true);
}


/* The two following scripts are executed when some of the two checkboxes
   changes state (from checked to unchecked and vice versa).
   If the graph that the particular script represents is visible,
   then the graph will be hidden and if the graph is hidden, then it will become visible. */

$(document).ready( function() {
  $('.total_visits_chart_checkbox').change( function() {
    isVisible = myChart.isDatasetVisible($('.total_visits_chart_checkbox').val());
    if ( isVisible === false ) {
      myChart.show($('.total_visits_chart_checkbox').val());
    }
    else if ( isVisible === true ) {
      myChart.hide($('.total_visits_chart_checkbox').val());
    }
  } );
} );

$(document).ready( function() {
  $('.covid_visits_chart_checkbox').change( function() {
    isVisible = myChart.isDatasetVisible($('.covid_visits_chart_checkbox').val());
    if ( isVisible === false ) {
      myChart.show($('.covid_visits_chart_checkbox').val());
    } else if ( isVisible === true ) {
      myChart.hide($('.covid_visits_chart_checkbox').val());
    }
  } );
} );
