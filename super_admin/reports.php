<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Vehicle Booking System Transport Saccos, Matatu Industry">
  <meta name="author" content="MartDevelopers">

  <title>Vehicle Booking System - Admin Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">
  <style>
    .report-tbl-div {
      max-height: 300px;
      overflow: auto;
    }
  </style>
</head>

<body id="page-top">
 <!--Start Navigation Bar-->
  <?php include("vendor/inc/nav.php");?>
  <!--Navigation Bar-->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php include("vendor/inc/sidebar.php");?>
    <!--End Sidebar-->
    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Generate Report</li>
        </ol>

        <!-- Icon Cards-->
        <form class="form" method="POST">
          <div class="card">
          <!-- <img src="../vendor/img/services_banner.jpg" class="card-img-top" alt="..."> -->
          <div class="card-header">
            Generate Report
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>From:</label>
                    <input type="date" name="date_from" required class="form-control" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>To:</label>
                    <input type="date" name="date_to" required class="form-control" />
                  </div>
                </div>
              </div>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary" type="submit" name="generate_report"><i class="fa fa-reports"></i> Generate</button>
          </div>
          <!--Bookings-->
          </div>
        </form>
        <?php if(isset($_POST['generate_report'])): ?>
          <?php
            extract($_POST);
            
            $rep = "SELECT * FROM `tms_transactions` INNER JOIN `tms_admin` 
            WHERE `trans_type` = 'registration'
            AND `tms_transactions`.`lessor_id` = `tms_admin`.`a_id`
            AND date(`tms_transactions`.`trans_created_at`) >= date('$date_from') 
            AND date(`tms_transactions`.`trans_created_at`) <= date('$date_to')";
            
            // $rep = "SELECT * FROM `tms_transactions` INNER JOIN `tms_admin` 
            // WHERE `tms_transactions`.`lessor_id` = `tms_admin`.`a_id`
            // AND date(`tms_transactions`.`created_at`) >= date('$date_from') 
            // AND date(`tms_transactions`.`created_at`) <= date('$date_to')";

            // print_r($rep);
            $stmt = $mysqli->prepare($rep);
            $stmt->execute();
            $res=$stmt->get_result();
            $total = 0;
          ?>
        <div class="card mt-4">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                Generated Report
              </div>
              <div class="col-md-6 text-right">
                as of <strong><?php echo date_format(date_create($date_from), "M d, Y")." to ".date_format(date_create($date_to), "M d, Y") ?></strong>
              </div>
            </div>
            
          </div>
          <div class="card-body">
            <?php if($res->num_rows > 0): ?>
              <?php 
                $select_client = "SELECT * FROM `tms_user` WHERE `u_id` == $row->user_id"; 
                $select_client = $mysqli->prepare($select_client); 
                $selected_client->execute();
                $select_client->get_result();
                $selected_client->fetch_object();
              ?>
            <div class="report-tbl-div">
              <table class="table">
                <thead>
                  <tr>
                    <th class="bg-primary text-white">Timestamp</th>
                    <th class="bg-primary text-white">Lessor</th>
                    <th class="bg-primary text-white">Email</th>
                    <th class="bg-primary text-white">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $res->fetch_object()): ?>
                  <tr>
                    <td><?php echo date_format(date_create($row->trans_created_at), 'M d, Y h:i: A') ?></td>
                    <td><?php echo $row->a_name ?></td>
                    <td><?php echo $row->a_email ?></td>
                    <td>Php <?php echo number_format($row->trans_amount, 2) ?></td>
                  </tr>
                  <?php $total += $row->trans_amount ?>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            <?php else: ?>
              <p class="alert alert-warning">No data found.</p>
            <?php endif; ?>
          </div>
          <div class="card-footer">
            <h2 class="text-right"><span class="text-info">Total:</span> Php <?php echo number_format($total, 2) ?></h2>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <?php include("vendor/inc/footer.php");?>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <?php include('logout-modal.php'); ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="vendor/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="vendor/js/demo/datatables-demo.js"></script>
  <script src="vendor/js/demo/chart-area-demo.js"></script>

</body>

</html>
