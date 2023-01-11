<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['u_id'];
?>
<!DOCTYPE html>
<html lang="en">
<?php include("vendor/inc/head.php");?>

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
            <a href="user-dashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item">Booking</li>
          <li class="breadcrumb-item ">View My Booking</li>
        </ol>

        <!-- My Bookings-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Bookings</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Timestamp</th>
                    <th>Name</th>
                    <th>Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Reg No</th>
                    <th>Booking date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                
                <tbody>
                <?php
                    $aid=$_SESSION['u_id'];
                    $ret="SELECT * FROM tms_transactions 
                    INNER JOIN tms_user
                    INNER JOIN tms_vehicle
                    WHERE tms_transactions.trans_type = 'booking'
                    AND tms_user.u_id = tms_transactions.user_id
                    AND tms_transactions.user_id = $aid
                    AND tms_transactions.vehicle_id = tms_vehicle.v_id";
                    $stmt= $mysqli->prepare($ret) ;
                    $stmt->execute() ;//ok
                    $res=$stmt->get_result();
                    //$cnt=1;
                        while($row=$res->fetch_object()):

                        switch ($row->trans_payment_status) {
	                        case 'pending':
                                $badge = "badge-info";
		                        break;
	                        case 'cancelled':
	                        case 'disapproved':
                                $badge = "badge-danger";
		                        break;
	                        case 'approved':
                                $badge = "badge-success";
		                        break;
	                        default:
                                $badge = "badge-muted";
		                        break;
                        }

                        
                ?>
                  <tr>
                    <td><?php echo date_format(date_create($row->trans_created_at), "M d, Y h:m a");?></td>
                    <td><?php echo $row->u_fname;?> <?php echo $row->u_lname;?></td>
                    <td><?php echo $row->v_name;?></td>
                    <td><?php echo $row->v_category;?></td>
                    <td><?php echo $row->v_reg_no;?></td>
                    <td><?php echo date_format(date_create($row->booking_pickup_date), "M d, Y h:m a");?></td>
                    <td><span class="badge <?php echo $badge; ?>"><?php echo $row->trans_payment_status ?></span></td>
                  </tr>

                <?php  endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Africa/Nairobi");
              echo "Generated:" . date("h:i:sa");
            ?> 
        </div>
        </div>
        
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
  <?php include('user-logout-modal.php'); ?>

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
