<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  include('admin-check-payment.php');
  //Add Booking
  if(isset($_POST['approve_booking']))
    {
        $tid = $_POST['trans_id'];
        $update = "UPDATE tms_transactions SET `trans_payment_status` = 'approved' WHERE `trans_id` = '$tid'";
        $stmt = $mysqli->prepare($update);
        $stmt->execute();

        if($stmt){
            $succ = "Successfully approved!";
        } else {
            $err = "Something went wrong during approval. Contact system admnistrator.";
        }

    } else {
        $tid=$_GET['t_id'];
    }

  if(isset($_POST['return_settle'])){
    extract($_POST);
    $returned = "UPDATE tms_transactions SET `trans_payment_status` = 'returned' WHERE `trans_id` = '$tid'";
    $stmt = $mysqli->prepare($returned);
    $stmt->execute();

    if($stmt){
      $insert_trans = "INSERT INTO tms_transactions (`trans_type`, `trans_payment_status`, `vehicle_id`, `user_id`, `trans_itenerary`, `trans_amount`, `parent_trans_id`) VALUES ('booking', 'returned', '$vehicle_id', '$user_id', 'ADDITIONAL FEE', '$total', '$trans_id')";
      $stmt1 = $mysqli->prepare($insert_trans);
      $stmt1->execute();
      
      if($stmt1){
        $succ = "Successfully approved!";
      } else {
        $err = "Something went wrong during settlement. Contact system admnistrator. INSER_TRANS";
      }
    } else {
        $err = "Something went wrong during settlement. Contact system admnistrator.";
    }

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
                            window.location.href = "admin-manage-booking.php";
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

        <a href="admin-manage-booking.php" class="btn btn-outline-danger mb-3"><i class="fa fa-arrow-left"></i> Back to list</a>
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
            AND tms_transactions.vehicle_id = tms_vehicle.v_id
            AND tms_transactions.parent_trans_id = 0"; //get all bookings
            $stmt= $mysqli->prepare($ret) ;
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;
            while($row=$res->fetch_object()):
            
            $pick_up_date = new DateTime(date_format(date_create($row->booking_due_date), "Y-m-d H:i:s"));
            $today = new DateTime(date_create("Y-m-d H:i:s"));
            
            $interval = $pick_up_date->diff($today);
            (int) $days_late = $interval->format("%d");
            (int) $hours_late = $interval->format("%H") / 12;
            (int) $rate_per_day = $row->v_per_24hrs;
            (int) $rate_per_12hrs = $row->v_per_12hrs;
            
            if($hours_late >= 1){
              $days_late++;
            } 

            if($hours_late > 0){
              $hours_late = 1;
            } else {
              $hours_late = 0;
            }
            
            $total_fee = ($days_late * $rate_per_day) + ($hours_late * $rate_per_12hrs);
            $user_id = $row->user_id;
            $vehicle_id = $row->vehicle_id;

            $dont_ret = "SELECT * FROM `tms_transactions` WHERE `parent_trans_id` = '$tid'";
            $get_fee= $mysqli->prepare($dont_ret) ;
            $get_fee->execute() ;//ok
            $fee_res=$get_fee->get_result();
            
        ?> 
        <?php if ($today >= $pick_up_date && $row->trans_payment_status == 'approved'):?>
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-info text-white" style="cursor: pointer;" data-toggle="collapse" data-target="#returnBody" aria-expanded="false" aria-controls="collapseExample">
                  <strong> Return Car </strong>
                  <br>
                  <small>(Click to view summary)</small>
                  <?php if($total_fee > 0): ?>
                    <small class="float-right bg-danger p-1 rounded">with fee ₱ <?php echo number_format($total_fee, 2); ?></small>
                  <?php endif; ?>
                </div>
                <div class="card-body collapse" id="returnBody">
                  <?php if($days_late > 0 || $hours_late > 0): ?>
                    <div>
                      <p class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Additional fee in this transaction</p>
                      <div class="mb-2">
                        <span><b>Today:</b> <?php echo(date_format($today, "M d, Y h:i A")) ;?></span>
                        <span class="float-right text-danger"><b>Due Date:</b> <?php echo(date_format($pick_up_date, "M d, Y h:i A")) ;?></span>
                      </div>
                      <table class="table">
                        </tbody>
                          <tr>
                            <td>Rate per day</td>
                            <td>₱ <?php echo number_format($rate_per_day, 2) ?></td>
                          </tr>
                          <tr>
                            <td>Days Late</td>
                            <td>
                              <?php if($days_late > 0): ?>
                                <span class="badge badge-danger"><?php echo $days_late ?> Days</span>
                              <?php else: ?>
                                <span class="badge badge-success">N / A</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <tr>
                            <td>Rate per 12 Hrs</td>
                            <td>₱ <?php echo number_format($rate_per_12hrs, 2) ?></td>
                          </tr>
                          <tr>
                            <td>12 Hrs</td>
                            <td>
                              <?php if($hours_late > 0): ?>
                                <span class="badge badge-danger">+12 Hrs</span>
                              <?php else: ?>
                                <span class="badge badge-success">N/A</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <tr class="text-danger font-weight-bold">
                            <th>Total Fee:</th>
                            <td>₱ <?php echo number_format($total_fee, 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  <?php endif; ?> 
                </div>
                <div class="card-footer">
                  <?php if($fee_res->num_rows <= 0):?>
                  <form method="POST">
                    <input type="number" name="total" value="<?php echo $total_fee; ?>" hidden />
                    <input type="number" name="trans_id" value="<?php echo $tid ?>" hidden />
                    <input type="number" name="user_id" value="<?php echo $user_id ?>" hidden />
                    <input type="number" name="vehicle_id" value="<?php echo $vehicle_id ?>" hidden />
                    <button type="submit"class="btn btn-primary" name="return_settle">SET AS RETURNED AND SETTLED</button>
                  </form>
                  <?php else: ?>
                    <p class="alert alert-info">This transaction was returned and settled.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <div class="row"> 
            <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                  Transaction
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
                                <label for="due_date">Valid ID: </label>
                                <div style="max-height: 400px; overflow: auto; width: 100%;">
                                    <img src="../uploads/valid_id/<?php echo $row->valid_id ?>" class="w-100" />
                                </div>
                                <hr>
                            </div>                
                            <?php if($row->trans_payment_status == 'pending'): ?>
                            <div class="col-md-12">
                                <button type="submit" name="approve_booking" class="btn btn-success"><i class="fa fa-check-circle"></i> Approve Booking</button>
                                <a href="admin-disapprove-booking.php?t_id=<?php echo $tid ?>" type="submit" name="disapprove_booking" class="btn btn-outline-danger"><i class="fa fa-times-circle"></i> Disapprove Booking</a>
                            </div>
                            <?php else: ?>
                            <p class="col-md-12">This transaction was 
                              <?php if($row->trans_payment_status != 'disapprove'): ?>
                                <b class="badge badge-danger"><?php echo $row->trans_payment_status ?></b>
                              <?php else: ?>
                                <b class="badge badge-success"><?php echo $row->trans_payment_status ?></b>
                              <?php endif; ?>
                              <br />
                            </p>
                            <?php endif; ?>
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
