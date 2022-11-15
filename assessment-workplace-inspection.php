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

    <title>SHASSIC | Workplace Inspection</title>

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
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Workplace Inspection</h1>
                                                                </div>

                                                                <!-- <form class="user" method="POST"> -->
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Item</th>
                                                                                <th>Checklist</th>
                                                                                <th>C</th>
                                                                                <th>NC</th>
                                                                                <th>NA</th>
                                                                                <th>Remarks</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <th colspan="2">TOTAL SCORE</th>
                                                                                <th>00</th>
                                                                                <th>00</th>
                                                                                <th>00</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </tfoot>
                                                                        <tbody>
                                                                            <?php
                                                                            $sql = "SELECT * from workplace_inspection_section where id between 1 and 3";
                                                                            $query = $dbh->prepare($sql);
                                                                            $query->execute();
                                                                            $sections = $query->fetchAll(PDO::FETCH_OBJ);
                                                                            $cnt = 1;
                                                                            if ($query->rowCount() > 0) {
                                                                                foreach ($sections as $section) {
                                                                            ?>
                                                                                    <tr>
                                                                                        <th><?php echo htmlentities($section->item_no) ?></th>
                                                                                        <th colspan="5" class="text-left"><?php echo htmlentities($section->item_name) ?></th>
                                                                                    </tr>
                                                                                    <?php
                                                                                    $sql = "SELECT * from workplace_inspection_checklist where item_id='$section->id'";
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
                                                                                                <td><?php echo htmlentities($checklist->c_status) ?></td>
                                                                                                <td><?php echo htmlentities($checklist->nc_status) ?></td>
                                                                                                <td><?php echo htmlentities($checklist->na_status) ?></td>
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
                                                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="add">Save</button>
                                                                    </div>
                                                                    <div class="col-sm-4 mb-3 mb-sm-0"></div>
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
</body>

</html>