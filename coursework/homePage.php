<?php
session_start();
require "navBar.php";
?>
<title>
  MotoTrader.com
</title>
<!-- Main Body-->
<section style="background-image:url('pictures/homebackground/carbg.jpg');
  height:32rem;position:relative;background-position:center;background-size:cover;">
  <div style="background-color:rgba(0,0,0,0.5);height:32rem;position:relative;
    background-position:center;background-size:cover;">
    <div class="container-fluid align-items-center text-light pb-3" style="
      position:absolute;bottom:0;left:0;right:0;">
      <div class="row">
        <div class="col-2">

        </div>
        <div class="col-lg-6">

          <h1 class="text-start">Unmissable deals</h1>
          <p class="pt-1 fw-light fs-5">Every deal you won't want to miss,
            ranging<br> from affordable, to luxury cars, all here in one place
          </p>

        </div>
        <div class="col-4">

        </div>
      </div>



    </div>





  </div>
</section>
<section class="text-light text-center py-3" style="background-color:#00a0df">
  <div class="text-center fw-light fs-4 row">
    <div class="col-lg-3">

    </div>
    <div class="col-lg-6 text-center">
      Get into the holiday spirit and treat yourself this year! Make sure to do
      it soon while offers still last
    </div>
    <div class="col-lg-3">

    </div>

  </div>

</section>

<!-- Cards -->
<section class="text-dark text-center">
  <div class="py-3 text-center border rounded-top border-bottom-0 fs-4 fw-normal" style="background-color:white;">
    Recently added vehicles

  </div>
  <hr style="margin:0px;">
  <div class="container-fluid row mx-auto rounded-bottom  py-5" style="background-color:whitesmoke;">

    <!-- Four most recent cars-->
    <?php
    $sql = ('SELECT userID,pictureFilesName,`description`,make,model,miles,price,dealer,carIndex FROM cars WHERE purchased=0 ORDER BY carIndex DESC');
    $stmt = $pdo->query($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);


    for ($i = 0; $i < 4; $i++) {
      $row = $stmt->fetch();
      $make = $row['make'];
      $model = $row['model'];
      $price = $row['price'];
      $miles = $row['miles'];
      $dealer = $row['dealer'];
      $carIndex = $row['carIndex'];
      $description = $row['description'];
      $imgFileName = $row['pictureFilesName'];
      $userID = $row['userID'];


      echo "<div class='container-fluid col-lg-3 py-3 align-items-center d-flex'>";
      echo "<div class='card' style='width: 18rem;''>";
      if ($imgFileName == 'image_unavailable.jpg') {
        echo "<img src='pictures/image_unavailable.jpg' class='card-img-top' alt=''
        style='object-fit:cover;height:10rem;'>";
      } else {
        echo "<img src='pictures/$userID/$carIndex/$imgFileName' 
        class='card-img-top' style='object-fit:cover;height:10rem;' alt=''>";
      }

      echo "<div class='card-body'>";
      echo "<h5 class='card-title text-truncate text-start'style='color:purple'>$make $model</h5>";
      echo "<hr>";
      echo "<div class='card-text text-start'>";
      echo "<div class='fw-light'>Recently added</div>";
      echo "<div class='fw-bold fs-4 pt-2'>£$price</div>";
      echo "<div class='fw-normal fs-6'>" . limit_text('' . $miles . ' miles done<br>
                           ' . $description . '<br> Sold at/by:' . $dealer . '', 100) . "</div>";
      echo "</div>";
      echo "<div class='text-start pt-3'>";
      echo "</div>";
      echo "</div>";

      echo "<a style='color:white; text-decoration:none;background-color:orange' class='py-2 container-fluid border-0 text-light fw-normal fs-5'
         href=carDisplay.php?carIndex=" . $carIndex . "&prevSearch=>Read more</a>";
      echo "</div>";
      echo "</div>";
    }
    ?>


  </div>


</section>



</html>