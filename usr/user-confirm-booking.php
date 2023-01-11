<?php
  session_start();
  include('vendor/inc/config.php');
  include('vendor/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['u_id'];
  //Add Booking
  if(isset($_POST['book_vehicle']))
    {
            $u_id = $_SESSION['u_id'];
            //$u_fname=$_POST['u_fname'];
            //$u_lname = $_POST['u_lname'];
            //$u_phone=$_POST['u_phone'];
            //$u_addr=$_POST['u_addr'];
            
            /*
                $u_car_type = $_POST['u_car_type'];
                $u_car_regno  = $_POST['u_car_regno'];
                $u_car_bookdate = $_POST['u_car_bookdate'];
                $u_car_book_status  = $_POST['u_car_book_status'];
                $query="update tms_user set u_car_type=?, u_car_bookdate=?, u_car_regno=?, u_car_book_status=? where u_id=?";
                $stmt = $mysqli->prepare($query);
                $rc=$stmt->bind_param('ssssi', $u_car_type, $u_car_bookdate, $u_car_regno, $u_car_book_status, $u_id);
                $stmt->execute();
                    if($stmt)
                    {
                        $succ = "Booking Subitted";
                    }
                    else 
                    {
                        $err = "Please Try Again Later";
                    }
                }
            */
            
            // upload proof of payment
            $target_dir = "../uploads/payments/";
            $target_file = $target_dir . basename($_FILES["proof_of_payment"]["name"]);
            $file_name = basename($_FILES["proof_of_payment"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file);

            $target_dir_valid_id = "../uploads/valid_id/";
            $target_file_valid_id = $target_dir_valid_id . basename($_FILES["valid_id"]["name"]);
            $file_name_valid_id = basename($_FILES["valid_id"]["name"]);
            $imageFileType_valid_id = strtolower(pathinfo($target_file_valid_id, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["valid_id"]["tmp_name"], $target_file_valid_id);

            $vehicle_id = $_POST['vehicle_id'];
            $user_itd = $_POST['user_id'];
            $trans_type = "booking";
            $trans_amount = $_POST["total_amount"];
            $trans_payment_status = "pending";
            $booking_pickup_date = $_POST["booking_date_start"];
            $booking_due_date = $_POST["booking_date_end"];
            $trans_itenerary = $_POST['trans_itenerary'];
            $trans_proof_of_payment = $file_name;

            $insert = "INSERT INTO `tms_transactions` 
            (trans_type, trans_amount, trans_payment_status, vehicle_id, user_id, booking_pickup_date, booking_due_date, trans_proof_of_payment, created_by, trans_itenerary, valid_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert);
            $rc = $stmt->bind_param('sssiissssss', 
            $trans_type, $trans_amount, $trans_payment_status, $vehicle_id, $u_id, $booking_pickup_date, $booking_due_date, $trans_proof_of_payment, $u_id, $trans_itenerary, $file_name_valid_id);

            if($stmt->execute()){
                $succ = "Booking Submitted";
            }
            else {
                $err = "Please Try Again Later";
            }
    }

?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<style>
    .vehicle-image-container {
        width: 300px;
        min-height: 200px;
        border: 1px solid darkgray;
        box-shadow: 1px 1px 3px black;
        position: fixed;
        right: 10px;
        top: 10vh;
        z-index: 200;
    }

    .toggle-img {
        width: 30px;
        padding: 5px 10px;
        margin-left: -30px;
        border-radius: 0;
        position: absolute;
    }
</style>
<body id="page-top">
 <!--Start Navigation Bar-->
  <?php include("vendor/inc/nav.php");?>
  <!--Navigation Bar-->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php include("vendor/inc/sidebar.php");?>
    <!--End Sidebar-->
    <div id="content-wrapper">

      <div class="container-fluid">
      <?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $succ;?>!","success").then(() => {
                            window.location.href = "user-manage-booking.php";
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
                        swal("Failed!","<?php echo $err;?>!","error");
                    },
                        100);
        </script>

        <?php } ?>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="user-dashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item">Vehicle</li>
          <li class="breadcrumb-item ">Book Vehicle</li>
          <li class="breadcrumb-item active">Confirm Booking</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          Confirm Booking
        </div>
        <div class="card-body">
          <!--Add User Form-->
          <?php
            $aid=$_GET['v_id'];
            $ret="SELECT * FROM `tms_vehicle` 
            INNER JOIN `tms_admin`
            WHERE tms_vehicle.v_id=?
            AND `tms_admin`.`a_id` = `tms_vehicle`.`lessor_id`";
            $stmt= $mysqli->prepare($ret) ;
            $stmt->bind_param('i',$aid);
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;
            while($row=$res->fetch_object())
        {
        ?>
        <div class="vehicle-image-container bg-white">
            <button class="btn btn-secondary toggle-img" id="vic" type="button" data-collapse="hide"><i class="fa fa-angle-right" id="vic-icon"></i></button>
            <div class="p-2">
                <img src="../vendor/img/<?php echo $row->v_dpic ?>" class="w-100" />
                <hr>
                <h3 class="text-primary"><?php echo $row->v_name?> </h3>
                <h5># <?php echo $row->v_reg_no ?></h5>
                <small><i><?php echo $row->v_category?></i></small>
                <p class="text-muted"><?php echo $row->v_pass_no?> Seats</p>
            </div>
        </div>
        <form method="post" enctype="multipart/form-data" class="row">
            <div class="col-md-12">
                <small class="text-muted">Vehicle Details: </small>
                <hr>
            </div>
            
            <input type="text" value="<?php echo $row->v_id;?>" readonly class="form-control" name="vehicle_id" hidden>
            <input type="text" value="<?php echo $aid;?>" readonly class="form-control" name="user_id" hidden>

            <?php if($row->v_registration): ?>
                <div class="form-group col-md-12">
                    <a href="../vendor/img/<?php echo $row->v_registration ?>" target="_blank"><i class="fa fa-file"></i> See Vehicle Registration</a>
                </div>
                <?php else:?>
                    
                <div class="form-group col-md-12">
                    <p class="alert alert-info">No Registration Photo uploaded yet.</p>
                </div>
            <?php endif; ?>
            <div class="form-group col-md-6">
                <label for="per12Hrs">Price Per 12 Hrs</label>
                <input type="number" class="form-control" id="per12Hrs" readonly  name="v_per_12hrs" value="<?php echo $row->v_per_12hrs ?>">
            </div>

            <div class="form-group col-md-6">
                <label for="per24Hrs">Price Per 24 Hrs</label>
                <input type="number" class="form-control" id="per24Hrs" readonly name="v_per_24hrs" value="<?php echo $row->v_per_24hrs ?>">
            </div>
            <div class="form-group col-md-12" style="display:none">
                <label for="exampleInputEmail1">Book Status</label>
                <input type="text" value="Pending" class="form-control" id="exampleInputEmail1"  name="u_car_book_status">
            </div>
            <div class="col-md-12">
                <small class="text-muted">Booking Details: </small>
            <hr>
            </div>
            <div class="form-group col-md-4">
                <label for="bookingHrs">Booking Hours: </label>
                <select class="form-control" name="booking_hrs" id="bookingHrs">
                    <option value="12">12 Hrs</option>
                    <option value="24">24 Hrs</option>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="bookingDate">Pickup Date &amp; Time: </label>
                <input type="datetime-local" class="form-control" id="bookingDate"  name="booking_date_start">
            </div>

            <div class="form-group col-md-4">
                <label for="bookingDate">Due Date: </label>
                <input type="datetime-local" class="form-control" id="bookingDateEnd" readonly  name="booking_date_end">
            </div>

            <div class="col-md-12">
                <p class="text-muted"><b><i class="fa fa-exclamation-triangle"></i> Note: </b> Additional charges/fees are effective if vehicle is not returned on time.</p>
            </div>

            <div class="form-group col-md-12">
                <label>Additional Comment / Full Itinerary : </label>
                <textarea name="trans_itenerary" class="form-control"></textarea>
            </div>
            
            <div class="col-md-12">
                <small class="text-muted">Payment Details: </small>
            <hr>
            </div>
            <div class="form-group col-md-12">
                <label for="bookingHrs">Amount in total: </label>
                <input type="number" readonly name="total_amount" id="totalAmount" required class="form-control"/>
            </div>
            
            <div class="col-md-6">
                 <p class="text-muted font-weight-bold">Payment methods: </p>
                 <div>
                    <table class="table table-bordered w-100">
                        <tr>
                            <td>GCash</td>
                            <th><?php echo $row->gcash_num ?></th>
                        </tr>
                        <tr>
                            <td>Bank Transfer</td>
                            <th><i class="font-weight-normal">BPI Account #:</i> <b><?php echo $row->bpi_account ?></b></th>
                        </tr>
                    </table>
                 </div>
            </div>

            <div class="form-group col-md-6">
                <label for="bookingDate">Proof of payment: </label>
                <br />
                <input type="file" accept=".png, .jpeg, .jpg" class="" id="proofOfPayment"  name="proof_of_payment" required>
                <div class="border border-muted rounded d-none mt-2" id="previewHolder">
                    <img src="" id="proofOfPaymentPreview" class="w-100 p-2" />
                </div>
            </div>
            <div class="col-md-6">

            </div>
            <div class="form-group col-md-6">
                <label for="bookingDate">Valid ID: </label>
                <br />
                <input type="file" accept=".png, .jpeg, .jpg" class="" id="validID"  name="valid_id" required>
                <div class="border border-muted rounded d-none mt-2" id="previewHolderValidID">
                    <img src="" id="validIDPreview" class="w-100 p-2" />
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" name="book_vehicle" class="btn btn-success"><i class="fa fa-check-circle"></i> Confirm Booking</button>      
                <a name="book_vehicle" class="btn btn-outline-danger" href="usr-book-vehicle.php"><i class="fa fa-times-circle"></i> Cancel</a>         
            </div>
  
          </form>
          <!-- End Form-->
        <?php }?>
        </div>
      </div>
       
      <hr>
     

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
  <?php include('user-logout-modal.php'); ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="vendor/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="vendor/js/demo/datatables-demo.js"></script>
  <script src="vendor/js/demo/chart-area-demo.js"></script>
 <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

 <script>
 function addHoursToDate(objDate, intHours) {
    var numberOfMlSeconds = objDate.getTime();
    var addMlSeconds = (intHours * 60) * 60 * 1000;
    var newDateObj = new Date(numberOfMlSeconds + addMlSeconds);

        return newDateObj;
    }

    $("#bookingDate").change((e) => {
        setDateTimeEnd();
    });
    
    $("#bookingDate").attr("min", formatDateTimeInput(new Date()));
    $("#bookingDate").val(formatDateTimeInput(new Date()));

    // DEFAULT TOTAL VALUE
    $("#totalAmount").val($(`#per12Hrs`).val());
    $("#bookingHrs").change((e) => {
        setDateTimeEnd();
        $("#totalAmount").val($(`#per${e['target']['value']}Hrs`).val());
    });
    setDateTimeEnd();

    function setDateTimeEnd(){
        let hoursToAdd = $("#bookingHrs").val();
        let timeEnd = addHoursToDate(new Date($("#bookingDate").val()), hoursToAdd);

        $("#bookingDateEnd").val(formatDateTimeInput(timeEnd));
    }
    
    $('#proofOfPayment').change(function (e) {
        $("#proofOfPaymentPreview").attr("src", URL.createObjectURL(e.target.files[0]));
        $("#previewHolder").removeClass("d-none");
    });
    
    $('#validID').change(function (e) {
        $("#validIDPreview").attr("src", URL.createObjectURL(e.target.files[0]));
        $("#previewHolderValidID").removeClass("d-none");
    });

    function formatDateTimeInput(timeEnd) {
        let year = timeEnd.getFullYear();
        let month = timeEnd.getMonth()+1;
        let d = timeEnd.getDate();
        let hrs = timeEnd.getHours();
        let mins = timeEnd.getMinutes();
        let sec = timeEnd.getSeconds();

        let formattedDateTimeEnd = `${year}-${month < 10 ? "0"+month : month}-${d< 10 ? "0"+d : d}T${hrs < 10 ? "0"+hrs : hrs}:${mins < 10 ? "0"+mins : mins}:${sec < 10 ? "0"+sec : sec}`
        return formattedDateTimeEnd;
    }
    
    $("#vic").click((e) => {
        $(".vehicle-image-container").css("transition", ".5s");
        console.log(e.target.dataset.collapse);
        if(e.target.dataset.collapse === 'show'){
                $(".vehicle-image-container").css("right", "30px");
                setTimeout(()=>{
                $(".vehicle-image-container").css("right", "10px");
                $("#vic-icon").removeClass("fa-angle-left");
                $("#vic-icon").addClass("fa-angle-right");
                e.target.dataset.collapse = 'hide';
            }, 500)
        } else {
            $(".vehicle-image-container").css("right", "-320px");
            setTimeout(()=>{
                $(".vehicle-image-container").css("right", "-300px");
                $("#vic-icon").addClass("fa-angle-left");
                $("#vic-icon").removeClass("fa-angle-right");
                e.target.dataset.collapse = 'show';
            }, 500)
        }
    });
 </script>
</body>

</html>
