<?php
require_once "controller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SHASSIC | Document Check</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">

</head>

<body id="page-top">

    <?php if ($_SESSION['login']) { ?>

        <div class="landing-container">
            <div class="landing-navbar">
                <img src="img/landing/logo.png" class="landing-logo">
                <!-- <nav class="landing-nav">
                    <ul class="landing-ul" id="menuList">
                        <li class="landing-li"><a href="about.php" class="landing-a">ABOUT</a></li>
                    </ul>
                </nav> -->
                <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
            </div>

            <!-- Page Wrapper -->
            <div id="wrapper">

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Begin Page Content -->
                        <div class="container-fluid">

                            <!-- Document Check Data Table -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                        <a class="font-weight-bold" href="index.php">
                                            &larr; Back</a>
                                    </div>
                                    <h6 class="h3 mb-4 text-gray-800 font-weight-bold">Document Check</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ITEM</th>
                                                    <th>CHECKLIST</th>
                                                    <th>C</th>
                                                    <th>NC</th>
                                                    <th>NA</th>
                                                    <th>REMARKS</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">TOTAL SCORE</th>
                                                    <th id="selectedC" value="">00</th>
                                                    <th id="selectedNC">00</th>
                                                    <th id="selectedNA">00</th>
                                                    <th id="totalScore"></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <tr>
                                                    <td><b>A</b></td>
                                                    <td colspan="5"><b>PROJECT OSH POLICY</b><br>(NOTE: Project OSH Policy is only applicable to organisations with more than five (5)
                                                        employees)</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Whether there is a written Project OSH Policy
                                                        Statement?</td>
                                                    <td><input type="checkbox" class="checkbox1"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Has the SHC conducted a review to ensure
                                                        suitability of Project OSH Policy Statement?</td>
                                                    <td><input type="checkbox" class="checkbox1"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Whether Project OSH Policy Statement was written
                                                        in Bahasa Malaysia?</td>
                                                    <td><input type="checkbox" class="checkbox1"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>Whether Project OSH Policy Statement was written
                                                        in English?</td>
                                                    <td><input type="checkbox" class="checkbox1"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>Whether Project OSH Policy is signed by the top
                                                        management?</td>
                                                    <td><input type="checkbox" class="checkbox1"></td>
                                                    <td><input type="checkbox" class="checkbox2"></td>
                                                    <td><input type="checkbox" class="checkbox3"></td>
                                                    <td></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->

                </div>
                <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->

            <!-- Footer -->
            <footer class="small">
                <div class="container my-auto justify-content-center">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; SHASSIC 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <!-- <i class="fas fa-angle-up"></i> -->
                &uarr;
            </a>

            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>
        </div>

    <?php } else {
        header("location: login.php");
    } ?>

    <script>
        var checkboxes = document.querySelectorAll('.checkbox1');
        var checkboxes2 = document.querySelectorAll('.checkbox2');
        var checkboxes3 = document.querySelectorAll('.checkbox3');
        //console.log(checkboxes);
        var totalScore = 0;
        var countC = 0;
        var countNC = 0;
        var countNA = 0;
        //SELECT ALL CHECKBOX AND RETURN OF ALL CHECKBOX
        // for (var checkbox of checkboxes) {
        //    countC = 0;
        //     checkbox.checkbox = this.checkbox;
        //     if (checkbox.checkbox == true) {
        //         countC++;
        //         document.getElementById('selectedC').innerHTML = countC;
        //     } else {
        //         countC = 0;
        //         document.getElementById('selectedC').innerHTML = countC;
        //     }
        // }

        //FOR INDIVIDUALS CHECKBOX  C COUNT
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('click', function() {
                // make sure if checkbox is checked or not
                if (this.checked == true) {
                    countC++;
                } else {
                    countC--;
                }
                document.getElementById('selectedC').innerHTML = countC;
                totalScore += countC;
                document.getElementById('totalScore').innerHTML = totalScore;
            })
        }

        //FOR INDIVIDUALS CHECKBOX  NC COUNT
        for (var i = 0; i < checkboxes2.length; i++) {
            checkboxes2[i].addEventListener('click', function() {
                // make sure if checkbox is checked or not
                if (this.checked == true) {
                    countNC++;
                } else {
                    countNC--;
                }
                document.getElementById('selectedNC').innerHTML = countNC;
                totalScore += countNC;
                document.getElementById('totalScore').innerHTML = totalScore;
            })
        }

        //FOR INDIVIDUALS CHECKBOX  NA COUNT
        for (var i = 0; i < checkboxes3.length; i++) {
            checkboxes3[i].addEventListener('click', function() {
                // make sure if checkbox is checked or not
                if (this.checked == true) {
                    countNA++;
                } else {
                    countNA--;
                }
                document.getElementById('selectedNA').innerHTML = countNA;
                totalScore += countNA;
                document.getElementById('totalScore').innerHTML = totalScore;
            })
        }

        // totalScore = countC + countNA;
        // document.getElementById('totalScore').innerHTML = totalScore;

        // function DocumentCheck() {
        //     C = document.getElementById('selectedC').value;
        //     NA = document.getElementById('selectedNA').value;
        //     document.getElementById("totalScore").innerHTML = C * NA;
        // }
    </script>

</body>

</html>