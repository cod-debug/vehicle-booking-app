<?php
  session_start();
  include('admin/vendor/inc/config.php');
  include('email_notifs.php');
  //include('vendor/inc/checklogin.php');
  //check_login();
  //$aid=$_SESSION['a_id'];
?>
<!DOCTYPE html>
<html lang="en">
<!--Head-->
<?php include("vendor/inc/head.php");?>

<body>

  <!-- Navigation -->
  <?php include("vendor/inc/nav.php");?>
<!--End Navigation-->

  <header>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      
      <div class="carousel-inner" role="listbox">
        <!-- Slide One - Set the background image for this slide in the line below -->
        <div class="carousel-item active" style="background-image: url('vendor/img/placeholder.png')">
        </div>
        <!-- Slide Two - Set the background image for this slide in the line below -->
        <div class="carousel-item" style="background-image: url('vendor/img/placeholder.png')">
        </div>
        <!-- Slide Three - Set the background image for this slide in the line below -->
        <div class="carousel-item" style="background-image: url('vendor/img/placeholder.png')">
        </div>

        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </header>

  <!-- Page Content -->
  <div class="container">

    <h1 class="my-4">Welcome to Vehicle Booking System</h1>

    <!-- Marketing Icons Section -->
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card h-100">
          <h4 class="card-header">Why Us</h4>
          <div class="card-body">
            <p class="card-text">We create accountability in the transport sector, enhance mobility and ease of accessing various transport modes</p>
          </div>
          
        </div>
      </div>
      <div class="col-lg-6 mb-4">
        <div class="card h-100">
          <h4 class="card-header">Core Values</h4>
          <div class="card-body">
            <p class="card-text">
              Excellence, Trust and Openness, Integrity, Take Responsibility, Customer Orientation             
            </p>
          </div>
        </div>
      </div>
    </div>
    
    
    <?php

      $ret="SELECT * FROM tms_vehicle INNER JOIN `tms_admin` ON `tms_admin`.`a_id` = `tms_vehicle`.`lessor_id`  ORDER BY RAND() LIMIT 10 "; //get all feedbacks
      $stmt= $mysqli->prepare($ret) ;
      $stmt->execute() ;//ok
      $res=$stmt->get_result();
      $cnt=1;
      while($row=$res->fetch_object())
      {
    ?>
    <!-- Project One -->
    <div class="row">
      <div class="col-md-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-md-0" src="vendor/img/<?php echo $row->v_dpic;?>" alt="">
        </a>
      </div>
      <div class="col-md-5">
        <h3><?php echo $row->v_category;?></h3>
        <div class="row">
            <div class="col-md-7">
                <table class="table w-100">
                    <thead>
                        <tr>
                            <td><b>Name: </b></td>
                            <td><?php echo $row->v_name ?></td>
                        </tr>
                        <tr>
                            <td><b>Seats Available: </b></td>
                            <td><?php echo $row->v_pass_no ?></td>
                        </tr>
                        <tr>
                            <td><b>Availability: </b></td>
                            <td><span class="badge badge-success">Available</span></td>
                        </tr>
                        <tr>
                            <td><b>Registration No. : </b></td>
                            <td><?php echo $row->v_reg_no ?></td>
                        </tr>
                        <tr>
                            <td><b>Lessor : </b></td>
                            <td><?php echo $row->a_name ?></td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-5">
                <div class="text-right">
                    <p class="alert alert-primary">Php <?php echo number_format($row->v_per_12hrs, 2) ?> </p>
                    <p class="mt-2"><small>Per <b>12hrs</b></small></p>
                </div>
                <div class="text-right">
                    <p class="alert alert-primary">Php <?php echo number_format($row->v_per_24hrs, 2) ?> </p>
                    <p class="mt-2"><small>Per <b>24hrs</b></small></p>
                </div>
            </div>
        </div>
        <a class="btn btn-success" href="usr/?v_id=<?php echo $row->v_id?>">Hire Vehicle
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
        
      </div>
    </div>
    <!-- /.row -->

    <hr>
      <?php }?>
    

  </div>
  <!-- /.container -->

  <!-- Footer -->
    <?php include("vendor/inc/footer.php");?>
  <!--.Footer-->
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
