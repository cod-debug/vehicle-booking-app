<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  //Add USer
  if(isset($_POST['add_user']))
    {

            $desc=$_POST['desc'];
            $query = "UPDATE `tms_terms` SET `terms_desc` = ?";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param('s', $desc);
            $stmt->execute();
                if($stmt)
                {
                    $succ = "Terms Update";
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
        
        <?php
          $select = "SELECT * FROM `tms_terms` WHERE `terms_id` = 1";
          $selected = $mysqli->prepare($select);
          $selected->execute();
          $res=$selected->get_result()->fetch_object();
        ?>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Settings</a>
          </li>
          <li class="breadcrumb-item active">Terms &amp; Polcies</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          Update Terms &amp; Policies
        </div>
        <div class="card-body">
          <!--Add User Form-->
          <form method ="POST" class="row"> 
            <div class="form-group col-md-6 col-sm-12">
                <label for="exampleInputEmail1">Terms & Policies</label>
                <textarea type="text" required class="form-control" id="exampleInputEmail1" name="desc" ><?php echo $res->terms_desc ?></textarea>
            </div>
            <div class="col-md-12">
              <button type="submit" name="add_user" class="btn btn-success">Save Updates</button>
            </div>
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

</body>

</html>
