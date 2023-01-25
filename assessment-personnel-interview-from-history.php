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
                <img src="img/landing/menu.png" class="menu-icon" onclick="togglemenu()">
            </div>
            <!-- End of Landing Navbar -->

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
                                                                        <a class="font-weight-bold" href="assessment-component-from-history.php?assessee_id=<?php echo htmlentities($result->assessee_id); ?>">
                                                                            < Back</a>
                                                                    </div>
                                                                    <!-- End of Text Center with style -->

                                                                    <!-- Page Heading -->
                                                                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Personnel Interview</h1><?php
                                                                                                                                                if (count($infos) > 0) {
                                                                                                                                                ?>
                                                                        <div class="col-lg-12 mb-4">
                                                                            <div class="card bg-success text-white shadow">
                                                                                <div class="card-body text-center font-weight-bold">
                                                                                    <?php foreach ($infos as $info) {
                                                                                                                                                        echo $info;
                                                                                                                                                    } ?>
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
                                                                    <div class="tab form-group">
                                                                        <button class="tablinks font-weight-bold" style="width: 33%;" onclick="openSection(event, 'managerial')">Managerial</button>
                                                                        <button class="tablinks font-weight-bold" style="width: 33%;" onclick="openSection(event, 'supervisory')">Supervisory</button>
                                                                        <button class="tablinks font-weight-bold" style="width: 33%;" onclick="openSection(event, 'workers')">Workers</button>
                                                                    </div>
                                                                </div>
                                                                <!-- End of Text Center -->

                                                                <div id="managerial" class="tabcontent">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                            <form class="user" method="POST" id="personnel-interview-managerial">
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
                                                                                                    $checklist_id = $checklist->id;
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                        <td><?= $cnt++ ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM personnel_interview_managerial WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $managerials = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($managerials as $managerial) {
                                                                                                        ?>
                                                                                                                <td><input type="radio" class="checkbox1" name="managerial_<?= $checklist_id ?>[]" value="C" onclick="countSelected()" <?php if (in_array("C", explode(", ", $managerial->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox2" name="managerial_<?= $checklist_id ?>[]" value="NC" onclick="countSelected()" <?php if (in_array("NC", explode(", ", $managerial->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox3" name="managerial_<?= $checklist_id ?>[]" value="NA" onclick="countSelected()" <?php if (in_array("NA", explode(", ", $managerial->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><textarea form="personnel-interview-managerial" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $managerial->remarks ?></textarea></td>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                                        } ?>
                                                                                    <?php }
                                                                                            } ?>
                                                                            <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2">SUB SCORE</th>
                                                                                        <th id="selectedC">
                                                                                            <script>
                                                                                                document.getElementById('selectedC').innerHTML = document.querySelectorAll('input[class="checkbox1"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC').innerHTML = document.querySelectorAll('input[class="checkbox2"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA').innerHTML = document.querySelectorAll('input[class="checkbox3"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedTotal"></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                        </table>
                                                                    </div>
                                                                    <!-- End of Table Responsive -->
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="managerial_c_score" id="managerial_c_score" onchange="countSelected()">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="managerial_na_score" id="managerial_na_score" onchange="countSelected()">
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-managerial-from-history">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                                <!-- End of Managerial Tab Content -->

                                                                <div id="supervisory" class="tabcontent">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                            <form class="user" method="POST" id="personnel-interview-supervisory">
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
                                                                                                    $checklist_id = $checklist->id;
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                        <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                        <td><?= $cnt++ ?></td>
                                                                                                        <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM personnel_interview_supervisory_1 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $supervisories1 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($supervisories1 as $supervisory1) {
                                                                                                        ?>
                                                                                                                <td><input type="radio" class="checkbox4" id="C" name="supervisory1_<?= $checklist_id ?>[]" onclick="countSelected()" value="C" <?php if (in_array("C", explode(", ", $supervisory1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox5" id="NC" name="supervisory1_<?= $checklist_id ?>[]" onclick="countSelected()" value="NC" <?php if (in_array("NC", explode(", ", $supervisory1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox6" id="NA" name="supervisory1_<?= $checklist_id ?>[]" onclick="countSelected()" value="NA" <?php if (in_array("NA", explode(", ", $supervisory1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM personnel_interview_supervisory_2 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $supervisories2 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($supervisories2 as $supervisory2) {
                                                                                                        ?>
                                                                                                                <td><input type="radio" class="checkbox7" id="C" name="supervisory2_<?= $checklist_id ?>[]" onclick="countSelected()" value="C" <?php if (in_array("C", explode(", ", $supervisory2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox8" id="NC" name="supervisory2_<?= $checklist_id ?>[]" onclick="countSelected()" value="NC" <?php if (in_array("NC", explode(", ", $supervisory2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox9" id="NA" name="supervisory2_<?= $checklist_id ?>[]" onclick="countSelected()" value="NA" <?php if (in_array("NA", explode(", ", $supervisory2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                        <?php
                                                                                                        $sql = "SELECT * FROM personnel_interview_supervisory_3 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                        $query = $dbh->prepare($sql);
                                                                                                        $query->execute();
                                                                                                        $supervisories3 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                        if ($query->rowCount() > 0) {
                                                                                                            foreach ($supervisories3 as $supervisory3) {
                                                                                                        ?>
                                                                                                                <td><input type="radio" class="checkbox10" id="C" name="supervisory3_<?= $checklist_id ?>[]" onclick="countSelected()" value="C" <?php if (in_array("C", explode(", ", $supervisory3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox11" id="NC" name="supervisory3_<?= $checklist_id ?>[]" onclick="countSelected()" value="NC" <?php if (in_array("NC", explode(", ", $supervisory3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><input type="radio" class="checkbox12" id="NA" name="supervisory3_<?= $checklist_id ?>[]" onclick="countSelected()" value="NA" <?php if (in_array("NA", explode(", ", $supervisory3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                <td><textarea form="personnel-interview-supervisory" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $supervisory3->remarks ?></textarea></td>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                    </tr>
                                                                                            <?php }
                                                                                            } ?>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="2" class="text-right">SUB SCORE</th>
                                                                                        <th id="selectedC2">
                                                                                            <script>
                                                                                                document.getElementById('selectedC2').innerHTML = document.querySelectorAll('input[class="checkbox4"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC2">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC2').innerHTML = document.querySelectorAll('input[class="checkbox5"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA2">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA2').innerHTML = document.querySelectorAll('input[class="checkbox6"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedC3">
                                                                                            <script>
                                                                                                document.getElementById('selectedC3').innerHTML = document.querySelectorAll('input[class="checkbox7"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC3">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC3').innerHTML = document.querySelectorAll('input[class="checkbox8"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA3">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA3').innerHTML = document.querySelectorAll('input[class="checkbox9"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedC4">
                                                                                            <script>
                                                                                                document.getElementById('selectedC4').innerHTML = document.querySelectorAll('input[class="checkbox10"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNC4">
                                                                                            <script>
                                                                                                document.getElementById('selectedNC4').innerHTML = document.querySelectorAll('input[class="checkbox11"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedNA4">
                                                                                            <script>
                                                                                                document.getElementById('selectedNA4').innerHTML = document.querySelectorAll('input[class="checkbox12"]:checked').length
                                                                                            </script>
                                                                                        </th>
                                                                                        <th id="selectedTotal2"></th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                        </table>
                                                                    </div>
                                                                    <!-- End of Table Responsive -->
                                                                    <div class="form-group" id="row">
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="supervisory_c_score" id="supervisory_c_score" onchange="countSelected()">
                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="supervisory_na_score" id="supervisory_na_score" onchange="countSelected()">
                                                                        </div>
                                                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                                                            <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-supervisory-from-history">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <!-- End of Form Group -->
                                                                    </form>
                                                                </div>
                                                                <!-- End of Supervisory Tab Content -->


                                                                <div id="workers" class="tabcontent">
                                                                    <div class="tab form-group">
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker1')">Worker<br>1</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker2')">Worker<br>2</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker3')">Worker<br>3</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker4')">Worker<br>4</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker5')">Worker<br>5</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker6')">Worker<br>6</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker7')">Worker<br>7</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker8')">Worker<br>8</button>
                                                                        <button class="tablinks2 font-weight-bold" style="width: 10%; font-size: 14px;" onclick="openWorker(event, 'worker9')">Worker<br>9</button>
                                                                    </div>
                                                                    <!-- Tab for Worker 1 -->
                                                                    <div id="worker1" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-1">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_1 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers1 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers1 as $worker1) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox13" onclick="countSelected()" name="worker1_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox14" onclick="countSelected()" name="worker1_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox15" onclick="countSelected()" name="worker1_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker1->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-1" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker1->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC5">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC5').innerHTML = document.querySelectorAll('input[class="checkbox13"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC5">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC5').innerHTML = document.querySelectorAll('input[class="checkbox14"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA5">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA5').innerHTML = document.querySelectorAll('input[class="checkbox15"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal3"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_1_c_score" id="worker_1_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_1_na_score" id="worker_1_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-1-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 2 -->
                                                                    <div id="worker2" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-2">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_2 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers2 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers2 as $worker2) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox16" onclick="countSelected()" name="worker2_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox17" onclick="countSelected()" name="worker2_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox18" onclick="countSelected()" name="worker2_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker2->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-2" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker2->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC6">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC6').innerHTML = document.querySelectorAll('input[class="checkbox16"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC6">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC6').innerHTML = document.querySelectorAll('input[class="checkbox17"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA6">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA6').innerHTML = document.querySelectorAll('input[class="checkbox18"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal4"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_2_c_score" id="worker_2_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_2_na_score" id="worker_2_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-2-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 3 -->
                                                                    <div id="worker3" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-3">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_3 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers3 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers3 as $worker3) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox19" onclick="countSelected()" name="worker3_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox20" onclick="countSelected()" name="worker3_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox21" onclick="countSelected()" name="worker3_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker3->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-3" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker3->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC7">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC7').innerHTML = document.querySelectorAll('input[class="checkbox19"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC7">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC7').innerHTML = document.querySelectorAll('input[class="checkbox20"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA7">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA7').innerHTML = document.querySelectorAll('input[class="checkbox21"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal5"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_3_c_score" id="worker_3_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_3_na_score" id="worker_3_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-3-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 4 -->
                                                                    <div id="worker4" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-4">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_4 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers4 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers4 as $worker4) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox22" onclick="countSelected()" name="worker4_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker4->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox23" onclick="countSelected()" name="worker4_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker4->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox24" onclick="countSelected()" name="worker4_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker4->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-4" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker4->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC8">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC8').innerHTML = document.querySelectorAll('input[class="checkbox22"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC8">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC8').innerHTML = document.querySelectorAll('input[class="checkbox23"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA8">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA8').innerHTML = document.querySelectorAll('input[class="checkbox24"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal6"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_4_c_score" id="worker_4_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_4_na_score" id="worker_4_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-4-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 5 -->
                                                                    <div id="worker5" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-5">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_5 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers5 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers5 as $worker5) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox25" onclick="countSelected()" name="worker5_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker5->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox26" onclick="countSelected()" name="worker5_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker5->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox27" onclick="countSelected()" name="worker5_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker5->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-5" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker5->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC9">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC9').innerHTML = document.querySelectorAll('input[class="checkbox25"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC9">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC9').innerHTML = document.querySelectorAll('input[class="checkbox26"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA9">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA9').innerHTML = document.querySelectorAll('input[class="checkbox27"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal7"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_5_c_score" id="worker_5_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_5_na_score" id="worker_5_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-5-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 6 -->
                                                                    <div id="worker6" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-6">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_6 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers6 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers6 as $worker6) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox28" onclick="countSelected()" name="worker6_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker6->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox29" onclick="countSelected()" name="worker6_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker6->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox30" onclick="countSelected()" name="worker6_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker6->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-6" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker6->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC10">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC10').innerHTML = document.querySelectorAll('input[class="checkbox28"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC10">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC10').innerHTML = document.querySelectorAll('input[class="checkbox29"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA10">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA10').innerHTML = document.querySelectorAll('input[class="checkbox30"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal8"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_6_c_score" id="worker_6_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_6_na_score" id="worker_6_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-6-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 7 -->
                                                                    <div id="worker7" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-7">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_7 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers7 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers7 as $worker7) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox31" onclick="countSelected()" name="worker7_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker7->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox32" onclick="countSelected()" name="worker7_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker7->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox33" onclick="countSelected()" name="worker7_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker7->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-7" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker7->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC11">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC11').innerHTML = document.querySelectorAll('input[class="checkbox31"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC11">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC11').innerHTML = document.querySelectorAll('input[class="checkbox32"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA11">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA11').innerHTML = document.querySelectorAll('input[class="checkbox33"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal9"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_7_c_score" id="worker_7_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_7_na_score" id="worker_7_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-7-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 8 -->
                                                                    <div id="worker8" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-8">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_8 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers8 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers8 as $worker8) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox34" onclick="countSelected()" name="worker8_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker8->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox35" onclick="countSelected()" name="worker8_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker8->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox36" onclick="countSelected()" name="worker8_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker8->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-8" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker8->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC12">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC12').innerHTML = document.querySelectorAll('input[class="checkbox34"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC12">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC12').innerHTML = document.querySelectorAll('input[class="checkbox35"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA12">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA12').innerHTML = document.querySelectorAll('input[class="checkbox36"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal10"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_8_c_score" id="worker_8_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_8_na_score" id="worker_8_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-8-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- Tab for Worker 9 -->
                                                                    <div id="worker9" class="tabcontent2">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                <form class="user" method="POST" id="personnel-interview-worker-9">
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
                                                                                                        $checklist_id = $checklist->id;
                                                                                                ?>
                                                                                                        <tr>
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="assessee_id" id="assessee_id" value="<?= $result->assessee_id ?>">
                                                                                                            <input type="hidden" class="form-control form-control-user font-weight-bold" name="personnel_interview_checklist_id[]" id="personnel_interview_checklist_id[]" value="<?= $checklist_id ?>">
                                                                                                            <td><?= $cnt++ ?></td>
                                                                                                            <td class="text-left"><?php echo htmlentities($checklist->checklist) ?></td>
                                                                                                            <?php
                                                                                                            $sql = "SELECT * FROM personnel_interview_worker_9 WHERE assessment_id='$result->assessee_id' AND personnel_interview_checklist_id='$checklist_id'";
                                                                                                            $query = $dbh->prepare($sql);
                                                                                                            $query->execute();
                                                                                                            $workers9 = $query->fetchAll(PDO::FETCH_OBJ);
                                                                                                            if ($query->rowCount() > 0) {
                                                                                                                foreach ($workers9 as $worker9) {
                                                                                                            ?>
                                                                                                                    <td><input type="radio" class="checkbox37" onclick="countSelected()" name="worker9_<?= $checklist_id ?>[]" value="C" <?php if (in_array("C", explode(", ", $worker9->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox38" onclick="countSelected()" name="worker9_<?= $checklist_id ?>[]" value="NC" <?php if (in_array("NC", explode(", ", $worker9->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><input type="radio" class="checkbox39" onclick="countSelected()" name="worker9_<?= $checklist_id ?>[]" value="NA" <?php if (in_array("NA", explode(", ", $worker9->status))) echo 'checked = "checked"'; ?>></td>
                                                                                                                    <td><textarea form="personnel-interview-worker-9" rows="2" cols="20" id="remarks" name="remarks_<?= $checklist_id ?>"><?= $worker9->remarks ?></textarea></td>
                                                                                                        </tr>
                                                                                                <?php }
                                                                                                            } ?>
                                                                                        <?php }
                                                                                                } ?>
                                                                                <?php }
                                                                                        } ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <th colspan="2">SUB SCORE</th>
                                                                                            <th id="selectedC13">
                                                                                                <script>
                                                                                                    document.getElementById('selectedC13').innerHTML = document.querySelectorAll('input[class="checkbox37"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNC13">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNC13').innerHTML = document.querySelectorAll('input[class="checkbox38"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedNA13">
                                                                                                <script>
                                                                                                    document.getElementById('selectedNA13').innerHTML = document.querySelectorAll('input[class="checkbox39"]:checked').length
                                                                                                </script>
                                                                                            </th>
                                                                                            <th id="selectedTotal11"></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                        </div>
                                                                        <!-- End of Table Responsive -->
                                                                        <div class="form-group" id="row">
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_9_c_score" id="worker_9_c_score" onchange="countSelected()">
                                                                                <input type="hidden" class="form-control form-control-user font-weight-bold" name="worker_9_na_score" id="worker_9_na_score" onchange="countSelected()">
                                                                            </div>
                                                                            <div class="col-sm-4 mb-3 mb-sm-0">
                                                                                <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" name="save-personnel-interview-worker-9-from-history">Save</button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <!-- End of Workers Tab Content -->

                                                                <!-- </form> -->
                                                            </div>
                                                            <!-- End of p-5 -->
                                                        </div>
                                                        <!-- End of col-lg-12 -->
                                                    </div>
                                                    <!-- End of Row -->
                                                </div>
                                                <!-- End of Card Body -->
                                            </div>
                                            <!-- End of Card -->

                                        </div>
                                        <!-- End of col-xl-12 -->

                                    </div>
                                    <!-- End of Row Justify -->

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

            function countSelected() {
                //managerial
                var checkboxes = document.querySelectorAll('.checkbox1');
                var checkboxes2 = document.querySelectorAll('.checkbox2');
                var checkboxes3 = document.querySelectorAll('.checkbox3');

                var totalScore = 0;
                var countC = 0;
                var countNC = 0;
                var countNA = 0;

                checkboxes.forEach(item => {
                    if (item.checked == true) {
                        countC++;
                    }
                });

                checkboxes2.forEach(item => {
                    if (item.checked == true) {
                        countNC++;
                    }
                });

                checkboxes3.forEach(item => {
                    if (item.checked == true) {
                        countNA++;
                    }
                });

                document.getElementById('selectedC').innerHTML = countC;
                document.getElementById('selectedNC').innerHTML = countNC;
                document.getElementById('selectedNA').innerHTML = countNA;

                totalScore = countC + countNC + countNA;
                document.getElementById('managerial_c_score').value = countC;
                document.getElementById('managerial_na_score').value = countNA;

                //supervisory 1
                var checkboxes4 = document.querySelectorAll('.checkbox4');
                var checkboxes5 = document.querySelectorAll('.checkbox5');
                var checkboxes6 = document.querySelectorAll('.checkbox6');

                var totalScore2 = 0;
                var countC2 = 0;
                var countNC2 = 0;
                var countNA2 = 0;

                checkboxes4.forEach(item => {
                    if (item.checked == true) {
                        countC2++;
                    }
                });

                checkboxes5.forEach(item => {
                    if (item.checked == true) {
                        countNC2++;
                    }
                });

                checkboxes6.forEach(item => {
                    if (item.checked == true) {
                        countNA2++;
                    }
                });

                document.getElementById('selectedC2').innerHTML = countC2;
                document.getElementById('selectedNC2').innerHTML = countNC2;
                document.getElementById('selectedNA2').innerHTML = countNA2;

                //supervisory 2
                var checkboxes7 = document.querySelectorAll('.checkbox7');
                var checkboxes8 = document.querySelectorAll('.checkbox8');
                var checkboxes9 = document.querySelectorAll('.checkbox9');

                var countC3 = 0;
                var countNC3 = 0;
                var countNA3 = 0;

                checkboxes7.forEach(item => {
                    if (item.checked == true) {
                        countC3++;
                    }
                });

                checkboxes8.forEach(item => {
                    if (item.checked == true) {
                        countNC3++;
                    }
                });

                checkboxes9.forEach(item => {
                    if (item.checked == true) {
                        countNA3++;
                    }
                });

                document.getElementById('selectedC3').innerHTML = countC3;
                document.getElementById('selectedNC3').innerHTML = countNC3;
                document.getElementById('selectedNA3').innerHTML = countNA3;

                //supervisory 3
                var checkboxes10 = document.querySelectorAll('.checkbox10');
                var checkboxes11 = document.querySelectorAll('.checkbox11');
                var checkboxes12 = document.querySelectorAll('.checkbox12');

                var countC4 = 0;
                var countNC4 = 0;
                var countNA4 = 0;

                checkboxes10.forEach(item => {
                    if (item.checked == true) {
                        countC4++;
                    }
                });

                checkboxes11.forEach(item => {
                    if (item.checked == true) {
                        countNC4++;
                    }
                });

                checkboxes12.forEach(item => {
                    if (item.checked == true) {
                        countNA4++;
                    }
                });

                document.getElementById('selectedC4').innerHTML = countC4;
                document.getElementById('selectedNC4').innerHTML = countNC4;
                document.getElementById('selectedNA4').innerHTML = countNA4;

                totalScore2 = countC2 + countNC2 + countNA2 + countC3 + countNC3 + countNA3 + countC4 + countNC4 + countNA4;
                document.getElementById('supervisory_c_score').value = countC2 + countC3 + countC4;
                document.getElementById('supervisory_na_score').value = countNA2 + countNA3 + countNA4;

                //worker 1
                var checkboxes13 = document.querySelectorAll('.checkbox13');
                var checkboxes14 = document.querySelectorAll('.checkbox14');
                var checkboxes15 = document.querySelectorAll('.checkbox15');

                var totalScore3 = 0;
                var countC5 = 0;
                var countNC5 = 0;
                var countNA5 = 0;

                checkboxes13.forEach(item => {
                    if (item.checked == true) {
                        countC5++;
                    }
                });

                checkboxes14.forEach(item => {
                    if (item.checked == true) {
                        countNC5++;
                    }
                });

                checkboxes15.forEach(item => {
                    if (item.checked == true) {
                        countNA5++;
                    }
                });

                document.getElementById('selectedC5').innerHTML = countC5;
                document.getElementById('selectedNC5').innerHTML = countNC5;
                document.getElementById('selectedNA5').innerHTML = countNA5;

                totalScore3 = countC5 + countNC5 + countNA5;
                document.getElementById('worker_1_c_score').value = countC5;
                document.getElementById('worker_1_na_score').value = countNA5;

                //worker 2
                var checkboxes16 = document.querySelectorAll('.checkbox16');
                var checkboxes17 = document.querySelectorAll('.checkbox17');
                var checkboxes18 = document.querySelectorAll('.checkbox18');

                var totalScore4 = 0;
                var countC6 = 0;
                var countNC6 = 0;
                var countNA6 = 0;

                checkboxes16.forEach(item => {
                    if (item.checked == true) {
                        countC6++;
                    }
                });

                checkboxes17.forEach(item => {
                    if (item.checked == true) {
                        countNC6++;
                    }
                });

                checkboxes18.forEach(item => {
                    if (item.checked == true) {
                        countNA6++;
                    }
                });

                document.getElementById('selectedC6').innerHTML = countC6;
                document.getElementById('selectedNC6').innerHTML = countNC6;
                document.getElementById('selectedNA6').innerHTML = countNA6;

                totalScore4 = countC6 + countNC6 + countNA6;
                document.getElementById('worker_2_c_score').value = countC6;
                document.getElementById('worker_2_na_score').value = countNA6;

                //worker 3
                var checkboxes19 = document.querySelectorAll('.checkbox19');
                var checkboxes20 = document.querySelectorAll('.checkbox20');
                var checkboxes21 = document.querySelectorAll('.checkbox21');

                var totalScore5 = 0;
                var countC7 = 0;
                var countNC7 = 0;
                var countNA7 = 0;

                checkboxes19.forEach(item => {
                    if (item.checked == true) {
                        countC7++;
                    }
                });

                checkboxes20.forEach(item => {
                    if (item.checked == true) {
                        countNC7++;
                    }
                });

                checkboxes21.forEach(item => {
                    if (item.checked == true) {
                        countNA7++;
                    }
                });

                document.getElementById('selectedC7').innerHTML = countC7;
                document.getElementById('selectedNC7').innerHTML = countNC7;
                document.getElementById('selectedNA7').innerHTML = countNA7;

                totalScore5 = countC7 + countNC7 + countNA7;
                document.getElementById('worker_3_c_score').value = countC7;
                document.getElementById('worker_3_na_score').value = countNA7;

                //worker 4
                var checkboxes22 = document.querySelectorAll('.checkbox22');
                var checkboxes23 = document.querySelectorAll('.checkbox23');
                var checkboxes24 = document.querySelectorAll('.checkbox24');

                var totalScore6 = 0;
                var countC8 = 0;
                var countNC8 = 0;
                var countNA8 = 0;

                checkboxes22.forEach(item => {
                    if (item.checked == true) {
                        countC8++;
                    }
                });

                checkboxes23.forEach(item => {
                    if (item.checked == true) {
                        countNC8++;
                    }
                });

                checkboxes24.forEach(item => {
                    if (item.checked == true) {
                        countNA8++;
                    }
                });

                document.getElementById('selectedC8').innerHTML = countC8;
                document.getElementById('selectedNC8').innerHTML = countNC8;
                document.getElementById('selectedNA8').innerHTML = countNA8;

                totalScore6 = countC8 + countNC8 + countNA8;
                document.getElementById('worker_4_c_score').value = countC8;
                document.getElementById('worker_4_na_score').value = countNA8;

                //worker 5
                var checkboxes25 = document.querySelectorAll('.checkbox25');
                var checkboxes26 = document.querySelectorAll('.checkbox26');
                var checkboxes27 = document.querySelectorAll('.checkbox27');

                var totalScore7 = 0;
                var countC9 = 0;
                var countNC9 = 0;
                var countNA9 = 0;

                checkboxes25.forEach(item => {
                    if (item.checked == true) {
                        countC9++;
                    }
                });

                checkboxes26.forEach(item => {
                    if (item.checked == true) {
                        countNC9++;
                    }
                });

                checkboxes27.forEach(item => {
                    if (item.checked == true) {
                        countNA9++;
                    }
                });

                document.getElementById('selectedC9').innerHTML = countC9;
                document.getElementById('selectedNC9').innerHTML = countNC9;
                document.getElementById('selectedNA9').innerHTML = countNA9;

                totalScore7 = countC9 + countNC9 + countNA9;
                document.getElementById('worker_5_c_score').value = countC9;
                document.getElementById('worker_5_na_score').value = countNA9;

                //worker 6
                var checkboxes28 = document.querySelectorAll('.checkbox28');
                var checkboxes29 = document.querySelectorAll('.checkbox29');
                var checkboxes30 = document.querySelectorAll('.checkbox30');

                var totalScore8 = 0;
                var countC10 = 0;
                var countNC10 = 0;
                var countNA10 = 0;

                checkboxes28.forEach(item => {
                    if (item.checked == true) {
                        countC10++;
                    }
                });

                checkboxes29.forEach(item => {
                    if (item.checked == true) {
                        countNC10++;
                    }
                });

                checkboxes30.forEach(item => {
                    if (item.checked == true) {
                        countNA10++;
                    }
                });

                document.getElementById('selectedC10').innerHTML = countC10;
                document.getElementById('selectedNC10').innerHTML = countNC10;
                document.getElementById('selectedNA10').innerHTML = countNA10;

                totalScore8 = countC10 + countNC10 + countNA10;
                document.getElementById('worker_6_c_score').value = countC10;
                document.getElementById('worker_6_na_score').value = countNA10;

                //worker 7
                var checkboxes31 = document.querySelectorAll('.checkbox31');
                var checkboxes32 = document.querySelectorAll('.checkbox32');
                var checkboxes33 = document.querySelectorAll('.checkbox33');

                var totalScore9 = 0;
                var countC11 = 0;
                var countNC11 = 0;
                var countNA11 = 0;

                checkboxes31.forEach(item => {
                    if (item.checked == true) {
                        countC11++;
                    }
                });

                checkboxes32.forEach(item => {
                    if (item.checked == true) {
                        countNC11++;
                    }
                });

                checkboxes33.forEach(item => {
                    if (item.checked == true) {
                        countNA11++;
                    }
                });

                document.getElementById('selectedC11').innerHTML = countC11;
                document.getElementById('selectedNC11').innerHTML = countNC11;
                document.getElementById('selectedNA11').innerHTML = countNA11;

                totalScore9 = countC11 + countNC11 + countNA11;
                document.getElementById('worker_7_c_score').value = countC11;
                document.getElementById('worker_7_na_score').value = countNA11;

                //worker 8
                var checkboxes34 = document.querySelectorAll('.checkbox34');
                var checkboxes35 = document.querySelectorAll('.checkbox35');
                var checkboxes36 = document.querySelectorAll('.checkbox36');

                var totalScore10 = 0;
                var countC12 = 0;
                var countNC12 = 0;
                var countNA12 = 0;

                checkboxes34.forEach(item => {
                    if (item.checked == true) {
                        countC12++;
                    }
                });

                checkboxes35.forEach(item => {
                    if (item.checked == true) {
                        countNC12++;
                    }
                });

                checkboxes36.forEach(item => {
                    if (item.checked == true) {
                        countNA12++;
                    }
                });

                document.getElementById('selectedC12').innerHTML = countC12;
                document.getElementById('selectedNC12').innerHTML = countNC12;
                document.getElementById('selectedNA12').innerHTML = countNA12;

                totalScore10 = countC12 + countNC12 + countNA12;
                document.getElementById('worker_8_c_score').value = countC12;
                document.getElementById('worker_8_na_score').value = countNA12;

                //worker 9
                var checkboxes37 = document.querySelectorAll('.checkbox37');
                var checkboxes38 = document.querySelectorAll('.checkbox38');
                var checkboxes39 = document.querySelectorAll('.checkbox39');

                var totalScore11 = 0;
                var countC13 = 0;
                var countNC13 = 0;
                var countNA13 = 0;

                checkboxes37.forEach(item => {
                    if (item.checked == true) {
                        countC13++;
                    }
                });

                checkboxes38.forEach(item => {
                    if (item.checked == true) {
                        countNC13++;
                    }
                });

                checkboxes39.forEach(item => {
                    if (item.checked == true) {
                        countNA13++;
                    }
                });

                document.getElementById('selectedC13').innerHTML = countC13;
                document.getElementById('selectedNC13').innerHTML = countNC13;
                document.getElementById('selectedNA13').innerHTML = countNA13;

                totalScore11 = countC13 + countNC13 + countNA13;
                document.getElementById('worker_9_c_score').value = countC13;
                document.getElementById('worker_9_na_score').value = countNA13;
            }

            var managerialCScore = document.getElementById('managerial_c_score');
            var managerialNAScore = document.getElementById('managerial_na_score');
            var managerial_c = document.querySelectorAll('input[class="checkbox1"]:checked').length;
            var managerial_na = document.querySelectorAll('input[class="checkbox3"]:checked').length;
            managerialCScore.setAttribute('value', managerial_c);
            managerialNAScore.setAttribute('value', managerial_na);

            var supervisoryCScore = document.getElementById('supervisory_c_score');
            var supervisoryNAScore = document.getElementById('supervisory_na_score');
            var supervisory_c = document.querySelectorAll('input[class="checkbox4"]:checked').length + document.querySelectorAll('input[class="checkbox7"]:checked').length + document.querySelectorAll('input[class="checkbox10"]:checked').length;
            var supervisory_na = document.querySelectorAll('input[class="checkbox6"]:checked').length + document.querySelectorAll('input[class="checkbox9"]:checked').length + document.querySelectorAll('input[class="checkbox12"]:checked').length;
            supervisoryCScore.setAttribute('value', supervisory_c);
            supervisoryNAScore.setAttribute('value', supervisory_na);

            var worker1CScore = document.getElementById('worker_1_c_score');
            var worker1NAScore = document.getElementById('worker_1_na_score');
            var worker1_c = document.querySelectorAll('input[class="checkbox13"]:checked').length;
            var worker1_na = document.querySelectorAll('input[class="checkbox15"]:checked').length;
            worker1CScore.setAttribute('value', worker1_c);
            worker1NAScore.setAttribute('value', worker1_na);

            var worker2CScore = document.getElementById('worker_2_c_score');
            var worker2NAScore = document.getElementById('worker_2_na_score');
            var worker2_c = document.querySelectorAll('input[class="checkbox16"]:checked').length;
            var worker2_na = document.querySelectorAll('input[class="checkbox18"]:checked').length;
            worker2CScore.setAttribute('value', worker2_c);
            worker2NAScore.setAttribute('value', worker2_na);

            var worker3CScore = document.getElementById('worker_3_c_score');
            var worker3NAScore = document.getElementById('worker_3_na_score');
            var worker3_c = document.querySelectorAll('input[class="checkbox19"]:checked').length;
            var worker3_na = document.querySelectorAll('input[class="checkbox21"]:checked').length;
            worker3CScore.setAttribute('value', worker3_c);
            worker3NAScore.setAttribute('value', worker3_na);

            var worker4CScore = document.getElementById('worker_4_c_score');
            var worker4NAScore = document.getElementById('worker_4_na_score');
            var worker4_c = document.querySelectorAll('input[class="checkbox22"]:checked').length;
            var worker4_na = document.querySelectorAll('input[class="checkbox24"]:checked').length;
            worker4CScore.setAttribute('value', worker4_c);
            worker4NAScore.setAttribute('value', worker4_na);

            var worker5CScore = document.getElementById('worker_5_c_score');
            var worker5NAScore = document.getElementById('worker_5_na_score');
            var worker5_c = document.querySelectorAll('input[class="checkbox25"]:checked').length;
            var worker5_na = document.querySelectorAll('input[class="checkbox27"]:checked').length;
            worker5CScore.setAttribute('value', worker5_c);
            worker5NAScore.setAttribute('value', worker5_na);

            var worker6CScore = document.getElementById('worker_6_c_score');
            var worker6NAScore = document.getElementById('worker_6_na_score');
            var worker6_c = document.querySelectorAll('input[class="checkbox28"]:checked').length;
            var worker6_na = document.querySelectorAll('input[class="checkbox30"]:checked').length;
            worker6CScore.setAttribute('value', worker6_c);
            worker6NAScore.setAttribute('value', worker6_na);

            var worker7CScore = document.getElementById('worker_7_c_score');
            var worker7NAScore = document.getElementById('worker_7_na_score');
            var worker7_c = document.querySelectorAll('input[class="checkbox31"]:checked').length;
            var worker7_na = document.querySelectorAll('input[class="checkbox33"]:checked').length;
            worker7CScore.setAttribute('value', worker7_c);
            worker7NAScore.setAttribute('value', worker7_na);

            var worker8CScore = document.getElementById('worker_8_c_score');
            var worker8NAScore = document.getElementById('worker_8_na_score');
            var worker8_c = document.querySelectorAll('input[class="checkbox34"]:checked').length;
            var worker8_na = document.querySelectorAll('input[class="checkbox36"]:checked').length;
            worker8CScore.setAttribute('value', worker8_c);
            worker8NAScore.setAttribute('value', worker8_na);

            var worker9CScore = document.getElementById('worker_9_c_score');
            var worker9NAScore = document.getElementById('worker_9_na_score');
            var worker9_c = document.querySelectorAll('input[class="checkbox37"]:checked').length;
            var worker9_na = document.querySelectorAll('input[class="checkbox39"]:checked').length;
            worker9CScore.setAttribute('value', worker9_c);
            worker9NAScore.setAttribute('value', worker9_na);
        </script>

    <?php } else {
        header("location: login.php");
    } ?>
</body>

</html>