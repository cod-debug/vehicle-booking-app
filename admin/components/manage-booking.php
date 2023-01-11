        
        <div class="card mb-3 mt-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Bookings <?php if (isset($start) && isset($end)){ echo "<br><b>".date_format(date_create($start), "M d, Y")."</b> to <b>".date_format(date_create($end), "M d, Y")."</b>";} ?></div>
          <div class="card-body">
            <div class="table-responsive">
              <?php 
                  if(isset($start) && isset($end)){
                      $is_report = true;
                      $ret="SELECT * FROM tms_transactions 
                      INNER JOIN tms_user
                      INNER JOIN tms_vehicle
                      WHERE tms_transactions.trans_type = 'booking'
                      AND tms_user.u_id = tms_transactions.user_id
                      AND tms_transactions.vehicle_id = tms_vehicle.v_id
                      AND date(tms_transactions.trans_created_at) >= date('$start') 
                      AND date(tms_transactions.trans_created_at) <= date('$end')";
                  } else {
                    $is_report = false;
                      $ret="SELECT * FROM tms_transactions 
                      INNER JOIN tms_user
                      INNER JOIN tms_vehicle
                      WHERE tms_transactions.trans_type = 'booking'
                      AND tms_user.u_id = tms_transactions.user_id
                      AND tms_transactions.vehicle_id = tms_vehicle.v_id
                      AND tms_transactions.parent_trans_id = 0";
                  } //get all bookings
                  $stmt= $mysqli->prepare($ret) ;
                  $stmt->execute() ;//ok
                  $res=$stmt->get_result();
                  $cnt=1;

                  if($res->num_rows):

              ?>
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Timestamp</th>
                    <th>Client Name</th>
                    <th>Phone</th>
                    <th>Vehicle</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Reg No</th>
                    <th>Booking date</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <?php  if(!$is_report):?>
                      <th>Action</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                
                <tbody>
                <?php
                  $total = 0;
                  while($row=$res->fetch_object()):

                  $badge = "badge-success";
                  switch ($row->trans_payment_status) {
                    case 'pending':
                          $badge = "badge-info";
                      break;
                    case 'cancelled':
                      $badge = "badge-warning";
                      break;
                    case 'disapproved':
                          $badge = "badge-danger";
                      break;
                    case 'approved':
                          $badge = "badge-success";
                      break;
                    case 'returned':
                          $badge = "badge-primary";
                      break;
                    default:
                          $badge = "badge-muted";
                      break;
                  }
                  
                  $total += $row->trans_amount;
                ?>
                  <tr>
                    <td><?php echo date_format(date_create($row->trans_created_at), "M d, Y h:m a");?></td>
                    <td><?php echo $row->u_fname;?> <?php echo $row->u_lname;?></td>
                    <td><?php echo $row->u_phone;?></td>
                    <td><?php echo $row->v_name;?></td>
                    <td><?php echo $row->v_category;?></td>
                    <td><?php echo $row->v_reg_no;?></td>
                    <td><?php echo date_format(date_create($row->booking_pickup_date), "M d, Y h:m a");?></td>
                    <td><span class = "badge <?php echo $badge ?>"><?php echo $row->trans_payment_status ?></span></td>
                    <td>₱ <?php echo number_format($row->trans_amount, 2);?></td>
                    <!-- VIEW DETAILS BUTTON -->
                    <?php  if(!$is_report):?>
                      <td>
                        <?php if($row->parent_trans_id == 0):?>
                          <a href="admin-approve-booking.php?t_id=<?php echo $row->trans_id;?>" class="badge badge-info"><i class = "fa fa-eye"></i> View Details</a></i>                  
                        <?php endif; ?>
                      </td>
                    <?php endif; ?>
                  </tr>
                  <?php  $cnt = $cnt +1; endwhile;?>
                  
                </tbody>
                <tfoot>
                  <tr>
                    <th>Total: </th>
                    <th colspan="9" class="text-right"><h4>₱ <?php echo number_format($total, 2) ?></h4></th>
                  </tr>
                </tfoot>
              </table>
              <?php else: ?>
              <p class="alert alert-info">No data found.</p>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Asia/Manila");
              echo "Time loaded " . date("h:i:s a");
            ?> 
        </div>