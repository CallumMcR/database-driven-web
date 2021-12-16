<?php
session_start();
include("navBar.php");
$mismatchpassword = false;
$firstNameInvalid = false;
$lastNameInvalid = false;
$usernameInvalid = false;
$emailAddressInvalid = false;
$addressInvalid = false;
$locationInvalid = false;
$postcodeInvalid = false;
$phoneNumberInvalid = false;
if (isset($_POST['registerAccount'])) { // Check if form on this page has been posted
    $username = $_POST['username'];
    $password = $_POST['password'];
    $emailAddress = $_POST['emailAddress'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $location = $_POST['location'];
    $passwordduplicate = $_POST['passwordduplicate'];
    $phoneNumber = $_POST['phoneNumber'];

    $checkemailIsUnique = ("SELECT COUNT(emailAddress) as 'numOfEmails' from users WHERE
    emailAddress = '$emailAddress' GROUP BY emailAddress");
    $email = $pdo->prepare($checkemailIsUnique);
    $email->execute();
    $emailUnique = $email->fetch(PDO::FETCH_ASSOC);



    if (
        $emailUnique['numOfEmails'] == 0 && !empty($username) && !empty($password) && !is_numeric($username)
        && !empty($emailAddress) && !empty($firstName) && !empty($lastName) &&
        !empty($postcode) && !empty($location) && !empty($address) &&
        $password == $passwordduplicate && !empty($phoneNumber) // Ensures all data that is needed is entered
    ) {
        // Save to db
        $sql = ("INSERT INTO users (username,`password`,emailAddress,firstName,lastName
        ,`location`,`address`,postcode,phoneNumber) 
        VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username, $password, $emailAddress, $firstName, $lastName,
            $location, $address, $postcode, $phoneNumber
        ]);

        $getUserID = ("SELECT userID FROM users WHERE username='$username' AND 
        password = '$password'");
        $val = $pdo->prepare($getUserID);
        $val->execute();
        $ID = $val->fetch(PDO::FETCH_ASSOC);


        mkdir("pictures/" . $ID['userID'] . "/", 0777, true);

        redirect('login.php');
        die;
    }
    if ($password != $passwordduplicate or empty($password) or empty($passwordduplicate)) {
        $mismatchpassword = true;
    }
    if (empty($username)) {
        $usernameInvalid = true;
    }
    if (empty($firstName)) {
        $firstNameInvalid = true;
    }
    if (empty($lastName)) {
        $lastNameInvalid = true;
    }
    if (empty($emailAddress)) {
        $emailAddressInvalid = true;
    }
    if (empty($location)) {
        $locationInvalid = true;
    }
    if (empty($postcode)) {
        $postcodeInvalid = true;
    }
    if (empty($address)) {
        $addressInvalid = true;
    }
    if (empty($phoneNumber)) {
        $phoneNumberInvalid = true;
    }
}
?>
<title>
    Register - MotoTrader.com
</title>

<section style="background-color:white;">
    <div class="row">
        <div class="col-3">

        </div>
        <div class="col-6 text-center">
            <div class="pt-5">
                <div class="fw-bold fs-2" style="color:midnightblue;">
                    Create an account
                </div>
                <hr>

            </div>
            <form class="pt-3" method="post">
                <div class="row">
                    <div class="col-6">
                        <input placeholder="First Name" style="border-color:black;" class="form-control" name="firstName" type="text">

                    </div>
                    <div class="col-6">
                        <input placeholder="Last Name" style="border-color:black;" type="text" class="form-control" name="lastName">
                    </div>
                </div>
                <?php
                if ($firstNameInvalid or $lastNameInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter your first, and last name
                </div>";
                }
                ?>
                <div class="pt-3">
                    <input placeholder="Email address" style="border-color:black;" type="text" class="form-control" name="emailAddress">
                </div>
                <?php
                if ($emailAddressInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter your email address
                </div>";
                }
                ?>
                <div class="pt-3">
                    <input placeholder="Address" style="border-color:black;" type="text" class="form-control" name="address">
                </div>
                <?php
                if ($addressInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter address
                </div>";
                }
                ?>
                <div class="pt-3">
                    <input placeholder="Town/City" style="border-color:black;" type="text" class="form-control" name="location">
                </div>
                <?php
                if ($locationInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter your town/city of residence
                </div>";
                }
                ?>
                <div class="pt-3">
                    <input placeholder="Postcode" style="border-color:black;" type="text" class="form-control" name="postcode">
                </div>
                <?php
                if ($postcodeInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter your postcode
                </div>";
                }
                ?>

                <div class="pt-3">
                    <input placeholder="Phone Number" style="border-color:black;" type="text" class="form-control" name="phoneNumber">
                </div>
                <?php
                if ($phoneNumberInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter your phone Number
                </div>";
                }
                ?>




                <div class="pt-3">
                    <input placeholder="Username" style="border-color:black;" type="text" class="form-control" name="username">
                </div>
                <?php
                if ($usernameInvalid) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter a username
                </div>";
                }
                ?>


                <div class="pt-3">
                    <input placeholder="Password" style="border-color:black;" type="password" class="form-control" name="password">
                </div>

                <div class="pt-3">
                    <input placeholder="Repeat Password" style="border-color:black;" type="password" class="form-control" name="passwordduplicate">
                </div>
                <?php
                if ($mismatchpassword) {
                    echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Mismatching/Empty password fields
                </div>";
                }
                ?>



                <div class="py-5">
                    <input name="registerAccount" type="submit" value="Register now" class="btn-lg col-6 btn btn-primary rounded-pill text-center">

                    </input>

                </div>
            </form>
        </div>
        <div class="col-3">

        </div>
    </div>


</section>



</html>