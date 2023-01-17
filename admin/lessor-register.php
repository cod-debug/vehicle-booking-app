<!--Server Side Scripting To inject Login-->
<?php
  //session_start();
  include('vendor/inc/config.php');
  //include('vendor/inc/checklogin.php');
  //check_login();
  //$aid=$_SESSION['a_id'];
  //Add USer
  if(isset($_POST['add_user']))
    {
            $target_dir = "../uploads/payments/";
            $target_file = $target_dir . basename($_FILES["proof_of_payment"]["name"]);
            $file_name = basename($_FILES["proof_of_payment"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'png'];
            move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file);
            
            $target_dir = "../uploads/business_permits/";
            $target_file = $target_dir . basename($_FILES["business_permit"]["name"]);
            $business_permit_file_name = basename($_FILES["business_permit"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["business_permit"]["tmp_name"], $target_file);

            $u_fname=$_POST['u_fname'];
            $u_lname = $_POST['u_lname'];
            $a_name = $u_fname." ".$u_lname;
            $u_phone=$_POST['u_phone'];
            $u_addr=$_POST['u_addr'];
            $u_email=$_POST['u_email'];
            $u_pwd=$_POST['u_pwd'];
            $gcash_num=$_POST['gcash_num']; 
            $bpi_account=$_POST['bpi_account'];
            $user_type= "1";
            $u_category=$_POST['u_category'];
            $is_new = true;
            
            $query="INSERT INTO tms_admin (a_name, contact_num, address, a_email, a_pwd, user_type, user_business_permit, gcash_num, bpi_account, is_new) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param("ssssssssss",$a_name, $u_phone, $u_addr, $u_email, $u_pwd, $user_type, $business_permit_file_name, $gcash_num, $bpi_account, $is_new);

            $stmt->execute();
                if($stmt)
                {
                    $trans_type = "registration";
                    $lessor_id = $stmt->insert_id;
                    $payment_status = "pending";
                    $trans_amount = $_POST["amount"];

                    $trans_q = "INSERT INTO `tms_transactions` (trans_type, trans_amount, trans_payment_status, lessor_id, trans_proof_of_payment) VALUES (?,?,?,?,?)";
                    $trans_stmt = $mysqli->prepare($trans_q);
                    $trans_rc = $trans_stmt->bind_param("sssss", $trans_type, $trans_amount, $payment_status, $lessor_id, $file_name);
                    $trans_stmt->execute();
                    if(!$trans_stmt){
                        $err = "Error in uploading proof of payment. Please Try Again Later";
                    } else {
                        $succ = "Account Created Proceed To Log In";
                    }
                }
                else 
                {
                    $err = "Please Try Again Later";
                }
            }
?>
<!--End Server Side Scriptiong-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Tranport Management System, Saccos, Matwana Culture">
  <meta name="author" content="MartDevelopers ">

  <title>Transport Managemennt System Client- Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>
<style>
    #proofPreview {
        width: 100%;
    }
</style>
<body class="bg-dark">
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
  <div class="container">
    <div class="card card-register mx-auto mt-5 mb-5">
             <div class="card-header">
                <i class="fa fa-exclamation-triangle"></i> <b> Be a Lessor! </b> Subscribe for only Php 4,000.00 a month.
             </div>
      <!--<div class="card-header">Create An Account With Us</div> -->
      <div class="card-body">
        <!--Start Form-->
        <form method = "post" enctype="multipart/form-data">
          <div class="form-group">
            <p class="text-muted font-weight-bold">User Information: </p>
            <div class="form-row">
              <div class="col-md-4">
                <div class="form-label-group">
                <input type="text" required class="form-control" id="exampleInputEmail1" name="u_fname">
                  <label for="firstName">First name</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-label-group">
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_lname">
                  <label for="lastName">Last name</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-label-group">
                <input type="text" class="form-control" id="exampleInputContact" name="u_phone" autocomplete="off">
                  <label for="exampleInputContact">Contact</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
            <input type="text" class="form-control" id="exampleInputEmail1" name="u_addr">
              <label for="inputEmail">Address</label>
            </div>
            <div class="form-label-group mt-3">
            <input type="text" class="form-control" id="exampleInputBusName" name="u_business_name">
              <label for="exampleInputBusName">Car Rental Business Name</label>
            </div>
          </div>
          <div class="form-group" style ="display:none">
            <div class="form-label-group">
            <input type="text" class="form-control" id="exampleInputEmail1" value="User" name="u_category">
              <label for="inputEmail">User Category</label>
            </div>
          </div>

          <div class="form-group">
            <p class="text-muted font-weight-bold">Online Banking Details: </p>
            <div class="form-row">
              <div class="col-md-6">
                <div class="form-label-group">
                <input type="text" required class="form-control" id="gcash_num" name="gcash_num">
                  <label for="gcash_num">GCash</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-label-group">
                <input type="text" class="form-control" id="bpi_account" name="bpi_account">
                  <label for="bpi_account">BPI Account Number</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
             <p class="text-muted font-weight-bold">Account Information: </p>
            <div class="form-label-group">
            <input type="email" class="form-control" name="u_email" id="u_email" autocomplete="off" value="">
              <label for="u_email">Email address</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-12">
                <div class="form-label-group">
                <input type="password" class="form-control" name="u_pwd" id="exampleInputPassword1">
                  <label for="inputPassword">Password</label>
                </div>
              </div>
            </div>
          </div>

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
                <div class="col-md-12 mb-3">
                    <div class="form-label-group">
                        <input type="number" class="form-control" required name="amount" id="paymentAmount">
                        <label for="paymentAmount">Amount</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-label-group">
                        <input type="file" accept=".jpg, .png, .pdf" class="form-control" required name="business_permit" id="businessPermit">
                        <label for="businessPermit">Business Permit</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-label-group">
                        <input type="file" accept=".jpg, .png" class="form-control" required name="proof_of_payment" id="proofOfPayment">
                        <label for="proofOfPayment">Proof of Payment</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="">
                        <a id="businessPermitPreview" class="btn btn-primary w-100 border rounded p-1 d-none" target="_blank"><i class="fa fa-eye"></i> Preview Business Permit</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="proof-preview-container">
                        <img id="proofPreview" class="w-100 border rounded p-1" src=''/>
                    </div>
                </div>
            </div>
          </div>
          <button type="submit" name="add_user" class="btn btn-success">Create Account</button>
        </form>
        <!--End FOrm-->
        <div class="text-center">
          <a class="d-block small mt-3" href="index.php">Login Page</a>
          <a class="d-block small" href="usr-forgot-pwd.php">Forgot Password?</a>
        </div>
        
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

 <script>
    $('#proofOfPayment').change(function (e) {
        $("#proofPreview").attr("src", URL.createObjectURL(e.target.files[0]));
        $("#proofPreview").removeClass("d-none")
        console.log(URL.createObjectURL(event.target.files[0]));
    });
    $('#businessPermit').change(function (e) {
        $("#businessPermitPreview").attr("href", URL.createObjectURL(e.target.files[0]));
        $("#businessPermitPreview").removeClass("d-none")
        console.log(URL.createObjectURL(event.target.files[0]));
    });
 </script>
</body>

</html>
