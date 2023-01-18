<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  //Add USer
  if(isset($_POST['update_user']))
    {
            $u_id= $_GET['u_id'];
            $u_fname=$_POST['u_fname'];
            $u_lname = $_POST['u_lname'];
            $u_phone=$_POST['u_phone'];
            $u_addr=$_POST['u_addr'];
            $u_email=$_POST['u_email'];
            $u_pwd=$_POST['u_pwd'];
            $u_category=$_POST['u_category'];
            $query="update tms_user set u_fname=?, u_lname=?, u_phone=?, u_addr=?, u_category=?, u_email=?, u_pwd=? where u_id=?";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param('sssssssi', $u_fname,  $u_lname, $u_phone, $u_addr, $u_category, $u_email, $u_pwd, $u_id);
            $stmt->execute();
                if($stmt)
                {
                    $succ = "User Updated";
                }
                else 
                {
                    $err = "Please Try Again Later";
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
      <?php 
        if(isset($_POST['approve_user'])){
            $swal_title = "Approval";
            $aid = $_POST['aid'];

            $update_trans = "UPDATE `tms_transactions` 
                SET `trans_payment_status` = ? 
                WHERE `lessor_id` = ?";


            $u_trans = $mysqli->prepare($update_trans);
            $payment_status = "approved";
            $active = "active";

            $is_new = false;
            $ur = $u_trans->bind_param('si', $payment_status, $aid);


            $update_lessor = "UPDATE `tms_admin` 
                SET `payment_status` = ?,
                `status` = ?,
                `is_new` = ?
                WHERE `a_id` = ?";
            $u_trans->execute();

            $u_lessor = $mysqli->prepare($update_lessor);
            $ul = $u_lessor->bind_param('ssi',  $payment_status, $active, $is_new, $aid);
            $u_lessor->execute();

            if($u_lessor && $u_trans){
                $succ="Successfully approved";
            } else {
                $err = "Something went wrong.";
            }
        }
      ?>
      <?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Approve Lessor","<?php echo $succ;?>!","success");
                    },
                        100);
        </script>

        <?php } ?>
        <?php if(isset($err)) {?>
        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Approve Lessor","<?php echo $err;?>!","Failed");
                    },
                        100);
        </script>

        <?php } ?>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Users</a>
          </li>
          <li class="breadcrumb-item active">Add User</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          <a href="admin-manage-user.php" title="Back to list"><i class="fa fa-arrow-left"></i></a>
          Registration Approval
        </div>
        <div class="card-body">
          <!--Add User Form-->
          <?php
            $aid=$_GET['a_id'];
            $ret="select * from tms_admin where a_id=?";
            $stmt= $mysqli->prepare($ret) ;
            $stmt->bind_param('i',$aid);
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;

            $query = "SELECT * FROM `tms_transactions` WHERE `trans_payment_status`='pending' AND `lessor_id` = ? ";
            $trans = $mysqli->prepare($query);
            $trans->bind_param('i', $aid);
            $trans->execute();
            $transactions = $trans->get_result();
            while($row=$res->fetch_object())
        {
        ?>
          <form method ="POST" class="row"> 
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Full Name</label>
                <input type="text" value="<?php echo $row->a_name;?>" required class="form-control" id="exampleInputEmail1" name="u_fname" readonly>
                <input type="text" value="<?php echo $aid;?>" required class="form-control" name="aid" hidden readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Contact</label>
                <input type="text" class="form-control" value="<?php echo $row->contact_num;?>" id="exampleInputEmail1" name="u_phone" readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Address</label>
                <input type="text" class="form-control" value="<?php echo $row->address;?>" id="exampleInputEmail1" name="u_addr" readonly>
            </div>

            <div class="form-group col-md-6" style="display:none">
                <label for="exampleInputEmail1">Category</label>
                <input type="text" class="form-control" id="exampleInputEmail1" value="User" name="u_category" readonly />
            </div>
            
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" value="<?php echo $row->a_email;?>" class="form-control" name="u_email" readonly />
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        Payment Transactions
                    </div>
                    <div class="card-body">
                        <?php if($transactions): ?>
                           <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th>Amount</th>
                                        <th>Proof of payment</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php while($tr = $transactions->fetch_object()): ?>
                                    <tr>
                                        <td>Php <?php echo number_format($tr->trans_amount, 2)?></td>
                                        <td class="w-50 text-center"><img src="../uploads/payments/<?php echo $tr->trans_proof_of_payment ?>" class="w-50" /></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody> 
                           </table>
                           <?php else: ?>
                            <p class="alert alert-info">No data found.</p>
                           <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" name="approve_user" class="btn btn-success"> <i class="fa fa-check-circle"></i> Approve Registration</button>
                <a href="admin-manage-user.php" class="btn btn-outline-danger"><i class="fa fa-times-circle"></i> Cancel</a>
            </div>
          </form>
          <!-- End Form-->
        <?php }?>
        </div>
      </div>
       
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
 <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

</body>

</html>
