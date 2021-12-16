<?php
session_start();
require "navBar.php";
$carIndex = $_GET['index'];
$stmt = $pdo->prepare('SELECT * FROM cars WHERE carIndex = ?');
$stmt->execute([$carIndex]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$user_data = check_login($pdo);
if (checkIfAdminOrOwnsCar($user_data, $row) == false) {
    redirect('login.php');
}

if (
    isset($_POST['changeCarDetails']) &&
    checkIfAdminOrOwnsCar($user_data, $row) == true
) {
    $useStandardImage = false;
    $uploadOk = 1;
    if (empty(basename($_FILES["changeCarPic"]["name"]))) {
        $fileName = "image_unavailable.jpg";
        $useStandardImage = true;
    } else {
        $allowsTypes = array(IMAGETYPE_PNG,IMAGETYPE_JPEG);
        $detectedType=exif_imagetype($_FILES["changeCarPic"]["tmp_name"]);
        if (in_array($detectedType,$allowsTypes)) {
            // if file is image say we can upload
            $uploadOk = 1;
        } else { // if not an image we cannot upload
            $uploadOk = 0;
        }
    }


    $model = $_POST['carChangeModel'];
    $make = $_POST['carChangeMake'];
    $description = $_POST['carChangeDescription'];
    $miles = $_POST['carChangeMiles'];
    $colour = $_POST['carChangeColour'];
    $condition = $_POST['carChangeCondition'];
    $price = $_POST['carChangePrice'];
    $Reg=$_POST['carChangeReg'];


    $sql = "UPDATE cars 
        SET model=?,make=?,`description`=?,`miles`=?,colour=?,carCondition=?,price=?,Reg=?
        WHERE carIndex=? AND userID=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
            $model,
            $make,
            $description,
            $miles,
            $colour,
            $condition,
            $price,
            $Reg,
            $carIndex,
            $user_data['userID']
        )
    );

    // If we dont have a directory, we need to create it
    if (is_dir("pictures/" . $user_data['userID'] . "/" . $carIndex . "/") == false && $useStandardImage == false) {
        mkdir("pictures/" . $user_data['userID'] . "/" . $carIndex . "/");
    }


    // if uploaded file is a picture and we want to upload it
    if ($useStandardImage == false && $uploadOk == 1) {
        $target_dir = "pictures/" . $user_data['userID'] . "/" . $carIndex . "/";

        $target_file = $target_dir . basename($_FILES["changeCarPic"]["name"]);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["changeCarPic"]["tmp_name"], $target_file)) {
            $fileName = basename($_FILES["changeCarPic"]["name"]);
            $addCarsFileName = ("UPDATE cars SET `pictureFilesName`='$fileName'
            WHERE `carIndex`='$carIndex'");
            $prepareFileName = $pdo->prepare($addCarsFileName);
            $prepareFileName->execute();
            redirect('account.php');
        }
    }
    redirect('account.php');
}


$userID = $row['userID'];
$imgFileName = $row['pictureFilesName'];
$carIndex = $row['carIndex'];
?>

<title>
    Editting <?php echo $row['make'] . " " . $row['model']; ?> - MotoTrader.com
</title>
<section class="pb-3" style="background-color:white;">
    <form method="post" action=<?php echo "editCarDetails.php?index=" . $carIndex ?> enctype="multipart/form-data">
        <div class="row">
            <div class="col-1">

            </div>
            <div class="col-10" style="background-color:white;">
                <div class="py-4"></div>
                <div class="row container-fluid pb-4">
                    <div class="col-lg-7 shadow text-center d-flex" style="background-color:whitesmoke;">

                        <div id="frame" class="container-fluid py-2 text-center" style="background-position:center;background-image:url(
                        <?php
                        if ($imgFileName == 'image_unavailable.jpg') {
                            echo 'pictures/image_unavailable.jpg';
                        } else {
                            echo "'pictures/$userID/$carIndex/".$imgFileName."'";
                        }
                        ?>
                    );
                    position:relative;background-size:cover;">
                            <label id="changeCarPicLabel" for="changeCarPic" class="btn btn-primary btn-lg text-start" style="position:absolute;top:50%;
                        -ms-transform: translateY(-50%);
                        transform: translateY(-50%);color:white;opacity:0.8;">
                                <span class="bi bi-upload" style="color:white;"></span>
                                <input name="changeCarPic" id="changeCarPic" type="file" style="color:white;display:none !important;">
                            </label>
                        </div>
                        <script type="text/javascript"> // Taken from (javascript - How to set background image of element to user uploaded image, 2021), please see references
                            const frame = document.getElementById('frame');
                            const file = document.getElementById('changeCarPic');
                            const reader = new FileReader();
                            reader.addEventListener("load", function() {
                                frame.style.backgroundImage = `url(${ reader.result })`;
                            }, false);
                            file.addEventListener('change', function() {
                                const image = this.files[0];
                                if (image) reader.readAsDataURL(image);
                            }, false)
                        </script>

                    </div>
                    <div class="col-lg-5">
                        <div class="container-fluid fs-4 fw-bolder" style="color:midnightblue">
                            <?php echo "" . $row['make'] . " " . $row['model']; ?>
                        </div>
                        <hr>





                        <div class="container-fluid fs-5 fw-normal pb-2">
                            Condition:
                        </div>

                        <select style="border-color:black;width:80%" class="pb-2form-select form-select-lg mb-3" aria-label="form-select-lg" name="carChangeCondition">
                            <?php
                            echo "<option value='" . $row['carCondition'] . "'>" . $row['carCondition'] . "</option>";
                            $thisCondition = ['New', 'Used', 'Damaged', 'Unfunctional'];

                            $key1 = array_search($row['carCondition'], $thisCondition);
                            unset($thisCondition[$key1]);
                            foreach ($thisCondition as $cond) {
                                echo "<option value='" . $cond . "'>" . $cond . "</option>";
                            }
                            ?>
                        </select>






                        <div class="container-fluid fs-5 fw-normal pb-2">
                            Miles
                        </div>
                        <input placeholder="Miles" style="border-color:black" type="number" class="form-control container-fluid" name="carChangeMiles" value="<?php echo $row['miles'] ?>">

                        <hr>
                        <div class="container-fluid fs-4 pt-2" style="color:orange;">
                            Price:
                        </div>
                        <input placeholder="Price" style="border-color:black" type="number" class="form-control container-fluid" name="carChangePrice" value="<?php echo $row['price'] ?>">
                        <hr>

                    </div>
                </div>
                <div class="row container-fluid">
                    <div class="col-lg-7">
                        <div class="fs-3 fw-normal" style="color:midnightblue;">
                            Overview
                        </div>
                        <div class="fs-5 py-3 fw-light row d-flex">
                            <div class="col-lg-4">
                                Make: <br>
                                <input placeholder="Car Make" style="border-color:black;" type="text" class="form-control" name="carChangeMake" value="<?php echo $row['make'] ?>">
                            </div>
                            <div class="col-lg-4">
                                Model: <br><input value="<?php echo $row['model'] ?>" placeholder="Car Model" style="border-color:black;" type="text" class="form-control" name="carChangeModel">
                            </div>



                            <div class="col-lg-4">
                                Colour:<br>
                                <select style="border-color:black;" class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="carChangeColour">
                                    <?php
                                    echo "<option value='" . $row['colour'] . "'>" . $row['colour'] . "</option>";
                                    $thisColour = ['Black', 'Blue', 'Brown', 'Bronze', 'Gold', 'Green', 'Grey', 'Lemon', 'Orange', 'Pale Blue', 'Pink', 'Red', 'Silver', 'White', 'Yellow'];
                                    $key = array_search($row['colour'], $thisColour);
                                    unset($thisColour[$key]);
                                    foreach ($thisColour as $colour) {
                                        echo "<option value='" . $colour . "'>" . $colour . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="fs-5 py-3 fw-light row d-flex">
                            <div class="col-lg-4">
                                Registration: <br>
                                <input placeholder="Registration" style="border-color:black;" type="text" class="form-control" name="carChangeReg" value="<?php echo $row['Reg'] ?>">
                            </div>


                        </div>

                        <div class="fs-5 fw-normal pb-2">
                            Description
                        </div>
                        <div class="">
                            <textarea placeholder="Describe your vehicle here, and remember to be as descriptive as possible" style="border-color:black;" type="text" class="form-control" name='carChangeDescription' rows=4><?php echo $row['description'] ?>
                            </textarea>
                        </div>

                    </div>
                    <div class="col-lg-5 border rounded">
                        <div class="fs-4 fw-normal pt-2" style="color:midnightblue;">
                            Seller Information:
                        </div>
                        <div class="fs-5 pt-3 fw-normal" style="color:blue;">
                            <?php echo $row['dealer']; ?>
                        </div>
                        <hr>
                        <div class="fs-5 fw-normal">
                            Enquire at: <?php echo $row['telephone']; ?>
                        </div>
                        <div class="fs-5 pt-3 fw-normal pb-3">
                            Collection: <?php echo $row['town']; ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-1">

            </div>

            <div class="container-fluid py-4 row">
                <div class="col-1">

                </div>
                <div class="col-10">
                    <div class="col-7 d-flex justify-content-between">
                        <a class="btn btn-danger rounded-pill btn-lg" style="text-decoration:none;" href="account.php">
                            Dismiss changes
                        </a>
                        <input class="btn btn-primary rounded-pill btn-lg" type="submit" value="Save changes" name="changeCarDetails">

                    </div>

                </div>
                <div class="col-1">

                </div>

            </div>
        </div>

    </form>
</section>





</html>