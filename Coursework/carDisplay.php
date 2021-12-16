<?php
session_start();
require "navBar.php";
$carIndex = $_GET['carIndex'];
$stmt = $pdo->prepare('SELECT * FROM cars
INNER JOIN users
ON cars.userID=users.userID
 WHERE carIndex = ?');
$stmt->execute([$carIndex]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['purchased'] == 1 && $user_data['userID'] != $row['purchaserID']) { // If the car the user is trying to view is already purchased, and the user is not the owner or purchaser
    redirect('homePage.php'); // Redirect them to the homepage
}
$userID = $row['userID'];
$imgFileName = $row['pictureFilesName'];
$carIndex = $row['carIndex'];
?>
<title>
    <?php echo $row['make'] . " " . $row['model']; ?> - MotoTrader.com
</title>
<section class="pb-3" style="background-color:white;">
    <div class="row">
        <div class="col-1">

        </div>
        <div class="col-10" style="background-color:white;">
            <button class="fw-normal fs-5 border-0 btn py-3">
                <?php
                if ($row['purchased'] == 0) {
                    echo "<a style='text-decoration:none;color:purple;' 
                onclick='history.go(-1);' >
                <- Back to results</a>";
                } else {
                    echo "<a style='text-decoration:none;color:purple;' 
                onclick='history.go(-1);' >
                <- Back to purchases</a>";
                }
                ?>
            </button>
            <div class="row container-fluid pb-4">
                <div class="col-lg-7 shadow" style="background-color:whitesmoke;">
                    <?php
                    if ($imgFileName == 'image_unavailable.jpg') {
                        echo '<img src="pictures/image_unavailable.jpg" class="img-fluid py-2" alt="" style="width:100%;display:block;
                        max-height:564px;">';
                    } else {
                        echo "<img src='pictures/$userID/$carIndex/$imgFileName' class='img-fluid py-2' alt='' style='width:100%;display:block;
                        max-height:564px;'>";
                    }
                    ?>

                </div>
                <div class="col-lg-5">
                    <div class="container-fluid fs-4 fw-bolder" style="color:midnightblue">
                        <?php echo "" . $row['make'] . " " . $row['model']; ?>
                    </div>
                    <hr>
                    <div class="container-fluid fs-5 fw-normal pb-2"> Condition: <?php echo $row['carCondition'] ?></div>
                    <div class="container-fluid fs-5 fw-normal">
                        Miles: <?php echo " " . $row['miles'] . ""; ?>
                    </div>
                    <hr>
                    <div class="container-fluid fs-4 pt-2" style="color:orange;">
                        Price: <span class="fw-bolder"> £<?php echo "" . $row['price']; ?> </span>
                    </div>
                    <hr>
                    <div class="container-fluid text-center d-grid">
                        <?php
                        if (($row['purchased'] == 0) AND ($row['userID'] != $user_data['userID'])) 
                        {
                        ?>
                            <a class="btn btn-primary rounded-2 btn-lg" <?php echo "href = purchaseCar.php?index=" . $row['carIndex']; ?>>
                                Buy it now
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row container-fluid">
                <div class="col-lg-7">
                    <div class="fs-3 fw-normal" style="color:midnightblue;">
                        Overview
                    </div>
                    <div class="fs-5 py-3 fw-light row">
                        <div class="col-lg-4">
                            Make: <?php echo $row['make']; ?>
                        </div>
                        <div class="col-lg-4">
                            Model: <?php echo $row['model']; ?>
                        </div>
                        <div class="col-lg-4">
                            Colour: <?php echo $row['colour']; ?>
                        </div>
                    </div>
                    <div class="py-3 fs-5 fw-light row">
                        <div class="col-lg-4">
                            Miles: <?php echo $row['miles']; ?>
                        </div>
                        <div class="col-lg-4">
                            Reg: <?php echo $row['Reg']; ?>
                        </div>

                    </div>
                </div>
                <div class="col-lg-5 border rounded">
                    <div class="fs-4 fw-normal pt-2" style="color:midnightblue;">
                        Seller Information:
                    </div>
                    <div class="fs-5 pt-3 fw-normal" style="color:blue;">
                        <?php
                        if (!empty($row['dealer'])) {
                            echo $row['dealer'];
                        } else {
                            echo $row['username'];
                        }


                        ?>
                    </div>
                    <hr>
                    <div class="fs-5 fw-normal">
                        Enquire at: <?php echo $row['phoneNumber']; ?>
                    </div>
                    <div class="fs-5 pt-3 fw-normal pb-3">
                        Collection: <?php echo $row['location']; ?>
                    </div>
                </div>
                <div class="fs-4 fw-normal py-2" style="color:midnightblue">
                    Description
                </div>
                <div class="px-3 fs-5">
                    <?php echo $row['description'] ?>
                </div>
            </div>

        </div>
        <div class="col-1">

        </div>
    </div>


</section>

</html>