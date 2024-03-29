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

    <title>SHASSIC | Assessment Result</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">
    <link rel="stylesheet" type="text/css" href="css/circular-progress-bar.css">
    <link rel="stylesheet" type="text/css" href="css/star-rantings.css">

    <script type=text/javascript>
        // Get ratings
        function getRankings() {
            // Initial Ratings
            // let ratings = document.getElementById("rating-control").value;

            let documents = document.getElementById("input-percentage").value;
            let workplace = document.getElementById("workplace-inspection").value;
            let personnel = document.getElementById("personnel-interview").value;


            let ratings = ((parseInt(documents) + parseInt(workplace) + parseInt(personnel)) / 100) * 5;

            let star = 3;

            console.log(workplace);
            console.log(personnel);
            console.log(ratings);

            // Total Stars
            const starsTotal = 5;

            // Get percentage
            const starPercentage = (ratings / starsTotal) * 100;

            if (starPercentage <= 49) {
                star = "Certificate of Participation ONLY";
            } else if (starPercentage >= 50 && starPercentage <= 59.9) {
                star = 20;
            } else if (starPercentage >= 60 && starPercentage <= 69.9) {
                star = 40;
            } else if (starPercentage >= 70 && starPercentage <= 79.9) {
                star = 60;
            } else if (starPercentage >= 80 && starPercentage <= 89.9) {
                star = 80;
            } else {
                star = 100;
            }

            // Round to nearest 10
            const starPercentageRounded = `${Math.round(parseInt(star / 10) * 10)}%`;
            console.log(starPercentageRounded);

            // Set width of stars-inner to percentage
            console.log(star);
            document.querySelector(`.stars-inner`).style.width = starPercentageRounded;

            // Add number rating
            // document.querySelector(`.number-rating`).innerHTML = star;
        }
    </script>

</head>

<body id="page-top">

    <?php if ($_SESSION['login']) { ?>

        <div class="landing-container">
            <div class="landing-navbar">
                <a href="landing.php" class="d-flex landing-a">
                    <h1 style="font-size: 24px; font-weight: 700; color: #fff; margin-top: 3rem; margin-bottom: 3rem">SHASSIC<span style="color: #558381;">.</span></h1>
                </a>
                <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
            </div>

            <!-- Page Wrapper -->
            <div id="wrapper">

                <?php
                $email = $_SESSION['login'];
                $assessee_id = $_GET['assessee_id'];
                $sql = "SELECT * from assessment where assessee_id=:assessee_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':assessee_id', $assessee_id, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>

                        <!-- Content Wrapper -->
                        <div id="content-wrapper" class="d-flex flex-column">

                            <!-- Main Content -->
                            <div id="content">

                                <!-- Begin Page Content -->
                                <div class="container-fluid">

                                    <div class="card shadow mb-4">

                                        <div class="card-header py-3">
                                            <div class="text-center" id="row">
                                                <div class="col-sm-6" style="display:flex; width:auto; justify-content: start;">
                                                    <a class="font-weight-bold" href="history.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                        < Back</a>
                                                </div>
                                            </div>
                                            <!-- Page Heading -->
                                            <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Assessment Result</h1>
                                        </div>

                                        <style>
                                            @media print {
                                                body * {
                                                    visibility: hidden;
                                                    /* overflow:visible !important; */
                                                }

                                                .print-container * {
                                                    visibility: visible;
                                                }

                                                .print-container {
                                                    position: relative;
                                                    left: 0;
                                                    top: 0;
                                                }
                                            }
                                        </style>

                                        <span class="print-container">
                                            <div class="card-body">

                                                <div class="text-center">
                                                    <a href=""><img src="img/shassic-logo.png" width="200px"></a>
                                                    <br>
                                                    <br>
                                                </div>

                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                    <thead>
                                                        <th colspan="4">Result SHASSIC Assessment</th>
                                                    </thead>
                                                </table>

                                                <!-- Details Assessee -->
                                                <p align=left>Assessee Name<span style="padding-left: 30px">: <?php echo htmlentities($result->assessee_name); ?></span></p>
                                                <p align=left>Project Name<span style="padding-left: 50px">: <?php echo htmlentities($result->project_name); ?></span></p>
                                                <p align=left>Project Date<span style="padding-left: 60px">: <?php echo htmlentities(date_format(new DateTime($result->project_date), 'd/m/Y')); ?></span></p>
                                                <p align=left>Project Location<span style="padding-left: 30px">: <?php echo htmlentities($result->project_location); ?></span></p>

                                                <form class="" action="" method="POST">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="3">Assessment Name</th>
                                                                    <th>Percentage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="3">Document Check</td>
                                                                    <td><?php echo htmlentities($result->document_check_percentage); ?> %</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">Workplace Inspection</td>
                                                                    <td><?php echo htmlentities($result->workplace_inspection_percentage); ?> %</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">Personnel Interview</td>
                                                                    <td><?php echo htmlentities($result->personnel_interview_percentage); ?> %</td>
                                                                </tr>

                                                            </tbody>
                                                            <tfoot>
                                                                <!-- For total score -->
                                                                <tr>
                                                                    <td colspan="3" class="font-weight-bold">Total SHASSIC (score %)</td>
                                                                    <td class="font-weight-bold"><?php echo htmlentities($result->total_percentage) ?></td>
                                                                </tr>

                                                                <!-- For ranking star -->
                                                                <tr class="star">
                                                                    <td colspan="3" class="font-weight-bold">Star(s) Awarded</td>
                                                                    <td>
                                                                        <div class="stars-outer">
                                                                            <div class="stars-inner"></div>
                                                                        </div>
                                                                        <div>
                                                                            <span class="number-rating"></span>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                            </tfoot>
                                                        </table>
                                                    </div>



                                                    <!-- ID for ranking star -->
                                                    <div id="assessee-select">
                                                        <input type="hidden" value="<?php echo htmlentities($result->assessee_name); ?>">
                                                        <input type="hidden" id="rating-control" value="<?php echo htmlentities($result->star_ranking); ?>">
                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="input-percentage" id="input-percentage" value="<?php echo htmlentities($result->document_check_percentage); ?>">
                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="workplace-inspection" id="workplace-inspection" value="<?php echo htmlentities($result->workplace_inspection_percentage); ?>">
                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel-interview" id="personnel-interview" value="<?php echo htmlentities($result->personnel_interview_percentage); ?>">
                                                        <?php
                                                        echo '<script type="text/javascript"> getRankings(); </script>';
                                                        ?>
                                                    </div>
                                                </form>

                                                <div style="font-size: small;">
                                                    <p align=left><b>SHASSIC Star Ranking Description</b></p>
                                                    <p align=left>90 to 100: 5 STAR(S)</p>
                                                    <p align=left>80 to 89.9: 4 STAR(S)</p>
                                                    <p align=left>70 to 79.9: 3 STAR(S)</p>
                                                    <p align=left>60 to 69.9: 2 STAR(S)</p>
                                                    <p align=left>50 to 59.9: 1 STAR(S)</p>
                                                    <p align=left>49 and below: Certificate of Participation ONLY</p>
                                                    <p align=left><b>Note: CIDB may award CCD point for the projects scored with star rankings</b></p><br>
                                                </div>


                                                <!-- For sign -->
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2" style="text-align: left;">Prepared By</td>
                                                            <td colspan="2" style="text-align: left;">Checked By</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:left">Signature</td>
                                                            <td style="text-align:left">:
                                                                <hr>
                                                            </td>
                                                            <td style="text-align:left">Signature</td>
                                                            <td style="text-align:left">:
                                                                <hr>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="width: 20%; text-align:left">Assessor name</td>
                                                            <td style="width: 30%; text-align:left">:
                                                                <hr>
                                                            </td>
                                                            <td style="width: 20%; text-align:left">Name</td>
                                                            <td style="width: 30%; text-align:left">:
                                                                <hr>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 20%; text-align:left">Department stamp</td>
                                                            <td style="width: 30%; text-align:left">:</td>
                                                            <td style="width: 20%; text-align:left">Department stamp</td>
                                                            <td style="width: 30%; text-align:left">:</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </span>



                                        <!-- For print -->
                                        <div class="form-group" id="row">
                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?php echo htmlentities($result->assessee_id); ?>">
                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="document_check_percentage" id="document_check_percentage" onchange="countSelected()">
                                                </div>
                                                <button class="btn btn-primary btn-user btn-block font-weight-bold" name="print_document" onclick="window.print()">Print</button>
                                            </div>
                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                        </div>



                                    </div>

                                </div>
                                <!-- container-fluid -->

                            </div>
                            <!-- End of Main Content -->

                        </div>
                        <!-- End of Content Wrapper -->

                <?php }
                } ?>
            </div>
            <!-- End of Page Wrapper -->

            <!-- Footer -->
            <footer class="small">
                <div class="container my-auto justify-content-center">
                    <div class="copyright text-center my-auto">
                    <span>Copyright &copy; SHASSIC 2023</span>
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


</body>

</html>