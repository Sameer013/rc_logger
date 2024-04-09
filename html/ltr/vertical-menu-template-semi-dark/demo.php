<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Demo</title>
</head>

<body>

  <div class="chart-container">
    <div class="indicator" id="indicatorS1"></div>
    <canvas id="chartContainerS1" style="height: 300px; width: 100%;"></canvas>
  </div>

  <div class="chart-container">
    <div class="indicator" id="indicatorS2"></div>
    <canvas id="chartContainerS2" style="height: 300px; width: 100%;"></canvas>
  </div>

  <div class="chart-container">
    <div class="indicator" id="indicatorS3"></div>
    <canvas id="chartContainerS3" style="height: 300px; width: 100%;"></canvas>
  </div>

  <div class="chart-container">
    <div class="indicator" id="indicatorS4"></div>
    <canvas id="chartContainerS4" style="height: 300px; width: 100%;"></canvas>
  </div>


  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@1.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

  <script>
    $(document).ready(function () {
      var charts = [];

      for (var i = 1; i <= 4; i++) {
        var chartContainerId = "chartContainerS" + i;
        var ctx = document.getElementById(chartContainerId).getContext('2d');
        var dps = [];
        var maxDps = [];
        var Indicator = " ";

        var chart = new Chart(ctx, {
          type: 'line',
          data: {
            datasets: [
              {
                label: 'S' + i,
                borderColor: getRandomColor(),
                data: dps,
                fill: false,
              },
              {
                label: 'Max Values',
                borderColor: 'red',
                borderDash: [5, 5],
                data: maxDps,
                fill: false,
              },
            ],
          },
          options: {
            tension: 0.4,
            cubicInterpolationMode: 'monotone',
            animation: {
              duration: updateInterval / 2,
            },
            scales: {
              x: {
                type: 'time',
                time: {
                  unit: 'second',
                  displayFormats: {
                    second: 'HH:mm:ss',
                  },
                },
                scaleLabel: {
                  display: true,
                  labelString: 'Time',
                },
              },
              y: {
                beginAtZero: true,
              },
            },
          },
        });

        charts.push({ chart: chart, dps: dps, maxDps: maxDps });
      }

      var updateInterval = 1000;

      var updateCharts = function () {
        $.ajax({
          url: 'fetch_data.php',
          method: 'GET',
          contentType: 'application/json',
          success: function (data) {
            var xVal = new Date().toISOString();
            for (var i = 0; i < charts.length; i++) {
              var yVal = data.values[i];
              var maxVal = data.minMaxValues.max;
              var dps = charts[i].dps;
              var maxDps = charts[i].maxDps;

              dps.push({
                x: xVal,
                y: yVal,
              });

              maxDps.push({
                x: xVal,
                y: maxVal,
              });

              // Add a binary indicator (1 or 0) if y-value is greater than 3000
              var binaryIndicator = yVal > 3000 ? 1 : 0;

              // Update the binary indicator on the top with image and standard size
              var indicatorElement = document.getElementById('indicatorS' + (i + 1));
              var indicatorElement = document.getElementById('indicatorS' + (i + 1));
              indicatorElement.innerHTML = binaryIndicator === 1 ?
                '<img src="red_dot.jpg" alt="Red Dot" style="width: 20px; height: 20px; transform: rotate(90deg);">' :
                '<img src="green_dot.png" alt="Green Dot" style="width: 20px; height: 20px; transform: rotate(90deg);">';

              charts[i].chart.update();

              if (dps.length > 20) {
                dps.shift();
              }

              if (maxDps.length > 20) {
                maxDps.shift();
              }
            }
          },
        });
      };


      updateCharts();

      setInterval(updateCharts, updateInterval);

      function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      }
    });
  </script>

</body>

</html>


<!-- <div class="content-wrapper container-xxl p-0">
  <div class="content-body">
    <section id="apexchart">
      <div class="row">

        <div class="container-fluid">
          <div class="row">

            <div class="col-12 col-md-12 col-lg-12 col-xl-12 ">

              <div class="card">
                <div class="
                    card-header
                    d-flex
                    flex-sm-row flex-column
                    justify-content-md-between
                    align-items-start
                    justify-content-start
                  ">
                  
                  <div class="chart-container">
                    <canvas id="chartContainerS1" style="height: 200px; width: 100%;"></canvas>
                  </div>

                  <div class="chart-container">
                    <canvas id="chartContainerS2" style="height: 200px; width: 100%;"></canvas>
                  </div>

                  <div class="chart-container">
                    <canvas id="chartContainerS3" style="height: 200px; width: 100%;"></canvas>
                  </div>

                  <div class="chart-container">
                    <canvas id="chartContainerS4" style="height: 200px; width: 100%;"></canvas>
                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div> 
<script>
            $(document).ready(function () {

                var ctx;

                var charts = [];

                for (var i = 1; i <= 4; i++) {
                    var chartContainerId = "chartContainerS" + i;


                    ctx = document.getElementById(chartContainerId).getContext('2d');
                    var dps = [];

                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            datasets: [{
                                label: 'S' + i,
                                borderColor: getRandomColor(),
                                data: dps,
                                fill: false,
                                indexAxis: 'x',
                            }]
                        },
                        options: {
                            tension: 0.6,
                            plugins: {
                                decimation: {
                                    algorithm: 'lttb',
                                    threshold: 10,
                                }
                            },
                            title: {
                                text: 'Series ' + i + ' Data'
                            },
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: 'second',
                                        displayFormats: {
                                            second: 'HH:mm:ss'
                                        }
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time'
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    charts.push({ chart: chart, dps: dps });
                }

                var updateInterval = 1000;
                var dataLength = 20;

                var updateCharts = function () {
                    $.ajax({
                        url: 'fetch_data.php',
                        method: 'GET',
                        contentType: 'application/json',
                        success: function (data) {
                            var xVal = new Date(data.labels[0]);
                            for (var i = 0; i < charts.length; i++) {
                                var yVal = data.values[i];
                                var dps = charts[i].dps;

                                dps.push({
                                    x: xVal,
                                    y: yVal
                                });

                                if (dps.length > dataLength) {
                                    dps.shift();
                                }
                            }

                            for (var i = 0; i < charts.length; i++) {
                                charts[i].chart.update();
                            }
                        }
                    });
                };

                updateCharts();
                setInterval(updateCharts, updateInterval);

                function getRandomColor() {
                    var letters = '0123456789ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                }
            });
        </script> -->