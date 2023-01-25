<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  include('admin-check-payment.php');
  //Add USer
  if(isset($_POST['upload_drivers_license']))
    {
      
      $target_dir = "../uploads/drivers_license/";
      $target_file = $target_dir . basename($_FILES["drivers_license"]["name"]);
      $file_name = basename($_FILES["drivers_license"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      $allowedTypes = ['jpg', 'png'];

      $update_drivers_license = "UPDATE `tms_admin` SET `drivers_license` = '$file_name' WHERE `a_id` = '$aid'";
      $stmt = $mysqli->prepare($update_drivers_license);
      if($stmt->execute()){
        $res = $stmt->get_result();
        move_uploaded_file($_FILES["drivers_license"]["tmp_name"], $target_file);
        $succ = "Successfully saved updates";
      } else {
        $err="Something went wrong";
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
          <li class="breadcrumb-item active">Admin Upload Driver's License</li>
        </ol>
        <hr>
        <div class="card">
        <!-- <img src="../vendor/img/services_banner.jpg" class="card-img-top" alt="..."> -->
        
        <div class="card-header"> 
          <h2> Upload Driver's License</h2>
        </div>
        <div class="card-body">
          <div class="">
              <form method = "post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="exampleInputPassword1">Driver's License: </label>
                          <br />
                          <input type="file" accept=".jpg, .png" name="drivers_license" id="driversLicense" />
                      </div>
                      <button type="submit" name="upload_drivers_license" class="btn btn-primary"><i class="fa fa-upload"></i> Upload License</button>
                    </div>
                    <div class="col-md-6">
                        <div class="proof-preview-container">
                            <img id="proofPreview" class="w-100 border rounded p-1" src=''/>
                        </div>
                    </div>
                  </div>
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
  $('#driversLicense').change(function (e) {
    $("#proofPreview").attr("src", URL.createObjectURL(e.target.files[0]));
    $("#proofPreview").removeClass("d-none")
    // console.log(URL.createObjectURL(event.target.files[0]));
  });
</script>

</body>

</html>
