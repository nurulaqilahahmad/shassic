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
    <link rel="stylesheet" type="text/css" href="css/progress-bar.css">

    <script type=text/javascript>
        function documentCheck() {
            let progressBar = document.querySelector(".circular-progress-each");
            let valueContainer = document.querySelector("#progress-document-check");

            let progressValue = 0;
            let progressEndValue = document.getElementById("input-percentage").value;
            let speed = 200;

            let progress = setInterval(() => {
                progressValue++;
                valueContainer.textContent = `${progressValue}%`;
                progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                if (progressValue == progressEndValue) {
                    clearInterval(progress);
                }
            }, speed);
        }

        function workplaceInspection() {
            let progressBar = document.querySelector(".circular-progress-each-1");
            let valueContainer = document.querySelector("#progress-document-workplace");

            let progressValue = 0;
            let progressEndValue = document.getElementById("workplace-inspection").value;
            let speed = 200;

            let progress = setInterval(() => {
                progressValue++;
                valueContainer.textContent = `${progressValue}%`;
                progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                if (progressValue == progressEndValue) {
                    clearInterval(progress);
                }
            }, speed);
        }

        function personnelInterview() {
            let progressBar = document.querySelector(".circular-progress-each-2");
            let valueContainer = document.querySelector("#progress-document-personnel");

            let progressValue = 0;
            let progressEndValue = document.getElementById("personnel-interview").value;
            let speed = 200;

            let progress = setInterval(() => {
                progressValue++;
                valueContainer.textContent = `${progressValue}%`;
                progressBar.style.background = `conic-gradient(
                #4d5bf9 ${progressValue * 3.6}deg,
                #cadcff ${progressValue * 3.6}deg
            )`;
                if (progressValue == progressEndValue) {
                    clearInterval(progress);
                }
            }, speed);
        }
    </script>

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

                                    <!-- Outer Row -->
                                    <div class="row justify-content-center">


                                        <div class="col-xl-12 col-lg-12 col-md-9">
                                            <div class="card o-hidden border-0 shadow-lg my-5">
                                                <div class="p-0" id="card-body">
                                                    <div id="row">
                                                        <div class="col-lg-12">
                                                            <div class="p-5">
                                                                <div class="text-center">
                                                                    <div class="text-center" id="row">
                                                                        <div class="col-sm-6" style="display:flex; width:auto; justify-content: start;">
                                                                            <a class="font-weight-bold" href="edit-assessment-from-history.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                                < Back</a>
                                                                        </div>
                                                                        <!-- <div class="col-sm-6" style="display:flex; width:auto; justify-content: end;">
                                                                            <a class="font-weight-bold" href="#">
                                                                                Next &rarr;</a>
                                                                        </div> -->
                                                                    </div>

                                                                    <!-- Page Heading -->
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold"><?php echo htmlentities($result->assessee_name); ?> - <?php echo htmlentities($result->project_name); ?></h1>
                                                                    <?php
                                                                    if ($_SESSION['info'] != "") {
                                                                    ?>
                                                                        <div class="col-lg-12 mb-4">
                                                                            <div class="card bg-success text-white shadow">
                                                                                <div class="card-body text-center font-weight-bold">
                                                                                    <?php echo $_SESSION['info']; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php
                                                                    } else {
                                                                        $_SESSION['info'] = "";
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
                                                                </div>

                                                                <form class="user" method="POST">
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <a href="assessment-document-check.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                                <div class="card mb-4">
                                                                                    <div class="card-body card-hover py-3">
                                                                                        <h6 class="m-0 font-weight-bold">Document Check</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
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
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <a href="assessment-workplace-inspection.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                                <div class="card mb-4">
                                                                                    <div class="card-body card-hover py-3">
                                                                                        <h6 class="m-0 font-weight-bold">Workplace Inspection</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
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
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <a href="assessment-personnel-interview.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                                <div class="card mb-4">
                                                                                    <div class="card-body card-hover py-3">
                                                                                        <h6 class="m-0 font-weight-bold">Personnel Interview</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
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
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>

                                </div>
                                <!-- /.container-fluid -->

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