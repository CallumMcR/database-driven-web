<?php
include('databaseTemplate.php');
include("functions.php");
$user_data = check_login($pdo);

?>
<!DOCTYPE html>
<html>


<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href='https://fonts.googleapis.com/css?family=Open Sans Condensed:300' rel='stylesheet'>
    <script>
        function getCarModel(value) {
            if (value == "") {
                document.getElementById("carModel-list").innerHTML = ""; // Change the text in the carModel-list id element to that of what is returned from the getModels php file
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("carModel-list").innerHTML = this.responseText;
                        console.log(this.responseText);
                    }
                };
                xmlhttp.open("GET", "getModels.php?carMake_id=" + value, true);
                xmlhttp.send();
            }
        }

        function getCookieName(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)')); // Returns the data held in the cookie of the name entered into the function
            if (match) {
                return match[2];
            } else {
                console.log("Error")
            }

        }
    </script>
    <?php
    function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }
    ?>

    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            height: 100%;
            width: 100%;
        }
    </style>

</head>

<body style="background-color: whitesmoke">

    <!-- Bar along top -->
    <div class="container-fluid bg-dark text-light">
        <div class="row text-center align-items-center d-flex py-1">

            <div class="col-4 fs-5">
                Quality service ensured
            </div>

            <div class="col-4 fs-5">
                Xmas Sale now on
            </div>

            <div class="col-4 fs-5">
                Call us at: 01790 470859
            </div>

        </div>
    </div>
    <!-- Navbar-->
    <nav class="navbar navbar-expand-sm navbar-dark justify-content-evenly
   border-bottom border border-0  shadow-sm row" style="background-color: white;">


        <!-- Left side NavBar -->
        <div class="col-lg-3 text-center">

            <!-- Logo part-->

            <a href="homePage.php" class="navbar-brand h-100 d-flex align-items-center justify-content-center">
                <img class="img-fluid" style="width: 50%;" src="NewMotoTraderLogoBlackOutline.svg">
            </a>

        </div>

        <!-- Search group-->
        <div class="col-lg-5">
            <div class="row input-group h-100">

                <div class="col-lg-9 p-0 align-items-center justify-content-center">
                    <form action="searchCars.php" method="get">
                        <input name="navBarSearch" class="form-control" type="search" placeholder="Search for any car you want..." aria-label="Search" aria-describedby="search-addon" />
                    </form>
                </div>

                <div class="col-lg-3 p-0 d-flex align-items-center justify-content-center">

                    <!-- Advanced filter-->
                    <button class="form-control" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilterNavBarContent" aria-controls="advancedFilterNavBarContent" aria-expanded="false" aria-label="Advanced Filter">
                        Advanced
                    </button>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <ul class="navbar-nav h-100 d-flex align-items-center justify-content-evenly">

                <lis class="nav-item h-100 d-flex align-items-center">


                    <a href="searchCars.php?navBarSearch=" id="BrowseCars" class="btn btn-large fs-5" name="browseAllCars" type="submit">
                        Browse Cars
                    </a>
                </lis>

                <lis class="nav-item h-100 d-flex align-items-center">

                    <a href="addCar.php" class="btn btn-large" name="userSellCar" type="submit">
                        <span class="bi bi-currency-pound fs-5" style="font-size:25px;
                      vertical-align: middle;"> Sell</span>
                    </a>
                </lis>

                <lis class="nav-item h-100 d-flex align-items-center text-dark">
                    <?php
                    if (!empty($user_data)) {
                        echo '<div class="btn-group">
                            <button style="font-size:25px;vertical-align:middle;" 
                            type="button" class="btn btn-sm bi bi-person-circle fs-5"
                             data-bs-toggle="dropdown" aria-expanded="false">
                              Account
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li><a class="dropdown-item" href="account.php">My Listings</a></li>
                              <li><a class="dropdown-item" href="myPurchases.php">My Purchases</a></li>
                              <li><a href="signout.php" class="dropdown-item">Sign out</a></li>
                            </ul>
                          </div>';
                    } else {
                        echo "<a class='btn btn-sm bi bi-person-circle fs-5'
                            style='font-size:25px;
                          vertical-align: middle;' href='login.php'> Sign in</a>";
                    }


                    ?>
                </lis>
            </ul>

        </div>



    </nav>

    <!-- Navbar end-->


    <!-- Dropdown part for advanced Filter-->
    <div class="collapse" id="advancedFilterNavBarContent" style="background-color:white;">
        <div class="text-dark text-center py-3 fs-3" style="margin-bottom: 0;">
            Your car, your way
        </div>
        <form method="get" action="searchCars.php">
            <div class="container-fluid p4">
                <div class="row align-items-center justify-content-evenly">

                    <div class="col-lg-4">

                        <div class="p-3 align-middle text-center rounded">

                            <div class="fw-light fs-4">Car Make</div>

                            <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" onChange="getCarModel(this.value);" name="make" id="carMake">
                                <option value=''>Car Make</option>

                                <?php
                                $sql = ('SELECT make,COUNT(make) as tally FROM cars WHERE purchased=0 GROUP BY make');
                                $stmt = $pdo->query($sql);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                $getCarMakesList = $pdo->prepare('SELECT make,COUNT(make) as tally FROM cars WHERE purchased=0 GROUP BY make');
                                $getCarMakesList->execute();
                                while ($row = $stmt->fetch()) {
                                    $carMake = $row['make'];
                                    $carTally = $row['tally'];
                                    echo "<option value='$carMake'>$carMake ($carTally)</option>";
                                }
                                ?>

                            </select>



                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-3 align-middle text-center rounded">

                            <div class="fw-light fs-4">Car Model</div>

                            <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="model" id="carModel-list">
                                <option value=''>Car Model</option>
                                <!-- getModel.php is used here as an ajax file -->


                            </select>
                        </div>
                    </div>



                    <div class="col-lg-4">
                        <div class="p-3 text-center rounded">

                            <div class="fw-light fs-4">Car Colour</div>

                            <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="colour" id="carColour-list">
                                <option value=''>Colour</option>


                                <?php
                                $sql = ('SELECT colour FROM cars GROUP BY colour');
                                $stmt = $pdo->query($sql);
                                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                $getCarMakesList = $pdo->prepare('SELECT colour FROM cars
                 GROUP BY colour');
                                $getCarMakesList->execute();
                                while ($row = $stmt->fetch()) {
                                    $carColour = $row['colour'];
                                    echo "<option value='$carColour'>$carColour</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-evenly">
                        <div class="col-lg-3">
                            <div class="p-3 text-center rounded">

                                <div class="fw-light fs-4">Minimum Price</div>

                                <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="minprice" id="mincarPrice-list">
                                    <option value=''>Minimum Price</option>
                                    <?php

                                    $getMaxPrice = $pdo->prepare("SELECT `price` as 'mostExpensive' FROM 
                            cars ORDER BY `price` DESC LIMIT 1");
                                    $getMaxPrice->execute();
                                    $maxPrice = $getMaxPrice->fetch(PDO::FETCH_ASSOC);
                                    $maxPriceToNear5k = ceil($maxPrice['mostExpensive'] / 5000) * 5000;
                                    for ($cost = 0; $cost <= $maxPriceToNear5k; $cost += 5000) {
                                        echo "<option value='$cost'>£ $cost</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="p-3 text-center rounded">

                                <div class="fw-light fs-4">Maximum Price</div>

                                <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="maxprice" id="maxcarPrice-list">
                                    <option value=''>Maximum Price</option>
                                    <?php

                                    $getMaxPrice = $pdo->prepare("SELECT `price` as 'mostExpensive' FROM 
                            cars ORDER BY `price` DESC LIMIT 1");
                                    $getMaxPrice->execute();
                                    $maxPrice = $getMaxPrice->fetch(PDO::FETCH_ASSOC);
                                    $maxPriceToNear5k = ceil($maxPrice['mostExpensive'] / 5000) * 5000;
                                    for ($cost = 5000; $cost <= $maxPriceToNear5k; $cost += 5000) {
                                        echo "<option value='$cost'>£ $cost</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="p-3 text-center rounded">

                                <div class="fw-light fs-4">Minimum Milage</div>

                                <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="minmiles" id="mincarMiles-list">
                                    <option value=''>Minimum Milage</option>
                                    <?php

                                    $getMaxMiles = $pdo->prepare("SELECT `miles` as 'mostMiles' FROM 
                            cars ORDER BY `miles` DESC LIMIT 1");
                                    $getMaxMiles->execute();
                                    $maxMiles = $getMaxMiles->fetch(PDO::FETCH_ASSOC);
                                    $maxMilesToNearest1k = ceil($maxMiles['mostMiles'] / 1000) * 1000;
                                    for ($miles = 0; $miles <= $maxMilesToNearest1k; $miles += 1000) {
                                        echo "<option value='$miles'>$miles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3">
                            <div class="p-3 text-center rounded">

                                <div class="fw-light fs-4">Maximum Milage</div>

                                <select class="form-select form-select-lg mb-3" aria-label="form-select-lg" name="maxmiles" id="maxcarMiles-list">
                                    <option value=''>Maximum Milage</option>
                                    <?php

                                    $getMaxMiles = $pdo->prepare("SELECT `miles` as 'mostMiles' FROM 
                            cars ORDER BY `miles` DESC LIMIT 1");
                                    $getMaxMiles->execute();
                                    $maxMiles = $getMaxMiles->fetch(PDO::FETCH_ASSOC);
                                    $maxMilesToNearest1k = ceil($maxMiles['mostMiles'] / 1000) * 1000;
                                    for ($miles = 0; $miles <= $maxMilesToNearest1k; $miles += 1000) {
                                        echo "<option value='$miles'>$miles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>




                    </div>
                </div>


            </div>
            <div class="container-fluid py-4">
                <div class="text-dark text-center rounded" style="background-color:white">
                    <button class='btn btn-lg btn-outline-primary rounded-pill' type="submit" value="Search Now!">
                        Search now!
                    </button>
                </div>
            </div>
        </form>
        <hr>
    </div>

    <!-- Advanced Filter End-->
    <!--Divider from white to coloured pic-->
    <section style="height:0.75em;">

    </section>
</body>