<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
?>
<!DOCTYPE html>
<html lang="en">
<?php
    
  if(isset($_POST['submit_payment']))
    {
            $target_dir = "../uploads/payments/";
            $target_file = $target_dir . basename($_FILES["proof_of_payment"]["name"]);
            $file_name = basename($_FILES["proof_of_payment"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'png'];
            move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file);

            $lessor_id = $_POST['lessor_id'];
            $amount = $_POST['amount'];

            $q = "INSERT INTO `tms_transactions` (trans_payment_status, trans_type, trans_amount, lessor_id, trans_proof_of_payment, created_by) VALUES ('pending', 'registration', $amount, $lessor_id, '$file_name', $lessor_id)";
            $stmt = $mysqli->prepare($q)->execute();
            if($stmt){
                $succ = "Successfully submitted payment";
            } else {
                $err = "Something went wrong during submission";
            }
    }
?>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Vehicle Booking System Transport Saccos, Matatu Industry">
  <meta name="author" content="MartDevelopers">

  <title>Vehicle Booking System - Admin Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">
 <!--Start Navigation Bar-->

    <?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $succ;?>!","success").then(() => {
                            window.location.href='index.php';
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
  
  <?php include("vendor/inc/nav.php");?>
  <!--Navigation Bar-->

  <div id="wrapper">
  
     <?php
  
      $month = date('m');
      $q = "SELECT * FROM `tms_transactions` WHERE MONTH(trans_created_at) = $month AND trans_type='registration' AND `lessor_id`=$aid AND `trans_payment_status` = 'pending'";
      $stmt = $mysqli->prepare($q);
      $exe = $stmt->execute();
      $res = $stmt->get_result();

      $pending_payment_count = $res->num_rows;

    ?>
    <!-- Sidebar -->
    <!--End Sidebar-->
    <div id="content-wrapper">

      <div class="container-fluid">
        <?php if(!$pending_payment_count): ?>
        <p class="alert alert-warning"><b><i class="fa fa-exclamation-triangle"></i> Note: </b> You've been redirected to this portal for you have no payment for this month yet.</p>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Lessor</a>
          </li>
          <li class="breadcrumb-item active">Payment Portal</li>
        </ol>
        <div class="card">
            <div class="card-header">
                Monthly Payment: (<b><?php echo date("M Y"); ?></b>)
            </div>
            <div class="card-body">
                <form method = "post" enctype="multipart/form-data">
                  <div class="form-group">
                     <p class="text-muted font-weight-bold">Payment methods: </p>
                     <div>
                        <table class="table table-bordered w-100">
                            <tr>
                                <td>GCash</td>
                                <th>09123456789</th>
                            </tr>
                            <tr>
                                <td>Bank Transfer</td>
                                <th><i class="font-weight-normal">BPI Account #:</i> <b>112233445566</b></th>
                            </tr>
                        </table>
                     </div>
                     <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="form-label-group">
                                <input type="number" class="form-control" required name="amount" id="paymentAmount" min="4000">
                                <input type="number" class="form-control" hidden required name="lessor_id" value="<?php echo $aid ?>">
                                <label for="paymentAmount">Amount</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group">
                                <input type="file" accept=".jpg, .png" class="form-control" required name="proof_of_payment" id="proofOfPayment">
                                <label for="proofOfPayment">Proof of Payment</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">
                            <div class="proof-preview-container">
                                <img id="proofPreview" class="w-100 border rounded p-1" src=''/>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="text-right">
                  <button type="submit" name="submit_payment" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Submit Payment</button>
                  </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <p class="alert alert-warning"><b><i class="fa fa-exclamation-triangle"></i> Note: </b> The administrator is now validating your payment. Kindly wait for awhile.</p>
        <?php endif; ?>
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->

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
            <span aria-hidden="true">�</span>
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
  <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

 <script>
    $('#proofOfPayment').change(function (e) {
        $("#proofPreview").attr("src", URL.createObjectURL(e.target.files[0]));
        $("#proofPreview").removeClass("d-none")
        console.log(URL.createObjectURL(event.target.files[0]));
    });
 </script>

</body>

</html>
