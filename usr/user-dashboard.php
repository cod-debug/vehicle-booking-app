<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['u_id'];
?>
<!DOCTYPE html>
<html lang="en">

<!--Head-->
<?php include ('vendor/inc/head.php');?>
<!--End Head-->

<body id="page-top">
<!--Navbar-->
  <?php include ('vendor/inc/nav.php');?>
<!--End Navbar-->  

  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>
    <!--End Sidebar-->

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="user-dashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>

        <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-user"></i>
                </div>
                <div class="mr-5">My Profile</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="user-view-profile.php">
                <span class="float-left">View</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-clipboard"></i>
                </div>
                <div class="mr-5">My Booking</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="user-view-booking.php">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa fa-times"></i>
                </div>
                <div class="mr-5">Cancel Booking</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="user-manage-booking.php">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-clipboard"></i> <i class="fas fa-fw fa-bus"></i>
                </div>
                <div class="mr-5">Book Vehicle</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="usr-book-vehicle.php">
                <span class="float-left">View Details</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        </div>

       <!--Bookings-->
       
       
       <?php 
        $ret="SELECT * FROM tms_admin  where   user_type = 1 "; //get all bookings
        $lessors= $mysqli->prepare($ret) ;
        $lessors->execute() ;//ok
        $lessors_list=$lessors->get_result();
          while($les = $lessors_list->fetch_object()):
            

            $ret="SELECT * FROM tms_vehicle  where   v_status ='Available' AND `lessor_id` = $les->a_id "; //get all bookings
            $stmt= $mysqli->prepare($ret) ;
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            $cnt=1;
        ?>
        <div class="card mb-3">
          <div class="card-header" type="button" data-toggle="collapse" data-target="#vehiclesUnder<?php echo $les->a_id ?>" aria-expanded="false" aria-controls="collapseTwo">
          
              <div class=" float-right">
                <?php if($les->user_business_permit): ?>
                  <a href="../uploads/business_permits/<?php echo $les->user_business_permit ?>" class="btn btn-primary btn-sm" target="_blank">View Business Permit</a>
                  <?php else: ?>
                  <small>No Business Permit uploaded yet.</small>
                <?php endif; ?>
              </div>

            <?php echo $les->a_name ?>
            <span class="badge badge-primary"><?php echo $res->num_rows ?></span>
            <br>
            <small class="text-muted">
              <?php echo $les->address ?>
            </small>
            
          </div>
          <div class="card-body collapse" id="vehiclesUnder<?php echo $les->a_id ?>">
       <!--Bookings-->
       <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-bus"></i>
            Available Vehicles</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover data-table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Vehicle Name</th>
                    <th>Reg No.</th>
                    <th>Seats</th>
                    <th>Action</th>
                  </tr>
                </thead>
                
                <tbody>
                <?php
                  while($row=$res->fetch_object())
                {
                ?>
                  <tr>
                    <td><?php echo $cnt;?></td>
                    <td><?php echo $row->v_name;?></td>
                    <td><?php echo $row->v_reg_no;?></td>
                    <td><?php echo $row->v_pass_no;?> Passengers</td>
                    <td>
                      <a href="user-confirm-booking.php?v_id=<?php echo $row->v_id;?>" class = "btn btn-outline-success"><i class ="fa fa-clipboard"></i> Book Vehicle</a>
                    </td>
                  </tr>
                  <?php  $cnt = $cnt +1; }?>
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Africa/Nairobi");
              echo "Generated At : " . date("h:i:sa");
            ?> 
        </div>
        </div>
      </div>

      </div>            
      <?php endwhile; ?>
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
