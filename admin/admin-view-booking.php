<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  include('admin-check-payment.php');
?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<body id="page-top">

 <?php include("vendor/inc/nav.php");?>


  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Bookings</a>
          </li>
          <li class="breadcrumb-item active">View </li>
        </ol>

        <!--Bookings-->        
        <div class="card mb-3 mt-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Bookings <?php if (isset($start) && isset($end)){ echo "<br><b>".date_format(date_create($start), "M d, Y")."</b> to <b>".date_format(date_create($end), "M d, Y")."</b>";} ?></div>
          <div class="card-body">
            <div class="table-responsive">
              <?php 
              
                  $lessor_id = $_SESSION['a_id'];
                  if(isset($start) && isset($end)){
                      $ret="SELECT * FROM tms_transactions 
                      INNER JOIN tms_user
                      INNER JOIN tms_vehicle
                      INNER JOIN tms_admin
                      WHERE tms_transactions.trans_type = 'booking'
                      AND tms_user.u_id = tms_transactions.user_id
                      AND tms_transactions.vehicle_id = tms_vehicle.v_id
                      AND tms_vehicle.lessor_id = tms_admin.a_id
                      AND tms_admin.a_id = '$lessor_id'
                      AND tms_transactions.created_at BETWEEN '$start 'AND '$end'";
                  } else {
                      $ret="SELECT * FROM tms_transactions 
                      INNER JOIN tms_user
                      INNER JOIN tms_vehicle
                      INNER JOIN tms_admin
                      WHERE tms_transactions.trans_type = 'booking'
                      AND tms_user.u_id = tms_transactions.user_id
                      AND tms_vehicle.lessor_id = tms_admin.a_id
                      AND tms_admin.a_id = '$lessor_id'
                      AND tms_transactions.vehicle_id = tms_vehicle.v_id";
                  } //get all bookings
                  $stmt= $mysqli->prepare($ret) ;
                  $stmt->execute() ;//ok
                  $res=$stmt->get_result();
                  $cnt=1;

                  if($res->num_rows):

              ?>
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Timestamp</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Reg No</th>
                    <th>Booking date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                
                <tbody>
                <?php
                  while($row=$res->fetch_object()):

                  $badge = "badge-success";
                  switch ($row->trans_payment_status) {
                    case 'pending':
                          $badge = "badge-info";
                      break;
                    case 'cancelled':
                      $badge = "badge-warning";
                      break;
                    case 'disapproved':
                          $badge = "badge-danger";
                      break;
                    case 'approved':
                          $badge = "badge-success";
                      break;
                    case 'returned':
                          $badge = "badge-primary";
                      break;
                    default:
                          $badge = "badge-muted";
                      break;
                  }
                  
                ?>
                  <tr>
                    <td><?php echo date_format(date_create($row->trans_created_at), "M d, Y h:m a");?></td>
                    <td><?php echo $row->u_fname;?> <?php echo $row->u_lname;?></td>
                    <td><?php echo $row->u_phone;?></td>
                    <td><?php echo $row->v_name;?></td>
                    <td><?php echo $row->v_category;?></td>
                    <td><?php echo $row->v_reg_no;?></td>
                    <td><?php echo date_format(date_create($row->booking_pickup_date), "M d, Y h:m a");?></td>
                    <td><span class = "badge <?php echo $badge ?>"><?php echo $row->trans_payment_status ?></span></td>
                  </tr>
                  <?php  $cnt = $cnt +1; endwhile;?>
                  
                </tbody>
              </table>
              <?php else: ?>
              <p class="alert alert-info">No data found.</p>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Asia/Manila");
              echo "Time loaded " . date("h:i:s a");
            ?> 
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
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger" href="admin-logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
