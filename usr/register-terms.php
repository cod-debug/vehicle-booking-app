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

            $u_fname=$_POST['u_fname'];
            $u_lname = $_POST['u_lname'];
            $a_name = $u_fname." ".$u_lname;
            $u_phone=$_POST['u_phone'];
            $u_addr=$_POST['u_addr'];
            $u_email=$_POST['u_email'];
            $u_pwd=$_POST['u_pwd'];
            $user_type= "1";
            $u_category=$_POST['u_category'];
            $query="INSERT INTO tms_admin (a_name, contact_num, address, a_email, a_pwd, user_type) VALUES(?,?,?,?,?,?)";
            $stmt = $mysqli->prepare($query);
            $rc=$stmt->bind_param("ssssss",$a_name, $u_phone, $u_addr, $u_email, $u_pwd, $user_type);
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
        <h1>Terms &amp; Policies</h1>
        <div class="card my-3">
        <div class="card-body">
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        </div>
        <div class="text-center">
          <div class="form-group">
            <input type="checkbox" id="agreeToTerms" /> <label for="agreeToTerms">Agree to terms and policies</label>
          </div>
          <a class="d-none small btn btn-primary" id="nextBtn" href="usr-register.php">Next <i class="fa fa-angle-right"></i> </a>
          <a class="small btn btn-outline-danger" href="index.php"> <i class="fa fa-times-circle"></i> Cancel </a>
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
    $("#agreeToTerms").on("change", (e) => {
        if(e.target.checked){
            $("#nextBtn").removeClass("d-none");
        } else {
            $("#nextBtn").addClass("d-none");
        }
    });
 </script>
</body>

</html>
