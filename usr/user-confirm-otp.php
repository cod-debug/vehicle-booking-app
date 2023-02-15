<?php
require '../vendor/autoload.php';
use Twilio\Rest\Client;

function sendSms($message){
    // Your Account SID and Auth Token from twilio.com/console
    $account_sid = 'ACb51841051872a3dc405c16caef236791';
    $auth_token = '642aac032a182a90046c39f24c2a7f93';
    // In production, these should be environment variables. E.g.:
    // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
    
    // A Twilio number you own with SMS capabilities
    $twilio_number = "+15132808168";
    $to_nummber = "+639771900912";
    
    $client = new Client($account_sid, $auth_token);
    $client->messages->create(
        // Where to send a text message (your cell phone?)
        $to_nummber,
        array(
            'from' => $twilio_number,
            'body' => $message
        )
    );
}


$otp = rand(1000,9999);
$final_messsage = "Here is your One-Time Pin (OTP): ".$otp;
sendSms($final_messsage);
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
<?php if(isset($_GET['succ'])) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $_GET['succ']; ?>!","success").then(() => {
                          window.location.href="index.php";
                        });
                    },
                        100);
        </script>

        <?php } ?>
        <?php if(isset($_GET['err'])) {?>
        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Failed!","<?php echo $_GET['err'];?>","error");
                    },
                        100);
        </script>

        <?php } ?>
  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Create An Account With Us</div>
      <div class="card-body">
        <!--Start Form-->
        <form method = "post" action="check-otp.php">
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-12">
                <div class="form-label-group">
                <input type="text" required class="form-control" id="exampleInputEmail1" min="4" name="otp_input">
                <input type="text" required value="<?php echo $otp ?>" hidden class="form-control" id="exampleInputEmail1" name="otp">
                <input type="text" required value="<?php echo $_GET['email'] ?>" class="form-control" hidden id="exampleInputEmail1" name="email">
                  <label for="firstName">OTP</label>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" name="confirm_otp" class="btn btn-success">Confirm</button>
        </form>
        <!--End FOrm-->
        <div class="text-center">
          <a class="d-block small mt-3" href="index.php">Login Page</a>
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
