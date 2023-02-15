<?php
    include('vendor/inc/config.php');

    if(isset($_POST['confirm_otp'])){
        extract($_POST);

        if($otp_input == $otp){
            $update_status = "UPDATE `tms_user` SET `u_status` = 'active' WHERE `u_email` = '$email'";
            // print_r($update_status);
            // return false;
            $stmt = $mysqli->prepare($update_status);
            if($stmt->execute()){
                header("location:user-confirm-otp.php?email=".$email."&succ=Successfully Registered");
            } else {
                
                header("location:user-confirm-otp.php?email=".$email."&err=Query error");
            }
        } else {
            header("location:user-confirm-otp.php?email=".$email."&err=Invalid OTP");
        }
    }

?>