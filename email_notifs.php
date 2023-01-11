<?php

include 'admin/vendor/inc/mailer/PHPMailer.php';
include 'admin/vendor/inc/mailer/SMTP.php';
include 'admin/vendor/inc/mailer/Exception.php';

use PhpMailer\PHPMailer\PHPMailer; 
use PhpMailer\PHPMailer\SMTP; 
use PhpMailer\PHPMailer\Exception; 

$month = date('m');
$q = "SELECT * FROM `tms_transactions` WHERE MONTH(trans_created_at) = $month AND trans_type='registration'";
$stmt = $mysqli->prepare($q);
$stmt->execute();
$res = $stmt->get_result();

$exclude = array();
while($row = $res->fetch_object()){
    array_push($exclude, $row->lessor_id);
}

if($exclude){
  $string_list = implode(",", $exclude);
  $q_email = "SELECT * FROM `tms_admin` WHERE `a_id` NOT IN ($string_list)";
} else {
  $q_email = "SELECT * FROM `tms_admin` WHERE `user_type` != 2";
}
$n_stmt = $mysqli->prepare($q_email);
$n_stmt->execute();
$to_be_emailed = $n_stmt->get_result();

if($to_be_emailed->num_rows > 0){
    while($r = $to_be_emailed->fetch_object()){
        $recipient = $r->a_email;
        
        $host = "smtp.gmail.com";
        $port = 587;
        $secure = "tls";
      //  or the following configurations through SSL should work as well. 
      //  $port = 465;
      //  $secure = "ssl";
        // $username = "vehicle.booking.info@gmail.com";
        $username = "vehicle.booking.care@gmail.com";
        $password = "kifwddphdkrsnuxa";
        
        try {
          $mailer = new PHPMailer(true);
          // $mailer->SMTPDebug = 1;
          $mailer->IsHTML(true);
          $mailer->IsSMTP();
          $mailer->From = $username;
          $mailer->FromName = $username;
          $mailer->ClearAllRecipients();
          $mailer->AddAddress($recipient);
          $mailer->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
            );
          $mailer->Subject = "Accoung Deactivation Notice";
          $mailer->Body = "
          <center>
            <div style='border: 5px solid darkslategray; font-family: century gothic; padding-bottom: 20px;'>
              <h2 style='background-color: darkslategray; margin: 0; padding: 10px 0; color: white;'>ACCOUNT DEACTIVATION</h2>
                <h3>Vehicle Booking System</h3>
                <p>You have unsettled accounts.</p>
                <a class='btn btn-primary text-white' href='http://localhost/VehicleBooking-PHP/admin/'>Redirect to Login</a>
            </div>
        </center>";

          $mailer->SMTPAuth   = true;       // enable SMTP authentication
          $mailer->SMTPSecure = $secure;    // sets the prefix to the servier
          $mailer->Host       = $host;      // sets GMAIL as the SMTP server
          $mailer->Port       = $port;      // set the SMTP port for the GMAIL server
          $mailer->Username   = $username;  // GMAIL username
          $mailer->Password   = $password;  // GMAIL password 
          if($r->has_sent_notif == 'no'){
            $result = $mailer->Send(); 
            $update_user = "UPDATE `tms_admin` SET `has_sent_notif` = 'yes' WHERE  `a_id` = $r->a_id";
            $u_stmt = $mysqli->prepare($update_user);
            $u_stmt->execute();
          } else {

          }
        } catch  (Exception $e) {
          // var_dump($e);
        }  
    }
}

?>