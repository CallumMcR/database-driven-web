<?php
session_start();
include "navBar.php";
$usernameInvalid=false;
$passwordInvalid = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") { // Get what was posted
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) { // Check the user entered something
        $query = $pdo->prepare("SELECT * FROM users WHERE username ='" . $username . "' OR emailAddress='" . $username . "'
         AND password='" . $password . "' LIMIT 1");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);



        if ($query->rowCount() > 0) { // If the query got any results
            $user_data = $result;

            if ($user_data['password'] == $password) { // If what the user entered matched the database
                $_SESSION['userID'] = $user_data['userID']; // Create a session that logs the user in
                redirect('homePage.php');// Redirect the user to the homepage
            }
        }
    }
    if (empty($username)) {
        $usernameInvalid = true;
    }
    if (empty($password)) {
        $passwordInvalid = true;
    }
}
?>
<title>
    Sign in - MotoTrader.com
</title>

<section style="background-color:white;overflow:hidden; height:80%;">
    <div class="row">
        <div class="col-3">

        </div>
        <div class="col-6 text-center">
            <div class="pt-5">
                <div class="fw-normal fs-3" style="color:midnightblue;">
                    Welcome back
                </div>
                <div class="fw-light fs-4">
                    Sign in below, or <a href='signup.php' style="color:blue;text-decoration:none;">register an account with us</a>
                </div>
            </div>
            <form class="pt-3" method="post">
                <div class="mb-3 px-5 py-3">
                    <input placeholder="Email or username" style="border-color:black;" class="form-control" name="username" aria-describedby="emailHelp">
                    <?php
                    if ($usernameInvalid) {
                        echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Please enter a recognised Email address/username
                </div>";
                    }
                    ?>
                </div>



                <div class="mb-3 px-5">
                    <input placeholder="Password" name="password" style="border-color:black;" type="password" class="form-control">
                    <div id="passlogin" class="form-text text-start">We will never ask you for your password</div>
                    <?php
                    if ($passwordInvalid) {
                        echo "<div class='fs-6 fw-light text-start' style='color:red'>
                    Incorrect password
                </div>";
                    }
                    ?>
                </div>


                <div class="py-5">
                    <input type="submit" value="Log in" class="btn-lg col-6 btn btn-primary rounded-pill text-center">
                    </input>

                </div>
            </form>


        </div>
        <div class="col-3">

        </div>
    </div>


</section>



</html>