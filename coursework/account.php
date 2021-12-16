<?php

session_start();
require("navBar.php");
$currentCarIndex = null;
$user_data = check_login($pdo);
if (!isset($user_data)) { // if the user is not logged in, redirect them to the login page
    redirect('login.php');
}
$numberOfRecords = 8; // Constant for the number of records on a page

if (!isset($_GET['page'])) {
    $pageNumber = 1;
    $offset = 0;
} else {
    $pageNumber = $_GET['page'];
    $offset = ($pageNumber - 1) * $numberOfRecords;
}

$stmt = $pdo->prepare("SELECT * FROM cars
INNER JOIN users on cars.userID = users.userID 
WHERE purchased=0 AND cars.userID=" . $user_data['userID'] . " LIMIT 
" . $offset . ", " . $numberOfRecords);
$stmt->execute();




$totalPrep = $pdo->prepare("SELECT COUNT('make') as 'totalResults' 
FROM cars WHERE
cars.userID=" . $user_data['userID'] . " AND purchased=0 GROUP BY userID");
$totalPrep->execute();
$prep = $totalPrep->fetch(PDO::FETCH_ASSOC);
$count = $prep['totalResults'];


?>
<script>
    function saveCarIndexForDeletion(value) {
        document.cookie = `carIndexForDeletion=${value}`; // Used to save carIndex the user selects
    }

    function editCarReDirect(value) {
        window.location.href = "editCarDetails.php?index=" + value;
    }


    function deleteSelectedCar() {
        var xmlhttp = new XMLHttpRequest();
        window.location.href = "deleteCar.php?index=" + getCookieName(`carIndexForDeletion`);
    }
</script>


<title>
    Account Overview - MotoTrader.com
</title>
<section style="background:white;height:100%;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 py-3" style="background-color:white">
                <div class="container-fluid border rounded">
                    <div class="pt-2 fs-5 row align-items-center">
                        <div class="col-6">
                            Your listings:
                            <?php
                            if (empty($prep['totalResults'])) {
                                echo "0";
                            } else {
                                echo $count;
                            }
                            ?>
                        </div>
                        <div class="col-6 text-end">
                            <a href="addCar.php" class="btn btn-primary rounded-pill">
                                Add car
                            </a>
                        </div>
                    </div>

                    <hr>

                    <?php
                    while ($row = $stmt->fetch()) {
                        $userID = $row['userID'];
                        $imgFileName = $row['pictureFilesName'];
                        $carIndex = $row['carIndex'];
                    ?>
                        <!-- item layout -->
                        <div class="row pb-2">
                            <div class="col-4 border py-2 shadow" style="background-color:whitesmoke;">
                                <!-- thumbnail-->
                                <?php
                                if ($imgFileName == 'image_unavailable.jpg') {
                                    echo "<img src='pictures/image_unavailable.jpg' class='img_thumbnail' alt='' style='width:100%;height:auto;display:block;'>";
                                } else {
                                    echo "<img src='pictures/$userID/$carIndex/$imgFileName' class='img_thumbnail' alt='' style='width:100%;height:auto;display:block;'>";
                                }
                                ?>
                            </div>
                            <div class="col-lg-7 d-table text-start pt-2">
                                <div class="d-table-row container-fluid fs-5 fw-bold" style="color:#ff8c00;">

                                    <div class="text-start">
                                        <?php
                                        echo "<a href=carDisplay.php?carIndex=" . $row['carIndex'] .
                                            " >" . $row['make'] . " " . $row['model'] . "</a>";


                                        ?>
                                    </div>
                                </div>
                                <div class="d-table-row container-fluid fs-5">Miles: <?php echo $row['miles']; ?></div>
                                <div class="d-table-row container-fluid fs-5">Condition: <?php echo $row['carCondition']; ?></div>
                                <div class="d-table-row container-fluid fs-3 fw-bold" style="color:#ff8c00;">Price: £<?php echo $row['price']; ?></div>
                            </div>
                            <div class="col-lg-1 position-relative">
                                <button onclick="saveCarIndexForDeletion(<?php echo $row['carIndex'] ?>)" class="btn btn-danger bi bi-trash border-0 
                            position-absolute top-0 end-0" data-bs-target="#deleteCar" data-bs-toggle="modal">

                                </button>
                                <button class="btn btn-primary border-0 position-absolute 
                            bottom-0 end-0" onclick="editCarReDirect(<?php echo $row['carIndex'] ?>)">
                                    Edit
                                </button>
                            </div>
                        </div>
                        <hr>
                    <?php
                    }
                    $next = $pageNumber + 1;
                    $previous = $pageNumber - 1;
                    $first = 1;
                    ?>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                                <?php
                                echo "<a class='page-link' href='account.php?page=1'>"
                                ?>
                                <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php if ($pageNumber > 1) {
                                echo '<li class="page-item">';
                                echo "<a class='page-link' href='account.php?page=$previous'>Previous</a>";
                                echo '</a>';
                                echo '</li>';
                            } else {
                                echo '<li class="page-item disabled">';
                                echo '<a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>';
                                echo '</li>';
                            }


                            $next2 = $pageNumber + 2;
                            echo "<li class='page-item active'><a class='page-link' href='account.php?page=$pageNumber'>" . $pageNumber . "</a></li>";


                            if (((($next - 1) * $numberOfRecords) + 1 > $count)) {
                                echo "<li class='page-item disabled'>";
                            } else {
                                echo "<li class='page-item'>";
                            }

                            echo "<a class='page-link' href='account.php?page=$next'>" . $next . "</a></li>";


                            if (((($next2 - 1) * $numberOfRecords) + 1 > $count)) {
                                echo "<li class='page-item disabled'>";
                            } else {
                                echo "<li class='page-item'>";
                            }
                            echo "<a class='page-link' href='account.php?page=$next2'>" . $next2 . "</a></li>";


                            if (((($next - 1) * $numberOfRecords) + 1 > $count)) {
                                echo "<li class='page-item disabled'>";
                            } else {
                                echo "<li class='page-item'>";
                            }

                            echo "<a class='page-link' href='account.php?page=$next'>Next</a>";
                            ?>
                            </li>
                            <?php
                                if($count>$numberOfRecords)
                                {
                                    $lastPage = ceil($count / $numberOfRecords); // Round up so we get last page
                                    echo "<a class='page-link' href='account.php?page=$lastPage'>";
                                    echo '<span aria-hidden="true">&raquo;</span>';
                                    echo '</a>';
                                }
                                ?>

                        </ul>
                    </nav>

                </div>
            </div>
            <div class="col-lg-4 py-3" style="background-color:white">
                <div class="container-fluid border pt-2 fs-5 rounded">
                    <div class="pt-2 fs-5">
                        User profile
                    </div>
                    <hr>
                    <div class="fs-5 fw-normal text-break py-2" style="color:blue;">
                        <?php echo "Username <br>" . $user_data['username']; ?>
                    </div>
                    <div class="fs-5 pt-1 text-break py-2">
                        <?php echo "Name <br>" . $user_data['firstName'] . " " . $user_data['lastName']; ?>
                    </div>
                    <div class="fs-5 pt-1 text-break py-2">
                        <?php echo "Email <br>" . $user_data['emailAddress']; ?>
                    </div>

                    <?php
                    if (!empty($user_data['location'])) {
                        echo "<div class='fs-5 pt-1 py-2'>";
                        echo "Town <br>" . $user_data['location'] . "</div>";
                    } else {
                        echo "<div style='color:blue;'class='fs-5 pt-1'>";
                        echo "Add the town you live at </div>";
                    }
                    ?>
                    <?php
                    if (!empty($user_data['phoneNumber'])) {
                        echo "<div class='fs-5 pt-1 py-2'>";
                        echo "Phone Number: <br>" . $user_data['phoneNumber'] . "</div>";
                    } else {
                        echo "<div style='color:blue;'class='fs-5 pt-1'>";
                        echo "Add a phone number </div>";
                    }
                    ?>
                </div>








            </div>

        </div>


    </div>




</section>

<section>

    <!--Modal 1 - Confirm car deletion-->
    <div class="modal fade" id="deleteCar" aria-hidden="true" aria-labelledby="deletecar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color:orange;">
                    <h5 class="modal-title text-center text-light" id="carDeletion">
                        Confirm car removal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="fs-5 fw-bold">
                        Are you sure you want to remove your car?
                    </div>
                    <div class="fs-6 fw-light pt-3">
                        Taking your business else where means your safety is not guaranteed. Here at MotoTrader
                        we provide the best service and prices around.
                    </div>
                </div>
                <div class="modal-footer text-center" style="display:block;">
                    <div class="row">
                        <button type="button" class="btn-lg col-5 btn
                     btn-primary rounded-pill text-center" onclick="deleteSelectedCar()">Proceed
                        </button>
                        <div class="col-2">

                        </div>
                        <button type="button" class="btn-lg col-5 btn
                     btn-primary rounded-pill text-center">Cancel
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


</html>