<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  //Add USer
  if(isset($_POST['add_user']))
    {

            // upload proof of payment
            $target_dir = "../uploads/business_permits/";
            $target_file = $target_dir . basename($_FILES["business_permit"]["name"]);
            $business_permit_file_name = basename($_FILES["business_permit"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["business_permit"]["tmp_name"], $target_file);

            $u_fname=$_POST['u_fname'];
            // $u_lname = $_POST['u_lname'];
            $u_phone=$_POST['u_phone'];
            $u_addr=$_POST['u_addr'];
            $u_email=$_POST['u_email'];
            $u_pwd=$_POST['u_pwd'];
            $u_category=$_POST['u_category'];
            $payment_status = "approved";
            $status = "active";

            $query="insert into tms_admin (a_name, contact_num, address, a_email, a_pwd, user_business_permit, user_type, payment_status, status) values(?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param('sssssssss', $u_fname, $u_phone, $u_addr, $u_email, $u_pwd, $business_permit_file_name, $u_category, $payment_status, $status);
            $stmt->execute();
            if($stmt)
            {
                $succ = "User Added";
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
      <?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $succ;?>!","success");
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
            <a href="#">Users</a>
          </li>
          <li class="breadcrumb-item active">Add User</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          Add User
        </div>
        <div class="card-body">
          <!--Add User Form-->
          <form method ="POST" class="row" enctype="multipart/form-data" > 
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">Full Name</label>
                <input type="text" required class="form-control" id="exampleInputEmail1" name="u_fname">
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <!-- <label for="exampleInputEmail1">Last Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_lname"> -->
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">Contact</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_phone">
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">Address</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_addr">
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">Branch</label>
                <select class="form-control" id="exampleInputEmail1" name="user_type">
                    <?php
                          $result ="SELECT * FROM tms_branch WHERE status = 1";
                          $stmt = $mysqli->prepare($result);
                          $stmt->execute();
                          $res=$stmt->get_result();
                          while($rec = $res->fetch_object()):
                    ?>
                        <option><?php echo $rec->branch_name ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">User Role</label>
                <select class="form-control" id="exampleInputEmail1" name="u_category">
                    <option value="1">Lessor</option>
                    <option value="2">Super Admin</option>
                </select>
            </div>
            
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" name="u_email">
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" name="u_pwd" id="exampleInputPassword1">
            </div>
            <div class="form-group col-md-6">
                <label for="bookingDate">Business Permit: </label>
                <br />
                <input type="file" class="" id="businessPermit"  name="business_permit" required>
                <div class="border border-muted rounded d-none mt-2" id="previewHolder">
                    <img src="" id="proofOfPaymentPreview" class="w-100 p-2" />
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-12">
            </div>

            <button type="submit" name="add_user" class="btn btn-success">Add User</button>
          </form>
          <!-- End Form-->
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
<script>
  
  $('#businessPermit').change(function (e) {
      $("#proofOfPaymentPreview").attr("src", URL.createObjectURL(e.target.files[0]));
      $("#previewHolder").removeClass("d-none");
  });

</script>
</body>

</html>
