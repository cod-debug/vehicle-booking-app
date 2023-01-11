<?php
  
  $month = date('m');
  $q = "SELECT * FROM `tms_transactions` WHERE MONTH(trans_created_at) = $month AND trans_type='registration' AND `lessor_id`=$aid AND `trans_payment_status` = 'approved'";
  $stmt = $mysqli->prepare($q);
  $exe = $stmt->execute();
  $res = $stmt->get_result();

  $payment_count = $res->num_rows;
  if(!$payment_count) {
	header("location: admin-payment-portal.php");
  }

?>