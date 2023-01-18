<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
  include('admin-check-payment.php');
  //Add USer
  if(isset($_POST['add_veh']))
    {

            $v_name=$_POST['v_name'];
            $v_reg_no = $_POST['v_reg_no'];
            $v_category=$_POST['v_category'];
            $v_pass_no=$_POST['v_pass_no'];
            $v_status=$_POST['v_status'];
            // $v_driver=$_POST['v_driver'];
            $v_per_12hrs=$_POST['v_per_12hrs'];
            $v_per_24hrs=$_POST['v_per_24hrs'];
            $v_dpic=$_FILES["v_dpic"]["name"];
            $v_registration=$_FILES["v_registration"]["name"];

            $duplicate_v_reg_no = "SELECT COUNT(v_reg_no) FROM `tms_vehicle` WHERE `v_reg_no` = '$v_reg_no'";
            $trap_dup = $mysqli->prepare($duplicate_v_reg_no);
            $exe = $trap_dup->execute();
            $res = $trap_dup->get_result();

            if($res->num_rows == 0){
              move_uploaded_file($_FILES["v_dpic"]["tmp_name"],"../vendor/img/".$_FILES["v_dpic"]["name"]);
              move_uploaded_file($_FILES["v_registration"]["tmp_name"],"../vendor/img/".$_FILES["v_registration"]["name"]);
  
              $query="insert into tms_vehicle (v_name, v_pass_no, v_reg_no, v_driver, v_category, v_dpic, v_status, v_per_12hrs, v_per_24hrs, v_registration, lessor_id) values(?,?,?,?,?,?,?,?,?,?,?)";
              $stmt = $mysqli->prepare($query);
              $rc=$stmt->bind_param('sssssssiisi', $v_name, $v_pass_no, $v_reg_no, $v_driver, $v_category, $v_dpic, $v_status, $v_per_12hrs, $v_per_24hrs, $v_registration, $aid);
              $stmt->execute();
                if($stmt)
                {
                    $succ = "Vehicle Added";
                }
                else 
                {
                    $err = "Please Try Again Later";
                }
            }
            else 
            {
                $err = "Duplicate registration number. Please try again";
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
            <a href="#">Vehicles</a>
          </li>
          <li class="breadcrumb-item active">Add Vehicle</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          Add Vehicle
        </div>
        <div class="card-body">
          <!--Add User Form-->
          <form method ="POST" enctype="multipart/form-data" class="row"> 
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Vehicle Name</label>
                <input type="text" required class="form-control" id="exampleInputEmail1" name="v_name">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Vehicle Registration Number</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="v_reg_no">
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Vehicle  Number Of Seats</label>
                <input type="number" class="form-control" id="exampleInputEmail1" name="v_pass_no">
            </div>
            <div class="form-group col-md-6 d-none">
              <label for="exampleFormControlSelect1">Driver</label>
              <select class="form-control" name="v_driver" id="exampleFormControlSelect1">
                <?php

                $ret="SELECT * FROM tms_user where u_category = 'Driver' "; //sql code to get to ten trains randomly
                $stmt= $mysqli->prepare($ret) ;
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                $cnt=1;
                while($row=$res->fetch_object())
                {
                ?>
                <option><?php echo $row->u_fname;?> <?php echo $row->u_lname;?></option>
                <?php }?> 
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="exampleFormControlSelect1">Vehicle Category</label>
              <input type="text" name="v_category" class="form-control" list="v_category" />
              <datalist id="v_category">
                <option>Bus</option>
                <option>Sedan</option>
                <option>SUV</option>
                <option>Van</option>
              </datalist>
            </div>

            <div class="form-group col-md-6" >
              <label for="exampleFormControlSelect1">Vehicle Status</label>
              <select class="form-control" name="v_status" id="exampleFormControlSelect1">
                <option selected>Available</option>
                <option>Unavailable</option>
              </select>
            </div>

            <div class="form-group col-md-6 col-md-12">
                <label for="exampleInputEmail1">Vehicle Picture</label>
                <br />
                <input type="file" class="btn btn-success" id="exampleInputEmail1" name="v_dpic">
            </div>

            <div class="form-group col-md-6">
                <label for="exampleInputEmail1">Registration Picture</label>
                <br />
                <input type="file" class="btn btn-success" id="exampleInputEmail1" name="v_registration">
            </div>
            <div class="col-md-12">
                <h4>Prices: </h4>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Every 12 hrs</label>
                        <input type="number" class="form-control" name="v_per_12hrs" />
                    </div>
                    <div class="form-group col-md-6">
                        <label>Every 24 hrs</label>
                        <input type="number" class="form-control" name="v_per_24hrs" />
                    </div>
                </div>
            </div>

            <button type="submit" name="add_veh" class="btn btn-success">Add Vehicle</button>
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
