<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['u_id'];
  //Add USer
  if(isset($_POST['update_password']))
    {
            $u_id = $_SESSION['u_id'];
            $u_pwd=$_POST['u_pwd'];
           // $u_category=$_POST['u_category'];
            $old_password = $_POST['old_password'];
            $query="update tms_user set u_pwd=? where u_id=? AND `u_pwd`=?";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param('sis',  $u_pwd, $u_id, $old_password);
            $stmt->execute();

            $check = "SELECT * FROM `tms_user` WHERE  u_id='$u_id' AND `u_pwd`='$old_password'";
            $check_stmt = $mysqli->prepare($check);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if($check_result->num_rows > 0){
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
            <a href="user-dashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item">Profile</li>
          <li class="breadcrumb-item active">Change Password</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          Add User
        </div>
        <div class="card-body">
        
          <form method ="POST"> 
            
            <div class="form-group" >
                <label for="exampleInputEmail1">Old Password <span class="text-danger">*<span></label>
                <input type="password" class="form-control" id="exampleInputEmail1" required name="old_password" >
            </div>
            <hr>
            <div class="form-group" >
                <label for="exampleInputEmail1">New Password <span class="text-danger">*<span></label>
                <input type="password" class="form-control" id="pass1" required onkeyup="checkPassword()" name="u_pwd">
            </div>

            <div class="form-group" >
                <label for="exampleInputEmail1">Confirm Password <span class="text-danger">*<span></label>
                <input type="password" class="form-control" id="pass2" onkeyup="checkPassword()" required name="u_pwd_confirm">
                <span id="confirmPassResult"></span>
            </div>
            
            <button type="submit" name="update_password" id="changePassBtn" disabled class="btn btn-outline-danger">Change Pasword</button>
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
