<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  include('admin-check-payment.php');
  //Add USer
  if(isset($_POST['change_pwd']))
    {

      $u_id = $_SESSION['a_id'];
      $u_pwd=$_POST['a_pwd'];
     // $u_category=$_POST['u_category'];
      $old_password = $_POST['old_password'];

      $check = "SELECT * FROM `tms_admin` WHERE  a_id='$u_id' AND `a_pwd`='$old_password'";
      $check_stmt = $mysqli->prepare($check);
      $check_stmt->execute();
      $check_result = $check_stmt->get_result();

      if($check_result->num_rows > 0){
        $query="update tms_admin set a_pwd=? where a_id=? AND `a_pwd`=?";
        $stmt = $mysqli->prepare($query);
        $rc=$stmt->bind_param('sis',  $u_pwd, $u_id, $old_password);
        $stmt->execute();
        if($stmt)
        {

            $succ = "Password Updated";
        }
        else 
        {
            $err = "Please Try Again Later";
        }
      } else {
        {
            $err = "Old password does not match";
        }
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
                        swal("Failed!","<?php echo $err;?>!","error");
                    },
                        100);
        </script>

        <?php } ?>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Admin Update Password</li>
        </ol>
        <hr>
        <div class="card">
        <!-- <img src="../vendor/img/services_banner.jpg" class="card-img-top" alt="..."> -->
        
        <div class="card-header"> 
          <h2> Change Password</h2>
        </div>
        <div class="card-body"
          <div class="">
              
              <form method ="post">                    
                  <div class="form-group">
                      <label for="exampleInputPassword1">Old Password</label>
                      <input type="password" name="old_password" class="form-control">
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">New Password</label>
                      <input type="password" name="a_pwd" id="pass1"  onkeyup="checkPassword()" class="form-control">
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">Confirm New Password</label>
                      <input type="password" class="form-control" id="pass2"  onkeyup="checkPassword()" required>
                      <span id="confirmPassResult"></span>
                  </div>
                  <button type="submit" name="change_pwd" id="changePassBtn" disabled class="btn btn-outline-danger">Change Password</button>
              </form>
      </div>
        </div>
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

<script>
 function checkPassword(){
   let pass1 = $("#pass1").val();
   let pass2 = $("#pass2").val();
   let confirmPassResult = $("#confirmPassResult");
   let changePassBtn = $("#changePassBtn");

   if(pass1 && pass2){
    if(pass1 === pass2){
      confirmPassResult.html("<b class='text-success'><i class='fa fa-check-circle'></i> Password matched.</b>");
      changePassBtn.removeAttr("disabled");
    } else {
      confirmPassResult.html("<b class='text-danger'><i class='fa fa-times-circle'></i> Password do not match</b>");
      changePassBtn.attr("disabled", true);
    }
   } else {
    confirmPassResult.html("");
   }
 }
</script>

</body>

</html>
