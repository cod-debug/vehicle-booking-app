<!--Server Side Scripting To inject Login-->
<?php
  //session_start();
  include('vendor/inc/config.php');
  // include('../vendor/inc/smsAPI.php');
  //include('vendor/inc/checklogin.php');
  //check_login();
  //$aid=$_SESSION['a_id'];
  //Add USer
  if(isset($_POST['add_user']))
    {

    
    $otp = rand(1000,9999);

    $u_fname=$_POST['u_fname'];
    $u_lname = $_POST['u_lname'];
    $u_phone=$_POST['u_phone'];
    $u_addr=$_POST['u_addr'];
    $u_email=$_POST['u_email'];
    $u_pwd=$_POST['u_pwd'];
    $u_category=$_POST['u_category'];
    $u_status = "otp";
    $query="insert into tms_user (u_fname, u_lname, u_phone, u_addr, u_category, u_email, u_pwd, u_status) values(?,?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('ssssssss', $u_fname,  $u_lname, $u_phone, $u_addr, $u_category, $u_email, $u_pwd, $u_status);
    $stmt->execute();
        if($stmt)
        {
            $succ = "Account Created Proceed To Log In";
        }
        else 
        {
            $err = "Please Try Again Later";
        }
    }

    if(isset($_POST['send_otp'])){
      require_once('../vendor/autoload.php');
      $to = $_POST['to'];
      $otp = rand(1000,9999);
      // return false;
      // $client = new \GuzzleHttp\Client();

      // $response = $client->request('POST', 'https://api.movider.co/v1/sms', [
      //     'form_params' => [
      //       'api_key' => '2IIMk1Hl3VR1PmjuQ54zZd0DUDu',
      //       'api_secret' => '5BjZ0QpidrENanXKbeS2T8fGUuSF6mWntoAYZS4H',
      //       'to' => $to,
      //       'from' => 'VEHICLE BOOKING',
      //       'text' => 'TEST SMS!'
      //     ],
      //     'headers' => [
      //       'accept' => 'application/json',
      //       'content-type' => 'application/x-www-form-urlencoded',
      //     ],
      //   ]);
        
      //   echo $response->getBody();
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

<body class="bg-dark">
<?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $succ;?>!","success").then(() => {
                          window.location.href="confirm-otp.php?email=<?php echo $u_email ?>";
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
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Create An Account With Us</div>
      <div class="card-body">
        <!--Start Form-->
        <form method = "post">
          <div class="form-group">
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
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_phone">
                  <label for="lastName">Contact</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
            <input type="text" class="form-control" id="exampleInputEmail1" name="u_addr">
              <label for="inputEmail">Address</label>
            </div>
          </div>
          <div class="form-group" style ="display:none">
            <div class="form-label-group">
            <input type="text" class="form-control" id="exampleInputEmail1" value="User" name="u_category">
              <label for="inputEmail">User Category</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
            <input type="email" class="form-control" name="u_email"">
              <label for="inputEmail">Email address</label>
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

</body>

</html>
