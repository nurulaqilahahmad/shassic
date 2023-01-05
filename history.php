<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SHASSIC | History</title>

    <link rel="icon" type="image/x-icon" href="img/favicon.png">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/landing.css">
    <link rel="stylesheet" type="text/css" href="css/progress-bar.css">

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
                $sql = "SELECT * from user where email=:email";
                $query = $dbh->prepare($sql);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
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
                                <!-- <div class="container-fluid"> -->

                                <!-- Begin Page Content -->
                                <div class="container-fluid">

                                    <!-- History Data Table -->
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                                <a class="font-weight-bold" href="index.php">
                                                    < Back</a>
                                            </div>
                                            <h6 class="h3 mb-4 text-gray-800 font-weight-bold">Assessment History</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Project Image</th>
                                                            <th>Assessee</th>
                                                            <th>Project Name</th>
                                                            <th>Progress</th>
                                                            <th>Edit</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Project Image</th>
                                                            <th>Assessee</th>
                                                            <th>Project Name</th>
                                                            <th>Progress</th>
                                                            <th>Edit</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                        $assessor_id = $result->id;
                                                        $sql = "SELECT * FROM `assessment` WHERE `assessor_id`=:assessor_id";
                                                        $query = $dbh->prepare($sql);
                                                        $query->bindParam(':assessor_id', $assessor_id, PDO::PARAM_STR);
                                                        $query->execute();
                                                        $histories = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($histories as $history) { ?>
                                                                <tr>
                                                                    <td class="project_image"> <img class="img-thumbnail" width="100" src="img/project-image/<?php echo htmlentities($history->project_picture); ?>" alt="project image" /></td>
                                                                    <td class="assessee_name"><?php echo htmlentities($history->assessee_name); ?></td>
                                                                    <td class="project_name"><?php echo htmlentities($history->project_name); ?></td>
                                                                    <td class="assessement_progress" align="center">
                                                                        <div class="outer-container">
                                                                            <div class="circular-progress">
                                                                                <div id="progress-num">
                                                                                    <!-- <?php echo htmlentities($history->document_check_percentage); ?> -->
                                                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="input-percentage" id="input-percentage" value="<?php echo htmlentities($history->document_check_percentage); ?>">
                                                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="max-percentage" id="max-percentage" value="100">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-primary btn-user btn-block font-weight-bold" onclick="window.location='edit-assessment-from-history.php?assessee_id=<?php echo htmlentities($history->assessee_id); ?>';">Edit</button>
                                                                        <button class="btn btn-primary btn-user btn-block font-weight-bold" onclick="window.location='print.php?assessee_id=<?php echo htmlentities($history->assessee_id); ?>';">Print</button>
                                                                    </td>
                                                                </tr>
                                                        <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- /.container-fluid -->

                                <!-- </div> -->
                                <!-- End of Content Wrapper -->

                        <?php }
                } ?>

                            </div>
                            <!-- End of Page Wrapper -->

                            <!-- Footer -->
                            <footer class="small">
                                <div class="container my-auto">
                                    <div class="copyright text-center my-auto">
                                        <span>Copyright &copy; Your Website 2020</span>
                                    </div>
                                </div>
                            </footer>
                            <!-- End of Footer -->

                            <!-- Scroll to Top Button-->
                            <a class="scroll-to-top rounded" href="#page-top">
                                <i class="fas fa-angle-up"></i>
                            </a>

                            <!-- Bootstrap core JavaScript-->
                            <script src="vendor/jquery/jquery.min.js"></script>
                            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                            <!-- Core plugin JavaScript-->
                            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                            <!-- Custom scripts for all pages-->
                            <script src="js/sb-admin-2.min.js"></script>

                            <!-- Page level plugins -->
                            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
                            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

                            <!-- Page level custom scripts -->
                            <script src="js/demo/datatables-demo.js"></script>

                        </div>
            </div>
        </div>

    <?php } else {
        header("location: login.php");
    } ?>

    <script>
        let progressBar = document.querySelector(".circular-progress");
        let valueContainer = document.querySelector("#progress-num");

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
    </script>

</body>

</html>