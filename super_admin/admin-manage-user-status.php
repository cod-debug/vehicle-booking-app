<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
?>
<?php 
if(isset($_GET['a_id']))
    {

        extract($_GET);

        $query="update tms_admin set status=? where a_id=?";
        $stmt = $mysqli->prepare($query);
        $rc=$stmt->bind_param('si',  $status, $a_id);
        $stmt->execute();
        if($stmt)
        {

            $succ = "Status Updated";
            header("location: admin-manage-user.php");
        }
        else 
        {
            $err = "Please Try Again Later";
        }
    }
?>