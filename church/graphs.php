<?php 
include('includes/checklogin.php');
check_login();

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php @include("includes/header.php");?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php @include("includes/sidebar.php");?>
      <!-- partial -->
      <div class="main-panel">
        <br>
        <br>
        <div class="content-wrapper">

          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="clearfix">
                    <h4 class="card-title float-left">Bid Per Source</h4>
                    <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
                  </div>
                  <canvas id="graphCanvas5" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bids Per Source Name</h4>
                  <canvas id="graphCanvas2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="clearfix">
                    <h4 class="card-title float-left">Bids Per Status</h4>
                    <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
                  </div>
                  <canvas id="graphCanvas4" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bids Per Sector</h4>
                  <canvas id="graphCanvas3" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php @include("includes/footer.php");?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <?php @include("includes/foot.php");?>
  <script >
    $(document).ready(function(){
      $.ajax({
        url: "data.php",
        method: "GET",
        success: function(data){
          console.log(data);
          var name = [];
          var marks = [];

          for (var i in data){
            name.push(data[i].Sector);

            marks.push(data[i].total);
          }
          var chartdata = {
            labels: name,
            datasets: [{
              label: 'student marks',
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
              borderColor: 'rgba(134, 159, 152, 1)',
              hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
              hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
              data: marks

            }]
          };
          var graphTarget = $("#graphCanvas");
          var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        },
        error: function(data) {
          console.log(data);
        }

      });
    });



    $(document).ready(function(){
      $.ajax({
        url: "data.php",
        method: "GET",
        success: function(data){
          console.log(data);
          var name = [];
          var marks = [];

          for (var i in data){
            name.push(data[i].Sector);

            marks.push(data[i].total);
          }
          var chartdata = {
            labels: name,
            datasets: [{
              label: 'No of Bids',
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
              borderColor: 'rgba(134, 159, 152, 1)',
              hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
              hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
              data: marks

            }]
          };
          var graphTarget = $("#graphCanvas3");
          var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        },
        error: function(data) {
          console.log(data);
        }

      });
    });





    $(document).ready(function(){
      $.ajax({
        url: "data1.php",
        method: "GET",
        success: function(data1){
          console.log(data1);
          var name = [];
          var marks = [];

          for (var i in data1){
            name.push(data1[i].Status);

            marks.push(data1[i].total);
          }
          var chartdata = {
            labels: name,
            datasets: [{
              label: 'No of bids',
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
              borderColor: 'rgba(134, 159, 152, 1)',
              hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
              hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
              data: marks

            }]
          };
          var graphTarget = $("#graphCanvas4");
          var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        },
        error: function(data) {
          console.log(data);
        }

      });
    });




    $(document).ready(function(){
      $.ajax({
        url: "data2.php",
        method: "GET",
        success: function(data2){
          console.log(data2);
          var name = [];
          var marks = [];

          for (var i in data2){
            name.push(data2[i].Source);

            marks.push(data2[i].total);
          }
          var chartdata = {
            labels: name,
            datasets: [{
              label: 'No of bids',
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
              borderColor: 'rgba(134, 159, 152, 1)',
              hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
              hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
              data: marks

            }]
          };
          var graphTarget = $("#graphCanvas5");
          var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        },
        error: function(data) {
          console.log(data);
        }

      });
    });



    $(document).ready(function(){
      $.ajax({
        url: "data3.php",
        method: "GET",
        success: function(data3){
          console.log(data3);
          var name = [];
          var marks = [];

          for (var i in data3){
            name.push(data3[i].Newspaper);

            marks.push(data3[i].total);
          }
          var chartdata = {
            labels: name,
            datasets: [{
              label: 'No of Bids',
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
              borderColor: 'rgba(134, 159, 152, 1)',
              hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
              hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
              data: marks

            }]
          };
          var graphTarget = $("#graphCanvas2");
          var barGraph = new Chart(graphTarget, {
            type: 'bar',
            data: chartdata,
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        },
        error: function(data) {
          console.log(data);
        }

      });
    });

  </script>
</body>
</html>


