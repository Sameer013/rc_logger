<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.css"
        integrity="sha512-72McA95q/YhjwmWFMGe8RI3aZIMCTJWPBbV8iQY3jy1z9+bi6+jHnERuNrDPo/WGYEzzNs4WdHNyyEr/yXJ9pA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Document</title>
    <style>
        .chart-container {
            width: 45%;
            float: left;
            margin: 10px;
        }

        .card {
            border-radius: 10px;
            text-align: center;
            background-color: white;
        }

        body {
            text-align: center;
            background-color: #e6e6ff;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="height:60vh;">
        <div class="row"
            style="background-color: #b3b3ff; height: 50px; text-align: center; display: flex; align-items: center; justify-content: center;">
            <div class="col col-sm-3 col-md-3 col-lg-3 col-xl-3">
                <p class="" style="font-size: 30px; font-weight: bold;">
                    <i class="fa fa-cog" aria-hidden="true" id="openModal" onclick="document.getElementById( 
             'username').value = '';document.getElementById( 
             'password').value = ''"></i>
                </p>
                <div class="modal" id="myModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h3 class="modal-title" style="font-weight: bold;">Login Page</h3>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <!-- Add your modal content here -->
                                <form id="loginForm">
                                    <span style="font-weight: bold;">User Name : </span>
                                    <input type="text" id="username" placeholder="Username"><br><br>
                                    <span style="font-weight: bold;">Password :</span>
                                    <input type="password" id="password" placeholder="Password">
                                </form>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="loginButton">login</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {

                    $('#loginButton').click(function () {

                        var username = $('#username').val();
                        var password = $('#password').val();

                        $.ajax({
                            type: 'POST',
                            url: 'login.php',
                            data: { username: username, password: password },
                            success: function (response) {
                                console.log(response);
                                if (response === '1success') {
                                    $('#myModal').modal('hide');
                                    $('#Modal2').modal('show');
                                } else {
                                    alert('User does not exist');
                                }
                            },
                            error: function () {
                                alert('Error occurred during login');
                            }
                        });
                    });
                    var edit_id = 1;
                    $.ajax({
                        type: "GET",
                        url: "nose_data.php",
                        data: {
                            check_edit: true,
                            sno: edit_id
                        },
                        success: function (response) {
                            $("#s1").val(response.s1);
                            $("#s2").val(response.s2);
                            $("#s3").val(response.s3);
                            $("#s4").val(response.s4);
                            $("#min").val(response.min);
                            $("#max").val(response.max);
                        }
                    });


                    $('#save_btn').click(function (e) {
                        e.preventDefault();
                        var s1 = $("#s1").val();
                        var s2 = $("#s2").val();
                        var s3 = $("#s3").val();
                        var s4 = $("#s4").val();
                        var min = $("#min").val();
                        var max = $("#max").val();

                        $.ajax({
                            type: "POST",
                            url: "nose_edit.php",
                            data: {
                                s1: s1,
                                s2: s2,
                                s3: s3,
                                s4: s4,
                                min: min,
                                max: max,
                                edit_check: "update",
                            },
                            success: function (responce) {
                                // console.log(responce);
                            },
                        });

                    });


                });
            </script>
            <div class="modal" id="Modal2">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Position From Nose</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <span style="font-weight: bold;">s1 : </span>
                            <input type="text" id="s1" name="s1"><br><br>
                            <span style="font-weight: bold;">s2 : </span>
                            <input type="text" id="s2" name="s2"><br><br>
                            <span style="font-weight: bold;">s3 : </span>
                            <input type="text" id="s3" name="s3"><br><br>
                            <span style="font-weight: bold;">s4 : </span>
                            <input type="text" id="s4" name="s4"><br><br>
                            <span style="font-weight: bold;">min : </span>
                            <input type="text" id="min" name="min">
                            <span style="font-weight: bold;">max : </span>
                            <input type="text" id="max" name="max">
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="save_btn">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col col-md-6 col-lg-6 col-xl-6">
                <h2 style="font-weight: bold;">Online Tuyere Nose Burn Monitoring System</h2>
            </div>

            <div class="col col-sm-3 col-lg-3 col-xl-3">
                <img src="tata_steel.png" height="40px">
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                <div class="card">
                    <h3 style="font-weight: bold;">Tuyere Nose Wear Condition</h3>
                    <table id="mytable" class="table table-border table-hover">

                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Pos From Nose</th>
                                <th class="text-center">Condition</th>
                                <th class="text-center">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <script>
                    $(document).ready(function () {

                        function getdata() {
                            console.log("djshcas")
                            $.ajax({
                                type: "GET",
                                url: "data_table.php",
                                datatype: "json",
                                success: function (response) {
                                    console.log(response);
                                    var modifiedData = response.map(function (item, index) {
                                        var posFromNose = 's' + (index + 1);
                                        var condition = checkCondition(item.posFromNose, item.min, item.max);
                                        var remarks = 'noware';
                                        return {
                                            'No.': index + 1,
                                            'Pos From Nose': posFromNose,
                                            'Condition': condition,
                                            'Remarks': remarks
                                        };
                                    });

                                }
                            })
                        }
                        function checkCondition(posFromNose, min, max) {
                            if (posFromNose >= min && posFromNose <= max) {
                                return 1;
                            } else {
                                return 2;
                            }
                        }

                        var table = $('#mytable').DataTable();

                        getdata();
                    });
                </script>
                <div class="image">
                    <img src="blockdiagram.jpg" style="height:350px; weidth:350px;" class="" />
                </div>



            </div>
            <div class="col-12 col-md-8 col-lg-8 col-xl-8 ">

                <div class="card">
                    <div class="
card-header
d-flex
flex-sm-row flex-column
justify-content-md-between
align-items-start
justify-content-start
">
                        <div class="chart-container rounded">
                            <div id="chartContainerS1" style="height: 300px; width: 100%;"></div>
                        </div>
                        <div class="chart-container">
                            <div id="chartContainerS2" style="height: 300px; width: 100%;"></div>
                        </div>

                        <div class="chart-container">
                            <div id="chartContainerS3" style="height: 300px; width: 100%;"></div>
                        </div>
                        <div class="chart-container">
                            <div id="chartContainerS4" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">Designed and Developed By
                <a class="ms-25" href="https://www.sigmaworld.in/" target="_blank">Sigma E Solution
                    Pvt Ltd </a><span>
    </footer>

    <!-- END: Footer-->
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        $(document).ready(function () {

            var charts = [];

            for (var i = 1; i <= 4; i++) {
                var chartContainerId = "chartContainerS" + i;
                var dps = [];

                var chart = new CanvasJS.Chart(chartContainerId, {
                    title: {
                        text: "Series " + i + " Data"
                    },
                    axisX: {
                        valueFormatString: "YYYY-MM-DD HH:mm:ss"
                    },
                    data: [
                        {
                            type: "spline",
                            showInLegend: true,
                            name: "S" + i,
                            color: getRandomColor(),
                            lineThickness: 2,
                            lineCurveType: "smooth",
                            dataPoints: dps
                        }
                    ]
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
                            charts[i].chart.render();
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

    </script>
    <script>
        document.getElementById('openModal').addEventListener('click', function () {
            $('#myModal').modal('show');
        });
    </script>
     <script src="js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript"></script>
</body>

</html>