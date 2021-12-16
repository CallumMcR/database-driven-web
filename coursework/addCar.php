<?php
session_start();
require("navBar.php");
$user_data = check_login($pdo);
if (!isset($user_data)) {
  redirect('login.php');
}
$picInvalid = false;
$modelInvalid = false;
$makeInvalid = false;
$priceInvalid = false;
$milesInvalid = false;
$regInvalid = false;
$descInvalid = false;
if (isset($_POST['addCarDetails'])) { // Check if form on this page has been posted
  if (empty($_POST['carAddModel'])) {
    $modelInvalid = true;
  }
  if (empty($_POST['carAddMake'])) {
    $makeInvalid = true;
  }
  if (empty($_POST['carAddDescription'])) {
    $descInvalid = true;
  }
  if (!is_numeric($_POST['carAddMiles'])) {
    console_log($_POST['carAddMiles']);
    $milesInvalid = true;
  }
  if (empty($_POST['carAddReg'])) {
    $regInvalid = true;
  }
  if (empty($_POST['carAddPrice'])) {
    $priceInvalid = true;
  }
  if (
    !empty($_POST['carAddModel']) && !empty($_POST['carAddMake'])
    && is_numeric($_POST['carAddMiles']) &&
    !empty($_POST['carAddPrice']) && is_numeric($_POST['carAddPrice'])
  ) {
    $useStandardImage = false;
    $uploadOk = 1; 
    if (empty(basename($_FILES["fileToUpload"]["name"]))) { // If an image was not uploaded
      $fileName = "image_unavailable.jpg"; // Change the filename to the default image
      $useStandardImage = true;
    } else {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if ($check !== false) {
        // if the file is an image we can upload
        $uploadOk = 1;
      } else { // if the file is not an image
        $uploadOk = 0;
        $picInvalid = true;
      }
    }


    $model = $_POST['carAddModel'];
    $make = $_POST['carAddMake'];
    $description = $_POST['carAddDescription'];
    $miles = $_POST['carAddMiles'];
    $colour = $_POST['carAddColour'];
    $condition = $_POST['carAddCondition'];
    $price = $_POST['carAddPrice'];
    $reg = $_POST['carAddReg'];
    $dealer = $_POST['carAddDealer'];


    if ($uploadOk == 1) { // File is okay to upload

      $sql = "INSERT INTO cars(`userID`,`model`,`make`,`description`,`miles`,
      `colour`,`carCondition`,`price`,`Reg`,`dealer`)
      VALUES(" . $user_data['userID'] . ",'$model','$make',
      :descriptionToAdd,$miles,'$colour','$condition',$price,'$reg','$dealer')";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['descriptionToAdd' => $description]); 


      $getCarIndex = "SELECT carIndex FROM `cars` WHERE userID = " . $user_data['userID'] . " 
      ORDER BY `cars`.`carIndex` DESC LIMIT 1";
      $findCreatedCarIndex = $pdo->prepare($getCarIndex);
      $findCreatedCarIndex->execute();
      $carIndex = $findCreatedCarIndex->fetch(PDO::FETCH_ASSOC);
      // Now we can get the carIndex to change the image to it

      if (!is_dir("pictures/" . $user_data['userID'] . "/" . $carIndex['carIndex'] . "/") or $useStandardImage == false) {
        mkdir("pictures/" . $user_data['userID'] . "/" . $carIndex['carIndex'] . "/"); // If the directory does not exist, or have an image we need to store, create a directory
      }// The directory uses the userID, and the carIndex to create a unique location where the image can't be overwritten by any other uses images.

      if ($useStandardImage == false) { // If we are using a custom image uploaded by the user
        $target_dir = "pictures/" . $user_data['userID'] . "/" . $carIndex['carIndex'] . "/";

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { // If moving the uploaded image to the target directory was successful
          $fileName = basename($_FILES["fileToUpload"]["name"]);
          $newIndex = $carIndex['carIndex'];
          $addCarsFileName = ("UPDATE cars SET `pictureFilesName`='$fileName'
          WHERE `carIndex`='$newIndex'");
          $prepareFileName = $pdo->prepare($addCarsFileName);
          $prepareFileName->execute();
          redirect('account.php'); // Take the user back to their listings to show the upload was complete
        }
      } else { // If we are not using a custom image, just set the file name to that of our default image
        $newIndex = $carIndex['carIndex'];
        $addCarsFileName = ("UPDATE cars SET `pictureFilesName`='$fileName'
          WHERE `carIndex`='$newIndex'");
        $prepareFileName = $pdo->prepare($addCarsFileName);
        $prepareFileName->execute();
        redirect('account.php');
      }


    }
  }
}
?>
<title>
  Selling a car - MotoTrader.com
</title>
<div class="text-start fs-4 fw-normal px-3 pb-3">
  Create a listing
</div>
<section style="background-color:white">
  <div class="container-fluid">
    <div class="row container-fluid">
      <div class="col-4 fs-4 fw-bold py-2" style="color:midnightblue;">
        Listing Details

      </div>

      <hr>

    </div>
    <form method="post" action="addCar.php" enctype="multipart/form-data">
      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Title
        </div>
        <div class="col-3">
          <input placeholder="Car Make" style="border-color:black;" type="text" class="form-control" name="carAddMake">
          <?php
          if ($makeInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    Please enter a car make
                </div>";
          }
          ?>
        </div>
        <div class="col-3">
          <input placeholder="Car Model" style="border-color:black;" type="text" class="form-control" name="carAddModel">
          <?php
          if ($modelInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    Please enter a car model
                </div>";
          }
          ?>
        </div>
      </div>


      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Description
        </div>
        <div class="col-6">
          <textarea placeholder="Describe your vehicle here, and remember to be as descriptive as possible" style="border-color:black;" type="text" class="form-control" name='carAddDescription' rows=4></textarea>
          <?php
          if ($descInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    Please enter a brief description
                </div>";
          }
          ?>
        </div>
      </div>

      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Miles
        </div>
        <div class="col-6">
          <input placeholder="Miles" style="border-color:black;" type="text" class="form-control" name="carAddMiles">
          <?php
          if ($milesInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    Please enter your milage
                </div>";
          }
          ?>
        </div>
      </div>

      <div class="row container-fluid py-2 align-items-center">
        <div class="col-3 fs-5 fw-normal">
          Colour
        </div>
        <div class="col-6">

          <select style="border-color:black;" class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="carAddColour">
            <?php
            $thisColour = ['Black', 'Blue', 'Brown', 'Bronze', 'Gold', 'Green', 'Grey', 'Lemon', 'Orange', 'Pale Blue', 'Pink', 'Red', 'Silver', 'White', 'Yellow'];
            for ($i = 0; $i < 14; $i++) {
              echo "<option value='" . $thisColour[$i] . "'>" . $thisColour[$i] . "</option>";
            }
            ?>
          </select>


        </div>
      </div>

      <div class="row container-fluid py-2 align-items-center">
        <div class="col-3 fs-5 fw-normal">
          Condition
        </div>
        <div class="col-6">

          <select style="border-color:black;" class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="carAddCondition">
            <?php
            $thisCondition = ['New', 'Used', 'Damaged', 'Unfunctional'];
            for ($i = 0; $i < 4; $i++) {
              echo "<option value='" . $thisCondition[$i] . "'>" . $thisCondition[$i] . "</option>";
            }
            ?>
          </select>


        </div>
      </div>

      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Price
        </div>
        <div class="col-6">
          <div class="input-group">
            <span class="input-group-text">£</span>
            <input placeholder="Enter your price here" style="border-color:black;" type="text" class="form-control" name="carAddPrice">

          </div>
          <?php
          if ($priceInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    Please enter how much you want for your car
                </div>";
          }
          ?>



        </div>
      </div>


      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Registration Letter
        </div>
        <div class="col-6">
          <input placeholder="Please use the one letter used to mark the year of registration" style="border-color:black;" type="text" class="form-control" name="carAddReg"></input>
          <?php
          if ($regInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    Please enter a single Registration letter
                </div>";
          }
          ?>
        </div>
      </div>

      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Dealership
        </div>
        <div class="col-6">
          <input placeholder="Businesses Dealership selling the car" style="border-color:black;" type="text" class="form-control" name="carAddDealer"></input>
          <span class="fs-6 fw-light" style="color:red">
            <br>If you are not a business, do not fill in this field</span>
        </div>
      </div>

      <div class="row container-fluid py-2">
        <div class="col-3 fs-5 fw-normal">
          Upload a picture of your car
        </div>
        <div class="col-6">
          <input type="file" name="fileToUpload">
          <span class="fs-6 fw-light" style="color:red">
            <br>You are not required to upload an image, but we recommend you do</span>
          <?php
          if ($picInvalid) {
            echo "<div class='fs-6 fw-light' style='color:red'>
                    File size was too large, or your picture wasn't a JPEG or PNG
                </div>";
          }
          ?>
        </div>
      </div>



      <div class="row container-fluid py-5">
        <div class="col-3 fs-5 fw-normal">
        </div>
        <div class="col-6 text-center">
          <input name="addCarDetails" class="btn-lg btn btn-primary rounded-2" type="submit" value="Create listing">
        </div>
      </div>





    </form>
</section>


</html>