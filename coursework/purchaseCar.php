<?php
session_start();
require "navBar.php";
$user_data = check_login($pdo);
if (!isset($user_data)) {
    redirect('login.php');
}
$missingData=false;
if (!isset($_GET['index'])) {
    redirect('homePage.php');
}
$carUniqueID = $_GET['index'];

if(isset($_POST['purchaseCarSubmit'])) // Checks that something has been posted to this page
{
    if(!empty($_POST['purchase_firstName']) && !empty($_POST['purchase_lastName'])
    && !empty($_POST['purchase_emailaddress']) && !empty($_POST['purchase_cardNumber'])
    && !empty($_POST['purchase_cardcvv']) && !empty($_POST['purchase_cardexpdate'])) // If anypart of the form is missing make them re-do the form
    {
        $missingData=true;
    }
    else
    {
        $disableCar = $pdo->prepare("UPDATE cars
        SET purchaserID = ".$user_data['userID'].",purchased=1 WHERE carIndex=?");
        $disableCar->execute([$carUniqueID]); // Make the car no longer purchasable
        redirect("purchaseComplete.php?index=".$carUniqueID);
    }
}
$stmt = $pdo->prepare('SELECT * FROM cars WHERE carIndex = ?');
$stmt->execute([$carUniqueID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$userID = $row['userID'];
$imgFileName = $row['pictureFilesName'];
$carIndex = $row['carIndex'];
?>
<title>
        <?php echo "Purchasing ".$row['make'] . " " . $row['model']; ?> - MotoTrader.com
    </title>

<section style="background-color:white;">
    <div class="row" style="margin:0px">

        <div class="col-7 text-center px-5">

            <div class="fw-bold fs-2" style="color:midnightblue;">
                Enter your details
            </div>
            <hr>
            <?php 
            if($missingData)
            {
                echo "<div class='fs-4' style='color:red'>
                Please ensure you fill the form correctly</div>";
            }
            ?>

            <form class="pt-3" action=<?php echo"purchaseCar.php?index=".$carUniqueID?> 
            method="post">
                <div class="row">
                    <div class="col-6">
                        <input placeholder="First Name" style="border-color:black;" class="form-control" name="purchase_firstName" value="<?php echo $user_data['firstName'] ?>" type="text">

                    </div>
                    <div class="col-6">
                        <input placeholder="Last Name" style="border-color:black;" type="text" class="form-control" name="purchase_lastName" value="<?php echo $user_data['lastName'] ?>">

                    </div>
                </div>
                <div class="pt-3">
                    <input placeholder="Email address" style="border-color:black;" type="text" class="form-control" name="purchase_emailAddress" value="<?php echo $user_data['emailAddress'] ?>">
                </div>

                <div class="pt-3">
                    <input placeholder="Card Number" style="border-color:black;" type="number" class="form-control" name="purchase_cardNumber">
                </div>
                <div class="row pt-3">
                    <div class="col-6">
                        <input placeholder="Card Expiration Date" style="border-color:black;" type="number" class="form-control" name="purchase_cardexpdate">
                    </div>
                    <div class="col-6">
                        <input placeholder="Card CVV number" style="border-color:black;" type="number" class="form-control" name="purchase_cardcvv">
                    </div>
                </div>


                <div class="py-5">
                    <input name="purchaseCarSubmit" 
                    type="submit" value="Confirm purchase" 
                    class="btn-lg col-6 btn btn-primary rounded-pill text-center"
                    >

                    </input>

                </div>
            </form>
        </div>
        <div class="col-5 text-center border-0 border-start">
            <div class="fw-bold fs-2" style="color:midnightblue;">
                Purchasing
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
                        if(!empty($row['town']))
                        {
                            echo "Location:".$row['town'];
                        }
                        else 
                        {
                            echo "Location: ".$user_data['location'];
                        }
                        
                        ?>
                    </div>
                </div>
                    <hr>
                <div class="text-start fs-4 pb-5">
                    Description:
                    <div class="text-center border rounded mt-3">
                        <?php echo $row['description'] ?>
                    </div>
                </div>

            </div>
        </div>
    </div>


</section>



</html>