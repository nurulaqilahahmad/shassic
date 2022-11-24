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

    <title>SHASSIC | Personnel Interview</title>

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
                                                                    <div class="text-center" style="display:flex; width:auto; justify-content: start;">
                                                                        <a class="font-weight-bold" href="assessment-component.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                            &larr; Back</a>
                                                                    </div>

                                                                    <!-- Page Heading -->
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Personnel Interview</h1>
                                                                    <div class="tab form-group">
                                                                        <button class="tablinks font-weight-bold" style="width: 33%;" onclick="openSection(event, 'managerial')">Managerial</button>
                                                                        <button class="tablinks font-weight-bold" style="width: 33%;" onclick="openSection(event, 'supervisory')">Supervisory</button>
                                                                        <button class="tablinks font-weight-bold" style="width: 33%;" onclick="openSection(event, 'workers')">Workers</button>
                                                                    </div>
                                                                </div>

                                                                <!-- <form class="user" method="POST"> -->
                                                                <div id="managerial" class="tabcontent">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th rowspan="2" class="align-middle">ITEM</th>
                                                                                    <th rowspan="2" class="align-middle">CHECKLIST</th>
                                                                                    <th colspan="3">MANAGERIAL</th>
                                                                                    <th rowspan="2" class="align-middle">Remarks</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>C</th>
                                                                                    <th>NC</th>
                                                                                    <th>NA</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <th colspan="2">SUB SCORE</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </tfoot>
                                                                            <tbody>
                                                                                <?php
                                                                                $sql = "SELECT * from personnel_interview_category where id=1";
                                                                                $query = $dbh->prepare($sql);
                                                                                $query->execute();
                                                                                $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                $cnt = 1;
                                                                                if ($query->rowCount() > 0) {
                                                                                    foreach ($categories as $category) {
                                                                                ?>
                                                                                        <?php
                                                                                        $sql = "SELECT * from personnel_interview_checklist where category_id='$category->id'";
                                                                                        $query = $dbh->prepare($sql);
                                                                                        $query->execute();
                                                                                        $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                        $cnt = 1;
                                                                                        if ($query->rowCount() > 0) {
                                                                                            foreach ($checklists as $checklist) {
                                                                                        ?>
                                                                                                <tr>
                                                                                                    <td><?php echo htmlentities($cnt++) ?></td>
                                                                                                    <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                </tr>
                                                                                        <?php }
                                                                                        } ?>
                                                                                <?php }
                                                                                } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview">Save</button>
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                    </div>
                                                                </div>
                                                                <!-- </form> -->

                                                                <!-- <form class="user" method="POST"> -->
                                                                <div id="supervisory" class="tabcontent">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th rowspan="2" class="align-middle">ITEM</th>
                                                                                    <th rowspan="2" class="align-middle">QUESTIONNAIRE</th>
                                                                                    <th colspan="3">PERSONNEL 1</th>
                                                                                    <th colspan="3">PERSONNEL 2</th>
                                                                                    <th colspan="3">PERSONNEL 3</th>
                                                                                    <th rowspan="2" class="align-middle">REMARKS</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>C</th>
                                                                                    <th>NC</th>
                                                                                    <th>NA</th>
                                                                                    <th>C</th>
                                                                                    <th>NC</th>
                                                                                    <th>NA</th>
                                                                                    <th>C</th>
                                                                                    <th>NC</th>
                                                                                    <th>NA</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <th colspan="2">SUB SCORE</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th>0</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </tfoot>
                                                                            <tbody>
                                                                                <?php
                                                                                $sql = "SELECT * from personnel_interview_category where id=2";
                                                                                $query = $dbh->prepare($sql);
                                                                                $query->execute();
                                                                                $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                $cnt = 1;
                                                                                if ($query->rowCount() > 0) {
                                                                                    foreach ($categories as $category) {
                                                                                ?>
                                                                                        <?php
                                                                                        $sql = "SELECT * from personnel_interview_checklist where category_id='$category->id'";
                                                                                        $query = $dbh->prepare($sql);
                                                                                        $query->execute();
                                                                                        $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                        $cnt = 1;
                                                                                        if ($query->rowCount() > 0) {
                                                                                            foreach ($checklists as $checklist) {
                                                                                        ?>
                                                                                                <tr>
                                                                                                    <td><?php echo htmlentities($cnt++) ?></td>
                                                                                                    <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                    <td></td>
                                                                                                </tr>
                                                                                        <?php }
                                                                                        } ?>
                                                                                <?php }
                                                                                } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview">Save</button>
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                    </div>
                                                                </div>
                                                                <!-- </form> -->

                                                                <!-- <form class="user" method="POST"> -->
                                                                <div id="workers" class="tabcontent">
                                                                    <div class="tab form-group">
                                                                        <button class="tablinks2 font-weight-bold" style="width: 33%;" onclick="openWorker(event, 'worker1')">Worker 1</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 33%;" onclick="openWorker(event, 'worker2')">Worker 2</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 33%;" onclick="openWorker(event, 'worker3')">Worker 3</button>
                                                                    </div>
                                                                    <div id="worker1" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>ITEM</th>
                                                                                        <th>QUESTIONNAIRE</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>REMARKS</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2">SUB SCORE</th>
                                                                                        <th>0</th>
                                                                                        <th>0</th>
                                                                                        <th>0</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $sql = "SELECT * from personnel_interview_category where id=3";
                                                                                    $query = $dbh->prepare($sql);
                                                                                    $query->execute();
                                                                                    $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                    $cnt = 1;
                                                                                    if ($query->rowCount() > 0) {
                                                                                        foreach ($categories as $category) {
                                                                                    ?>
                                                                                            <?php
                                                                                            $sql = "SELECT * from personnel_interview_checklist where category_id='$category->id'";
                                                                                            $query = $dbh->prepare($sql);
                                                                                            $query->execute();
                                                                                            $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                            $cnt = 1;
                                                                                            if ($query->rowCount() > 0) {
                                                                                                foreach ($checklists as $checklist) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo htmlentities($cnt++) ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                            } ?>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-workplace-inspection">Save</button>
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                        </div>
                                                                    </div>

                                                                    <div id="worker2" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>ITEM</th>
                                                                                        <th>QUESTIONNAIRE</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>REMARKS</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2">SUB SCORE</th>
                                                                                        <th>0</th>
                                                                                        <th>0</th>
                                                                                        <th>0</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $sql = "SELECT * from personnel_interview_category where id=3";
                                                                                    $query = $dbh->prepare($sql);
                                                                                    $query->execute();
                                                                                    $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                    $cnt = 1;
                                                                                    if ($query->rowCount() > 0) {
                                                                                        foreach ($categories as $category) {
                                                                                    ?>
                                                                                            <?php
                                                                                            $sql = "SELECT * from personnel_interview_checklist where category_id='$category->id'";
                                                                                            $query = $dbh->prepare($sql);
                                                                                            $query->execute();
                                                                                            $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                            $cnt = 1;
                                                                                            if ($query->rowCount() > 0) {
                                                                                                foreach ($checklists as $checklist) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo htmlentities($cnt++) ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                            } ?>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-workplace-inspection">Save</button>
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                        </div>
                                                                    </div>

                                                                    <div id="worker3" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>ITEM</th>
                                                                                        <th>QUESTIONNAIRE</th>
                                                                                        <th>C</th>
                                                                                        <th>NC</th>
                                                                                        <th>NA</th>
                                                                                        <th>REMARKS</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2">SUB SCORE</th>
                                                                                        <th>0</th>
                                                                                        <th>0</th>
                                                                                        <th>0</th>
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $sql = "SELECT * from personnel_interview_category where id=3";
                                                                                    $query = $dbh->prepare($sql);
                                                                                    $query->execute();
                                                                                    $categories = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                    $cnt = 1;
                                                                                    if ($query->rowCount() > 0) {
                                                                                        foreach ($categories as $category) {
                                                                                    ?>
                                                                                            <?php
                                                                                            $sql = "SELECT * from personnel_interview_checklist where category_id='$category->id'";
                                                                                            $query = $dbh->prepare($sql);
                                                                                            $query->execute();
                                                                                            $checklists = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                            $cnt = 1;
                                                                                            if ($query->rowCount() > 0) {
                                                                                                foreach ($checklists as $checklist) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo htmlentities($cnt++) ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                        <td></td>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                            } ?>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview">Save</button>
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- </form> -->
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
    <script>
        function openSection(evt, sectionName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(sectionName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function openWorker(event, workerName) {
            var i, tabcontent2, tablinks2;
            tabcontent2 = document.getElementsByClassName("tabcontent2");
            for (i = 0; i < tabcontent2.length; i++) {
                tabcontent2[i].style.display = "none";
            }
            tablinks2 = document.getElementsByClassName("tablinks2");
            for (i = 0; i < tablinks2.length; i++) {
                tablinks2[i].className = tablinks2[i].className.replace(" active", "");
            }
            document.getElementById(workerName).style.display = "block";
            event.currentTarget.className += " active";
        }
    </script>
</body>

</html>