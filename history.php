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

    <!-- Add icon library -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        .btn {
            background-color: rgb(6, 25, 44);
            border: none;
            color: white;
            padding: 2px 2px;
            font-size: 11px;
            cursor: pointer;
        }

        Darker background on mouse-over .btn:hover {
            background-color: RoyalBlue;
        }
    </style>

    <script type=text/javascript>
        window.onload = function() {

            // let bar = document.querySelectorAll('.bar');
            let status = document.querySelectorAll('.status');

            status.forEach((progress) => {

                let calc = progress.getAttribute('data-value');

                let value = progress.getAttribute('data-value'); //valid
                let values = Math.trunc((parseInt(value) / 315) * 100);

                // alert("Total = " + values);

                if (values == 0) {
                    // progress.innerHTML = "Not Started = " + values; //valid
                    progress.innerHTML = "Not Started"; //valid
                    progress.style.color = "red";
                } else if (values > 0 && values <= 99) {
                    progress.innerHTML = "In Progress"; //valid
                    progress.style.color = "#CCCC00";
                } else if (values == 100) {
                    progress.innerHTML = "Completed"; //valid
                    progress.style.color = "green";
                }

            });

            // bar.forEach((progress) => {

            //     let value = progress.getAttribute('data-value');
            //     console.log(value);
            //     let values = Math.trunc((value / 315) * 100);

            //     progress.style.width = `${values}%`;
            //     let count = 0;

            //     let progressAnimation = setInterval(() => {
            //         if (count == values) {
            //             progress.setAttribute('data-text', `${count}%`);
            //             clearInterval(progressAnimation);
            //         } else {
            //             count++;
            //             progress.setAttribute('data-text', `${count}%`);
            //             if (count >= values) {
            //                 clearInterval(progressAnimation);
            //             }
            //         }

            //     }, 15);
            // });
        };
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
                                            <?php
                                            if (count($infos) > 0) {
                                            ?>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="card bg-success text-white shadow">
                                                        <div class="card-body text-center" style="font-weight: bold;">
                                                            <?php foreach ($infos as $info) {
                                                                echo $info;
                                                            } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } ?>
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
                                            } ?>

                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Project Image</th>
                                                            <th>Assessee</th>
                                                            <th>Project Name</th>
                                                            <th>Progress</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Project Image</th>
                                                            <th>Assessee</th>
                                                            <th>Project Name</th>
                                                            <th>Progress</th>
                                                            <th>Action</th>
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

                                                        $count = 0;
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($histories as $history) {
                                                                $assessee_id = $history->assessee_id; ?>

                                                                <!-- value from document subscore -->
                                                                <?php
                                                                $sql = "SELECT * FROM document_check_subscore WHERE assessment_id='$history->assessee_id'";
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $totalcna = $query->fetchAll(PDO::FETCH_OBJ);
                                                                if ($query->rowCount() > 0) {
                                                                    foreach ($totalcna as $totalcnas) {
                                                                ?>
                                                                        <!-- value from workplace subscore -->
                                                                        <?php
                                                                        $sql = "SELECT * FROM workplace_inspection_subscore WHERE assessment_id='$totalcnas->assessment_id'";
                                                                        $query = $dbh->prepare($sql);
                                                                        $query->execute();
                                                                        $totalcna1 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                        if ($query->rowCount() > 0) {
                                                                            foreach ($totalcna1 as $totalcnas1) {
                                                                        ?>
                                                                                <!-- value from personnel subscore -->
                                                                                <?php
                                                                                $sql = "SELECT * FROM personnel_interview_subscore WHERE assessment_id='$totalcnas->assessment_id'";
                                                                                $query = $dbh->prepare($sql);
                                                                                $query->execute();
                                                                                $totalcna2 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                if ($query->rowCount() > 0) {
                                                                                    foreach ($totalcna2 as $totalcnas2) {
                                                                                ?>
                                                                                        <tr>
                                                                                            <form class="user" method="POST" enctype="multipart/form-data">
                                                                                                <td class="project_image"> <img class="img-thumbnail" width="80" src="img/project-image/<?php echo htmlentities($history->project_picture); ?>" alt="project image" /></td>
                                                                                                <td class="assessee_name"><?php echo htmlentities($history->assessee_name); ?></td>
                                                                                                <td class="project_name"><?php echo htmlentities($history->project_name); ?></td>
                                                                                                <td class="assessement_progress" align="center">
                                                                                                    <!-- <?php echo htmlentities($history->status); ?> -->

                                                                                                    <!-- <div class="p_progress_container">
                                                                                                    <div class="progress">
                                                                                                        <div class="p_progress_item">
                                                                                                            <div class="progress_bar">
                                                                                                                <div class="bar" data-value="<?php echo htmlentities($totalcnas->doc_check_c_score)
                                                                                                                                                    + htmlentities($totalcnas->doc_check_na_score)
                                                                                                                                                    + htmlentities($totalcnas1->general_c_score)
                                                                                                                                                    + htmlentities($totalcnas1->general_na_score)
                                                                                                                                                    + htmlentities($totalcnas1->high_risk_c_score)
                                                                                                                                                    + htmlentities($totalcnas1->high_risk_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->managerial_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->managerial_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->supervisory_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->supervisory_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_1_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_1_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_2_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_2_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_3_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_3_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_4_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_4_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_5_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_5_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_6_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_6_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_7_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_7_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_8_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_8_na_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_9_c_score)
                                                                                                                                                    + htmlentities($totalcnas2->worker_9_na_score); ?>" data-text="<?php echo htmlentities($totalcnas->doc_check_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas->doc_check_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas1->general_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas1->general_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas1->high_risk_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas1->high_risk_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->managerial_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->managerial_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->supervisory_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->supervisory_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_1_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_1_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_2_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_2_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_3_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_3_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_4_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_4_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_5_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_5_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_6_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_6_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_7_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_7_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_8_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_8_na_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_9_c_score)
                                                                                                                                                                                                                        + htmlentities($totalcnas2->worker_9_na_score);
                                                                                                                                                                                                                    ?>">
                                                                                                                </div>

                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div> -->

                                                                                                    <div class="status" data-value="<?php echo htmlentities($totalcnas->doc_check_c_score)
                                                                                                                                        + htmlentities($totalcnas->doc_check_na_score)
                                                                                                                                        + htmlentities($totalcnas1->general_c_score)
                                                                                                                                        + htmlentities($totalcnas1->general_na_score)
                                                                                                                                        + htmlentities($totalcnas1->high_risk_c_score)
                                                                                                                                        + htmlentities($totalcnas1->high_risk_na_score)
                                                                                                                                        + htmlentities($totalcnas2->managerial_c_score)
                                                                                                                                        + htmlentities($totalcnas2->managerial_na_score)
                                                                                                                                        + htmlentities($totalcnas2->supervisory_c_score)
                                                                                                                                        + htmlentities($totalcnas2->supervisory_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_1_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_1_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_2_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_2_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_3_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_3_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_4_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_4_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_5_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_5_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_6_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_6_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_7_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_7_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_8_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_8_na_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_9_c_score)
                                                                                                                                        + htmlentities($totalcnas2->worker_9_na_score); ?>" data-text="<?php echo htmlentities($totalcnas->doc_check_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas->doc_check_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas1->general_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas1->general_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas1->high_risk_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas1->high_risk_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->managerial_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->managerial_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->supervisory_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->supervisory_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_1_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_1_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_2_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_2_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_3_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_3_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_4_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_4_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_5_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_5_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_6_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_6_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_7_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_7_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_8_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_8_na_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_9_c_score)
                                                                                                                                                                                                            + htmlentities($totalcnas2->worker_9_na_score);
                                                                                                                                                                                                        ?>">
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>


                                                                                                    <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?php echo htmlentities($assessee_id); ?>">
                                                                                                    <!-- <button class="btn" onclick="window.location='edit-assessment-from-history.php?assessee_id=<?php echo htmlentities($history->assessee_id); ?>';"><i class="material-icons">edit</i></button> -->
                                                                                                    <button type="submit" class="btn" name="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">edit</i></button>

                                                                                                    <!-- <button class="btn" onclick="window.location='print.php?assessee_id=<?php echo htmlentities($history->assessee_id); ?>';"><i class="material-icons">print</i></button> -->
                                                                                                    <button type="submit" class="btn" name="print" title="Print" data-toggle="tooltip"><i class="material-icons">print</i></button>

                                                                                                    <button type="submit" class="btn" name="delete" title="Delete" data-toggle="tooltip" onclick="return confirm('Do you really want to delete ?');"><i class="material-icons">delete</i></button>
                                                                                                    <!-- <a href="history.php?delete_id=<?php echo htmlentities($history->assessee_id); ?>"><button class="btn" name="delete" title="Delete" data-toggle="tooltip" onclick="return confirm('Do you really want to delete ?');"><i class="material-icons">delete</i></button></a> -->
                                                                                                    <!-- <button class="btn" onclick="window.location='history.php?delete_id=<?php echo htmlentities($history->assessee_id); ?>';" name="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">delete</i></button> -->
                                                                                                </td>
                                                                                            </form>
                                                                                        </tr>
                                                                                <?php }
                                                                                } ?>
                                                                        <?php }
                                                                        } ?>
                                                                <?php }
                                                                } ?>
                                                        <?php }
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
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
</body>

</html>