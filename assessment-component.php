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

    <title>SHASSIC | Assessment</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">
    <link rel="stylesheet" type="text/css" href="css/circular-progress-bar.css">

    <script type=text/javascript>
        function documentCheck() {
            let progressBar = document.querySelector(".circular-progress-each");
            let valueContainer = document.querySelector("#progress-document-check");

            let progressValue = 0;
            let speed = 50;

            let checkc = document.getElementById("doc_check_c_score").value;
            let checkna = document.getElementById("doc_check_na_score").value;

            let progressEndValue = Math.round((((parseInt(checkc) + parseInt(checkna)) / 57) * 100));

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

            let generalCScore = document.getElementById("general_c_score").value;
            let generalNaScore = document.getElementById("general_na_score").value;
            let HighRiskCScore = document.getElementById("high_risk_c_score").value;
            let HighRiskNaScore = document.getElementById("high_risk_na_score").value;

            let progressEndValue = Math.round((((parseInt(generalCScore) + parseInt(generalNaScore) + parseInt(HighRiskCScore) + parseInt(HighRiskNaScore)) / 72) * 100));

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

            let managerialcscore = document.getElementById("managerial_c_score").value;
            let managerialnascore = document.getElementById("managerial_na_score").value;
            let supervisorycscore = document.getElementById("supervisory_c_score").value;
            let supervisorynascore = document.getElementById("supervisory_na_score").value;

            let workerc1 = document.getElementById("worker_1_c_score").value;
            let workerc2 = document.getElementById("worker_2_c_score").value;
            let workerc3 = document.getElementById("worker_3_c_score").value;
            let workerc4 = document.getElementById("worker_4_c_score").value;
            let workerc5 = document.getElementById("worker_5_c_score").value;
            let workerc6 = document.getElementById("worker_6_c_score").value;
            let workerc7 = document.getElementById("worker_7_c_score").value;
            let workerc8 = document.getElementById("worker_8_c_score").value;
            let workerc9 = document.getElementById("worker_9_c_score").value;

            let workerna1 = document.getElementById("worker_1_na_score").value;
            let workerna2 = document.getElementById("worker_2_na_score").value;
            let workerna3 = document.getElementById("worker_3_na_score").value;
            let workerna4 = document.getElementById("worker_4_na_score").value;
            let workerna5 = document.getElementById("worker_5_na_score").value;
            let workerna6 = document.getElementById("worker_6_na_score").value;
            let workerna7 = document.getElementById("worker_7_na_score").value;
            let workerna8 = document.getElementById("worker_8_na_score").value;
            let workerna9 = document.getElementById("worker_9_na_score").value;

            let workerc = parseInt(workerc1) + parseInt(workerc2) + parseInt(workerc3) + parseInt(workerc4) + parseInt(workerc5) + parseInt(workerc6) + parseInt(workerc7) + parseInt(workerc8) + parseInt(workerc9);
            let workerna = parseInt(workerna1) + parseInt(workerna2) + parseInt(workerna3) + parseInt(workerna4) + parseInt(workerna5) + parseInt(workerna6) + parseInt(workerna7) + parseInt(workerna8) + parseInt(workerna9);

            let progressEndValue = Math.round((((parseInt(managerialcscore) + parseInt(managerialnascore) + parseInt(supervisorycscore) + parseInt(supervisorynascore) + workerc + workerna) / 186) * 100));

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
                                                    <a class="font-weight-bold" href="edit-assessment.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                        < Back</a>
                                                </div>
                                            </div>
                                            <!-- Page Heading -->
                                            <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Assessment Progress</h1>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            if (isset($_SESSION['info'])) {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-success text-white shadow">
                                                        <div class="card-body text-center font-weight-bold">
                                                            <?php echo $_SESSION['info'];
                                                            unset($_SESSION['info']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if (count($errors) > 0) {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-danger text-white shadow">
                                                        <div class="card-body text-center" style="font-weight: bold;">
                                                            <?php foreach ($errors as $error) {
                                                                echo $error;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <form class="" action="" method="POST">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th><a href="assessment-document-check.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">Document Check</a></th>
                                                                <th><a href="assessment-workplace-inspection.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">Workplace Inspection</a></th>
                                                                <th><a href="assessment-personnel-interview.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">Personnel Interview</a></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <!-- value from document subscore -->
                                                            <?php
                                                            $sql = "SELECT * FROM document_check_subscore WHERE assessment_id='$result->assessee_id'";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $totalcna = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($totalcna as $totalcnas) {
                                                            ?>
                                                                    <input type="hidden" id="doc_check_c_score" value="<?php echo htmlentities($totalcnas->doc_check_c_score); ?>">
                                                                    <input type="hidden" id="doc_check_na_score" value="<?php echo htmlentities($totalcnas->doc_check_na_score); ?>">
                                                            <?php }
                                                            } ?>

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
                                                            $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id='$result->assessee_id'";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $totalcna1 = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($totalcna1 as $totalcnas1) {
                                                            ?>
                                                                    <input type="hidden" id="managerial_c_score" value="<?php echo htmlentities($totalcnas1->managerial_c_score); ?>">
                                                                    <input type="hidden" id="managerial_na_score" value="<?php echo htmlentities($totalcnas1->managerial_na_score); ?>">
                                                                    <input type="hidden" id="supervisory_c_score" value="<?php echo htmlentities($totalcnas1->supervisory_c_score); ?>">
                                                                    <input type="hidden" id="supervisory_na_score" value="<?php echo htmlentities($totalcnas1->supervisory_na_score); ?>">

                                                                    <input type="hidden" id="worker_1_c_score" value="<?php echo htmlentities($totalcnas1->worker_1_c_score); ?>">
                                                                    <input type="hidden" id="worker_1_na_score" value="<?php echo htmlentities($totalcnas1->worker_1_na_score); ?>">

                                                                    <input type="hidden" id="worker_2_c_score" value="<?php echo htmlentities($totalcnas1->worker_2_c_score); ?>">
                                                                    <input type="hidden" id="worker_2_na_score" value="<?php echo htmlentities($totalcnas1->worker_2_na_score); ?>">

                                                                    <input type="hidden" id="worker_3_c_score" value="<?php echo htmlentities($totalcnas1->worker_3_c_score); ?>">
                                                                    <input type="hidden" id="worker_3_na_score" value="<?php echo htmlentities($totalcnas1->worker_3_na_score); ?>">

                                                                    <input type="hidden" id="worker_4_c_score" value="<?php echo htmlentities($totalcnas1->worker_4_c_score); ?>">
                                                                    <input type="hidden" id="worker_4_na_score" value="<?php echo htmlentities($totalcnas1->worker_4_na_score); ?>">

                                                                    <input type="hidden" id="worker_5_c_score" value="<?php echo htmlentities($totalcnas1->worker_5_c_score); ?>">
                                                                    <input type="hidden" id="worker_5_na_score" value="<?php echo htmlentities($totalcnas1->worker_5_na_score); ?>">

                                                                    <input type="hidden" id="worker_6_c_score" value="<?php echo htmlentities($totalcnas1->worker_6_c_score); ?>">
                                                                    <input type="hidden" id="worker_6_na_score" value="<?php echo htmlentities($totalcnas1->worker_6_na_score); ?>">

                                                                    <input type="hidden" id="worker_7_c_score" value="<?php echo htmlentities($totalcnas1->worker_7_c_score); ?>">
                                                                    <input type="hidden" id="worker_7_na_score" value="<?php echo htmlentities($totalcnas1->worker_7_na_score); ?>">

                                                                    <input type="hidden" id="worker_8_c_score" value="<?php echo htmlentities($totalcnas1->worker_8_c_score); ?>">
                                                                    <input type="hidden" id="worker_8_na_score" value="<?php echo htmlentities($totalcnas1->worker_8_na_score); ?>">

                                                                    <input type="hidden" id="worker_9_c_score" value="<?php echo htmlentities($totalcnas1->worker_9_c_score); ?>">
                                                                    <input type="hidden" id="worker_9_na_score" value="<?php echo htmlentities($totalcnas1->worker_9_na_score); ?>">
                                                            <?php }
                                                            } ?>

                                                            <tr>
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
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- <form class="" action="" method="POST"> -->
                                                <div class="form-group" id="row">
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?php echo htmlentities($result->assessee_id); ?>">
                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="document_check_percentage" id="document_check_percentage" onchange="countSelected()">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
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