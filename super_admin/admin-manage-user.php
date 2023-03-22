<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['a_id'];
?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<body id="page-top">

 <?php include("vendor/inc/nav.php");?>


  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">User</a>
          </li>
          <li class="breadcrumb-item active">View Users</li>
        </ol>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-users"></i>
            Registered Users</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Business Permit</th>
                    <th>Driver's License</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <?php
                    $ret="SELECT * FROM tms_admin"; //sql code to get to ten trains randomly
                    $stmt= $mysqli->prepare($ret) ;
                    $stmt->execute() ;//ok
                    $res=$stmt->get_result();
                    $cnt=1;
                ?>
                <tbody>
                <?php 
                    while($row=$res->fetch_object()): ?>
                  <tr>
                    <td><?php echo $cnt;?></td>
                    <td><?php echo $row->a_name;?></td>
                    <td><?php echo $row->contact_num;?></td>
                    <td><?php echo $row->address;?></td>
                    <td>
                      <?php if($row->user_business_permit): ?>
                        <a href="../uploads/business_permits/<?php echo $row->user_business_permit ?>" class="btn btn-primary btn-sm" target="_blank">View Business Permit</a>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if($row->drivers_license): ?>
                        <a href="../uploads/drivers_license/<?php echo $row->drivers_license ?>" class="btn btn-primary btn-sm" target="_blank">View Driver's License</a>
                      <?php endif; ?>
                    </td>
                    <td><?php echo $row->a_email;?></td>
                    <td><?php echo $row->payment_status ? $row->payment_status : 'Pending';?></td>
                    <td>
                      <?php if($row->payment_status == 'approved'): ?>
                      <!-- <a href="admin-manage-single-usr.php?a_id=<?php echo $row->a_id;?>" class="badge badge-primary"><i class="fa fa-user-edit"></i> Update</a> -->
                      <?php endif; ?>
                      <?php if($row->a_id != 1): ?>
                        <?php if($row->payment_status != 'approved'):?>
                            <a href="admin-manage-reg-single-usr.php?a_id=<?php echo $row->a_id;?>" class="badge badge-success"><i class="fa fa-user-edit"></i> Check Registration</a>
                        <?php endif; ?>
                      <a href="admin-manage-user-payment.php?a_id=<?php echo $row->a_id;?>" class="badge badge-primary"><i class="fa fa-check"></i> Payments</a>
                      
                      <?php if($row->status == 'active'): ?>
                        <a href="admin-manage-user-status.php?a_id=<?php echo $row->a_id;?>&status=deactivated" class="badge badge-danger"><i class="fa fa-power-off"></i> Deactivate</a>
                      
                      <?php else: ?>
                        <a href="admin-manage-user-status.php?a_id=<?php echo $row->a_id;?>&status=active" class="badge badge-success"><i class="fa fa-power-on"></i> Activate</a>
                      <?php endif; ?>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php $cnt++; endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
          <?php
              date_default_timezone_set("Africa/Nairobi");
              echo "Generated : " . date("h:i:sa");
            ?> 
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <?php include("vendor/inc/footer.php");?>
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  
  <!-- Logout Modal-->
  <?php include('logout-modal.php'); ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script>
    $(document).ready(() => {
        $("#dataTable").dataTable();
    });
  </script>
</body>

</html>
