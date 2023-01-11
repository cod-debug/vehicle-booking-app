<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  $tid=$_GET['t_id'];
  include('admin-check-payment.php');
  //DISAPPROVE Booking
  if(isset($_POST['disapprove_booking']))
  {
      $tid = $_POST['trans_id'];
      $remarks = $_POST['trans_disapprove_reason'];
      $update = "UPDATE tms_transactions SET 
      `trans_payment_status` = 'disapproved',
      `trans_disapprove_remarks` = '$remarks'
      WHERE `trans_id` = '$tid'";
      $stmt = $mysqli->prepare($update);
      $stmt->execute();

      if($stmt){
          $succ = "Successfully disapproved";
      } else {
          $err = "Something went wrong during approval. Contact system admnistrator";
      }

  } else {
      $tid=$_GET['t_id'];
  }

?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

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
      <?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $succ;?>!","success").then(() => {
                          window.location.href="admin-manage-booking.php";
                        });
                    },
                        100);
        </script>

        <?php } ?>
        <?php if(isset($err)) {?>
        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Failed!","<?php echo $err;?>!","Failed");
                    },
                        100);
        </script>

        <?php } ?>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Bookings</a>
          </li>
          <li class="breadcrumb-item active">Approve</li>
        </ol>
        <hr>
        
        <?php

            $ret="SELECT * FROM tms_transactions 
            INNER JOIN tms_user
            INNER JOIN tms_vehicle
            WHERE tms_transactions.trans_id = $tid
            AND tms_transactions.trans_type = 'booking'
            AND tms_user.u_id = tms_transactions.user_id
            AND tms_transactions.vehicle_id = tms_vehicle.v_id"; //get all bookings
            $stmt= $mysqli->prepare($ret) ;
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;
            while($row=$res->fetch_object()):
        ?>
        <div class="row"> 
            <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                  Approve Booking
                </div>
                <div class="card-body">
                  <!--Add User Form-->
                  <form method ="POST" class="row"> 
                    <div class="form-group col-md-12">
                        <h5>Lessee Information</h5>
                        <hr>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">First Name</label>
                        <p class="text-muted"><?php echo $row->u_fname;?></p>
                        <hr>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Last Name</label>
                        <p class="text-muted"><?php echo $row->u_lname;?></p>
                        <hr>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Contact</label>
                        <p class="text-muted"><?php echo $row->u_phone;?></p>
                        <hr>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Address</label>
                        <p class="text-muted"><?php echo $row->u_addr;?></p>
                        <hr>
                    </div>
            
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Email address</label>
                        <p class="text-muted"><?php echo $row->u_email;?></p>
                        <hr>
                    </div>
                    <div class="form-group col-md-12 row">
                        <div class="form-group col-md-12">
                            <hr>
                            <h5>Vehicle Information</h5>
                            <hr>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Vehicle</label>
                            <p class="text-muted"><?php echo $row->v_name;?></p>
                            <hr>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Vehicle Category</label>
                            <p class="text-muted"><?php echo $row->v_category;?></p>
                            <hr>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Vehicle Registration NUmber</label>
                            <p class="text-muted"><?php echo $row->v_reg_no;?></p>
                            <hr>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Seats</label>
                            <p class="text-muted"><?php echo $row->v_pass_no;?> Seats</p>
                            <hr>
                        </div>
                    </div>
                  </form>
                  <!-- End Form-->
                </div>
              </div>
            </div>
            <div class="col-md-5">
                <diV class="card">
                    <div class="card-header">
                       <div>Transaction Details</div>
                    </div>
                    <div class="card-body">
                        <form class="form row" method="POST">
                            <div class="form-group col-md-6">
                                <label for="pickup_date">Pickup Date: </label>
                                <p class="text-muted"><?php echo date_format(date_create($row->booking_pickup_date), "M d, Y h:m a") ?></p>
                                <input type="text" name="trans_id" readonly value="<?php echo $row->trans_id ?>" class="form-control" hidden />
                                <hr>
                            </div>      
                            <div class="form-group col-md-6">
                                <label for="due_date">Due Date: </label>
                                <p class="text-muted"><?php echo date_format(date_create($row->booking_due_date), "M d, Y h:m a") ?></p>
                                <hr>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="due_date">Full Itenerary: </label>
                                <p class="text-muted"><?php echo $row->trans_itenerary ?></p>
                                <hr>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="due_date">Proof of Payment: </label>
                                <div style="max-height: 400px; overflow: auto; width: 100%;">
                                    <img src="../uploads/payments/<?php echo $row->trans_proof_of_payment ?>" class="w-100" />
                                </div>
                                <hr>
                            </div>  
                            <div class="form-group col-md-12">
                                <label>Reason / Remarks</label>
                                <textarea class="form-control" name="trans_disapprove_reason" id="reason" autofocus></textarea>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="disapprove_booking" class="btn btn-danger"><i class="fa fa-check-circle"></i> Confirm</button>
                                <a href="admin-approve-booking.php?t_id=<?php echo $tid ?>" type="submit" name="disapprove_booking" class="btn btn-outline-primary"><i class="fa fa-times-circle"></i> Cancel </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
       
        <?php endwhile;?>
      <hr>
     

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
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="vendor/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="vendor/js/demo/datatables-demo.js"></script>
  <script src="vendor/js/demo/chart-area-demo.js"></script>
 <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

</body>

</html>
