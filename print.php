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
    <link rel="stylesheet" type="text/css" href="css/circular-progress-bar.css">
    <link rel="stylesheet" type="text/css" href="css/star-rantings.css">

    <script type=text/javascript>
        function documentCheck() {
            let progressBar = document.querySelector(".circular-progress-each");
            let valueContainer = document.querySelector("#progress-document-check");

            var progressValue = 0;
            let progressEndValue = document.getElementById("input-percentage").value;
            let speed = 50;

            let progress = setInterval(() => {
                if (progressValue == progressEndValue) {
                    valueContainer.textContent = `${progressValue}%`;
                    progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                    clearInterval(progress);
                } else {
                    progressValue++;
                    valueContainer.textContent = `${progressValue}%`;
                    progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                    if (progressValue == progressEndValue) {
                        clearInterval(progress);
                    }
                }
            }, speed);

        }

        function workplaceInspection() {
            let progressBar = document.querySelector(".circular-progress-each-1");
            let valueContainer = document.querySelector("#progress-document-workplace");

            let progressValue = 0;
            // let progressEndValue = document.getElementById("workplace-inspection").value;

            let generalCScore = document.getElementById("general_c_score").value;
            let generalNaScore = document.getElementById("general_na_score").value;
            let HighRiskCScore = document.getElementById("high_risk_c_score").value;
            let HighRiskNaScore = document.getElementById("high_risk_na_score").value;

            let progressEndValue = Math.round((((parseInt(generalCScore) + parseInt(generalNaScore) + parseInt(HighRiskCScore) + parseInt(HighRiskNaScore)) / 72) * 100));
            console.log(progressEndValue);

            let speed = 50;

            let progress = setInterval(() => {
                if (progressValue == progressEndValue) {
                    valueContainer.textContent = `${progressValue}%`;
                    progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                    clearInterval(progress);
                } else {
                    progressValue++;
                    valueContainer.textContent = `${progressValue}%`;
                    progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                    if (progressValue == progressEndValue) {
                        clearInterval(progress);
                    }
                }
            }, speed);
        }

        function personnelInterview() {
            let progressBar = document.querySelector(".circular-progress-each-2");
            let valueContainer = document.querySelector("#progress-document-personnel");

            let progressValue = 0;
            let progressEndValue = document.getElementById("personnel-interview").value;
            let speed = 50;

            let progress = setInterval(() => {
                if (progressValue == progressEndValue) {
                    valueContainer.textContent = `${progressValue}%`;
                    progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                    clearInterval(progress);
                } else {
                    progressValue++;
                    valueContainer.textContent = `${progressValue}%`;
                    progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                    if (progressValue == progressEndValue) {
                        clearInterval(progress);
                    }
                }
            }, speed);
        }

        // Get ratings
        function getRankings() {

            // Initial Ratings
            let ratings = document.getElementById("rating-control").value;

            // Total Stars
            const starsTotal = 5;

            // Get percentage
            const starPercentage = (ratings / starsTotal) * 100;

            // Round to nearest 10
            const starPercentageRounded = `${Math.round(starPercentage / 10) * 10}%`;

            // Set width of stars-inner to percentage
            document.querySelector(`.stars-inner`).style.width = starPercentageRounded;

            // Add number rating
            document.querySelector(`.number-rating`).innerHTML = starPercentageRounded;
        }
    </script>


</head>

<body id="page-top">

    <?php if ($_SESSION['login']) { ?>

        <div class="landing-container">
            <div class="landing-navbar">
                <img src="img/landing/logo.png" class="landing-logo">
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
                                            <div class="text-center">
                                                <div class="col-sm-6" style="display:flex; width:auto; justify-content: start;">
                                                    <a class="font-weight-bold" href="history.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                        < Back</a>
                                                </div>
                                                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Assessment Result</h1>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <form class="" action="" method="POST">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Check</th>
                                                                <th>Workplace Inspection</th>
                                                                <th>Personnel Interview</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- value from workplace subscore -->
                                                            <?php
                                                            $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id='$result->assessee_id'";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $totalcna = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($totalcna as $totalcnas) {
                                                            ?>
                                                                    <input type="hidden" id="general_c_score" value="<?php echo htmlentities($totalcnas->general_c_score); ?>">
                                                                    <input type="hidden" id="general_na_score" value="<?php echo htmlentities($totalcnas->general_na_score); ?>">
                                                                    <input type="hidden" id="high_risk_c_score" value="<?php echo htmlentities($totalcnas->high_risk_c_score); ?>">
                                                                    <input type="hidden" id="high_risk_na_score" value="<?php echo htmlentities($totalcnas->high_risk_na_score); ?>">
                                                            <?php }
                                                            } ?>

                                                            <!-- value from personnel subscore -->
                                                            <?php
                                                            $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id='$result->assessee_id'";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $totalcna = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($totalcna as $totalcnas) {
                                                            ?>
                                                                    <input type="hidden" id="general_c_score" value="<?php echo htmlentities($totalcnas->general_c_score); ?>">
                                                                    <input type="hidden" id="general_na_score" value="<?php echo htmlentities($totalcnas->general_na_score); ?>">
                                                                    <input type="hidden" id="high_risk_c_score" value="<?php echo htmlentities($totalcnas->high_risk_c_score); ?>">
                                                                    <input type="hidden" id="high_risk_na_score" value="<?php echo htmlentities($totalcnas->high_risk_na_score); ?>">
                                                            <?php }
                                                            } ?>

                                                            <td>
                                                                <div class="outer-container" align="center">
                                                                    <div class="circular-progress-each">
                                                                        <div id="progress-document-check">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="input-percentage" id="input-percentage" value="<?php echo htmlentities($result->document_check_percentage); ?>">
                                                                            <?php
                                                                            echo '<script type="text/javascript"> documentCheck(); </script>';
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="outer-container" align="center">
                                                                    <div class="circular-progress-each-1">
                                                                        <div id="progress-document-workplace">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="workplace-inspection" id="workplace-inspection" value="<?php echo htmlentities($result->workplace_inspection_percentage); ?>">
                                                                            <?php
                                                                            echo '<script type="text/javascript"> workplaceInspection(); </script>';
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="outer-container" align="center">
                                                                    <div class="circular-progress-each-2">
                                                                        <div id="progress-document-personnel">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel-interview" id="personnel-interview" value="<?php echo htmlentities($result->personnel_interview_percentage); ?>">
                                                                            <?php
                                                                            echo '<script type="text/javascript"> personnelInterview(); </script>';
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <!-- For ranking star -->
                                                            <tr class="star">
                                                                <td colspan="3">

                                                                    <div class="stars-outer">
                                                                        <div class="stars-inner"></div>
                                                                    </div>
                                                                    <div>Total Score:
                                                                        <span class="number-rating"></span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?php echo htmlentities($result->assessee_id); ?>">
                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="document_check_percentage" id="document_check_percentage" onchange="countSelected()">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-document-check">Print</button>
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                </div>

                                                <!-- ID for ranking star -->
                                                <div id="assessee-select">
                                                    <input type="hidden" value="<?php echo htmlentities($result->assessee_name); ?>">
                                                    <input type="hidden" id="rating-control" value="<?php echo htmlentities($result->star_ranking); ?>">
                                                    <?php
                                                    echo '<script type="text/javascript"> getRankings(); </script>';
                                                    ?>
                                                </div>
                                            </form>

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


</body>

</html>