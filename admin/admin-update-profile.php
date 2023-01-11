<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  include('admin-check-payment.php');
  //Add USer
  if(isset($_POST['update_profile']))
  {
    $aid=$_SESSION['a_id'];
    $a_name=$_POST['a_name'];
    $address=$_POST['address'];
    $gcash_num=$_POST['gcash_num'];
    $bpi_account=$_POST['bpi_account'];
    // $u_pwd=$_POST['u_pwd'];
    $query="update tms_admin set a_name=?, address=?, gcash_num=?, bpi_account=? where a_id=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssssi', $a_name, $address, $gcash_num, $bpi_account, $aid);
    $stmt->execute();
    
    if($stmt)
    {
        $succ = "Profile Updated";
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
          <li class="breadcrumb-item active">My Profile</li>
        </ol>
        <hr>
        <div class="card">
        <!-- <img src="../vendor/img/services_banner.jpg" class="card-img-top" alt="..."> -->
        
        <div class="card-header"> 
          <h2>My Profile</h2>
        </div>
        <div class="card-body">
            <!--Add User Form-->
            <?php
              $ret="select * from tms_admin where a_id=?";
              $stmt= $mysqli->prepare($ret) ;
              $stmt->bind_param('i',$aid);
              $stmt->execute() ;//ok
              $res=$stmt->get_result();
              //$cnt=1;
              while($row=$res->fetch_object()):
          ?>
            <form method ="POST"> 
              <div class="form-group">
                  <label for="exampleInputEmail1">Full Name</label>
                  <input type="text" value="<?php echo $row->a_name;?>" required class="form-control" id="exampleInputEmail1" name="a_name">
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Email Address</label>
                  <input type="text" class="form-control" value="<?php echo $row->a_email;?>" id="exampleInputEmail1" name="a_email" readonly>
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">Address</label>
                  <input type="text" class="form-control" value="<?php echo $row->address;?>" id="exampleInputEmail1" name="address">
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">GCash Number</label>
                  <input type="text" class="form-control" value="<?php echo $row->gcash_num;?>" id="exampleInputEmail1" name="gcash_num">
              </div>
              <div class="form-group">
                  <label for="exampleInputEmail1">BPI Account Number</label>
                  <input type="text" class="form-control" value="<?php echo $row->bpi_account;?>" id="exampleInputEmail1" name="bpi_account">
              </div>
              <button type="submit" name="update_profile" class="btn btn-outline-success">Update Profile </button>
            </form>
            <!-- End Form-->
        <?php endwhile; ?>
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
