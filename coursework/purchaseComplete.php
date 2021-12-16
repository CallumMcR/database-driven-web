<?php
session_start();
require "navBar.php";
$user_data = check_login($pdo);
if (!isset($user_data)) {
    redirect('login.php'); // Prevents guests from accessing this page
}
$missingData = false;
if (!isset($_GET['index'])) {
    redirect('homePage.php');
}
$carUniqueID = $_GET['index'];


$stmt = $pdo->prepare('SELECT * FROM cars WHERE carIndex = ?');
$stmt->execute([$carUniqueID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$userID = $row['userID'];
$imgFileName = $row['pictureFilesName'];
$carIndex = $row['carIndex'];
?>
<title>
    <?php echo "Purchasing " . $row['make'] . " " . $row['model']; ?> - MotoTrader.com
</title>

<section style="background-color:white;">
    <div class="row" style="margin:0px">

        <div class="col-7 text-center px-5">

            <div class="fw-bold fs-2" style="color:midnightblue;">
                What next?
            </div>
            <hr>
            <div class="container-fluid">
                <div class="text-start pt-2 fw-bold fs-4">
                    Step 1 - Organise a time
                </div>
                <div class="fs-5 fw-normal pt-2 text-start">
                    Organise a time to meet at and arrange the purchase. The next
                    available time for the seller is on the
                    <?php echo date('d-m-Y', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y'))) ?>
                </div>

                <div class="text-start pt-4 fw-bold fs-4">
                    Step 2 - Documentation
                </div>
                <div class="fs-5 fw-normal pt-2 text-start">
                    Please bring the following documents with you:<br>
                    Driving License<br>
                    Payment Documents

                </div>

                <div class="text-start pt-4 fw-bold fs-4">
                    Step 3 - Seller documentation
                </div>
                <div class="fs-5 fw-normal pt-2 text-start">
                    If you are buying from a general user, ensure you get the following:<br>
                    Logbook<br>
                    Seller's Details<br>
                    MOT Status<br>
                    Service History<br>
                    Proof of Purchase

                </div>

            </div>


        </div>
        <div class="col-5 text-center border-0 border-start">
            <div class="fw-bold fs-2" style="color:midnightblue;">
                Purchased!
            </div>
            <hr>
            <div class="px-3 text-center">
                <div class="container-fluid px-5 text-center">
                    <?php
                    if ($imgFileName == 'image_unavailable.jpg') {
                        echo '<img src="pictures/image_unavailable.jpg" class="img-fluid py-2 text-center" alt="">';
                    } else {
                        echo "<img src='pictures/$userID/$carIndex/$imgFileName' class='img-fluid py-2' alt=''>";
                    }
                    ?>
                </div>


            </div>
            <div class="fs-3 fw-normal" style="color:midnightblue">
                <?php echo $row['make'] . " " . $row['model']; ?>
            </div>
            <hr>
            <div class="text-center">
                <div class="row">
                    <div class="col-6 text-center fs-4 fw-bold">
                        £<?php echo $row['price'] ?>
                    </div>
                    <div class="col-6 text-center fs-4">
                        Condition: <?php echo $row['carCondition'] ?>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-6 text-center fs-4">
                        Colour: <?php echo $row['colour'] ?>
                    </div>
                    <div class="col-6 text-center fs-4">
                        Registration: <?php echo $row['Reg'] ?>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-6 text-center fs-4">
                        Miles: <?php echo $row['miles'] ?>
                    </div>
                    <div class="col-6 text-center fs-4">
                        <?php
                        if (!empty($row['town'])) {
                            echo "Location:" . $row['town'];
                        } else {
                            echo "Location: " . $user_data['location'];
                        }

                        ?>
                    </div>
                </div>
                <hr>
                <div class="text-start fs-4 pb-5">
                    Description:
                    <div class="text-start border rounded mt-3 px-3">
                        <?php echo $row['description'] ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="border-0 border-top text-center fs-2 fw-bold py-5" 
    style="background-color:#00a0df;color:white;">
            Our recommended Car Insurance providers
    </div>
    <div class="row py-3 d-flex align-items-center" 
    style="margin:0px;background-color:whitesmoke;">
        <div class="col-lg-4 d-flex text-center">
            <a href="https://www.moneysupermarket.com/car-insurance/"
            class="mx-auto d-block">
                <img
                style="max-height:150px;object-fit:contain;
                background-color:whitesmoke;"
                 class="mx-auto d-block img-thumbnail border-0" 
                 src="pictures/insurance/moneysupermarketlogo.svg" 
                >

                </img>
            </a>
        </div>

        <div class="col-lg-4 d-flex text-center">
            <a href="https://www.comparethemarket.com/car-insurance/"
            class="mx-auto d-block">
                <img
                style="max-height:150px;object-fit:contain;
                background-color:whitesmoke;"
                 class="mx-auto d-block img-thumbnail border-0" 
                 src="pictures/insurance/comparethemarketlogo.svg" 
                >

                </img>
            </a>
        </div>

        <div class="col-lg-4 d-flex text-center">
            <a href="https://www.churchill.com/"
            class="mx-auto d-block">
                <img
                style="max-height:150px;object-fit:contain;
                background-color:whitesmoke;"
                 class="mx-auto d-block img-thumbnail border-0" 
                 src="pictures/insurance/churchilllogo.png" 
                >

                </img>
            </a>
        </div>
    </div>


</section>



</html>