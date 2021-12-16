<?php
session_start();
require "navBar.php";
$numberOfRecords = 8;
$isSearchBeingUsed = false;
$isFilterBeingUsed = false;
if (!isset($_GET['page'])) { // If we haven't been through pages before, default the page to 1
    $pageNumber = 1;
    $offset = 0;
} else {
    $pageNumber = $_GET['page']; // If we're going through pages, check what page number was posted
    $offset = ($pageNumber - 1) * $numberOfRecords;
}

if (isset($_GET['navBarSearch'])) { // Check it was the search bar used
    $isSearchBeingUsed = true;
    $navBarSearch = $_GET['navBarSearch'];
    $stmt = $pdo->prepare("SELECT * 
    FROM cars WHERE purchased=0 AND
    `make` LIKE '%" . $navBarSearch . "%' OR 
    `model` LIKE '%" . $navBarSearch . "%' OR
    `Reg` LIKE '%" . $navBarSearch . "%' OR
    `colour` LIKE '%" . $navBarSearch . "%' OR
    `description` LIKE '%" . $navBarSearch . "%' 
    LIMIT " . $offset . ", " . $numberOfRecords);
    $array = ['make', 'model', 'Reg', 'colour', 'description', 'miles', 'town', 'price']; // Query for any car containing the key word
    $stmt->execute();




    $totalPrep = $pdo->prepare("SELECT COUNT('carIndex') as 'totalResults' 
    FROM cars WHERE purchased=0 AND 
    `make` LIKE '%" . $navBarSearch . "%' OR 
    `model` LIKE '%" . $navBarSearch . "%' OR
    `Reg` LIKE '%" . $navBarSearch . "%' OR
    `colour` LIKE '%" . $navBarSearch . "%' OR
    `description` LIKE '%" . $navBarSearch . "%' 
    GROUP BY 'carIndex'"); // Get the total number of results from that query
    $totalPrep->execute();
    $prep = $totalPrep->fetch(PDO::FETCH_ASSOC);
    $count = $prep['totalResults'];
} else { // If the user is not using the search bar
    $midQuery = ""; // Empty as we are going to dynamically construct the query based on what the user has entered
    $isFilterBeingUsed = true; // Make sure we know the filters being used
    $query = "SELECT * FROM cars"; // Beginning of the query
    $getWithAValue = array_filter($_GET); // This will put into an array any values that was sent with a GET method, that were not empty
    $addAndToPurchased = 0; // This is here so we can know when to stop add and statements
    if (count($getWithAValue)) { // If this exists
        $query .= " WHERE purchased=0"; // Add to this to query
        $keynames = array_keys($getWithAValue); // Get keys of the get data in the array
        $numberOfElements = count($getWithAValue); // Get number of elements
        $i = 0;


        foreach ($getWithAValue as $key => $value) { // For every key in the array, get the value
            if ($key == 'page') { // If the key is page, we ignore it as we dont want to do an SQL query for a non-existent field
                $i++;
                continue;
            }

            if ($key == 'minprice') { 
                $i++;
                $addAndToPurchased++;
                $midQuery .= " price >= '$value'"; // Add this to the query, we dont use the key, as the key is named differently as we have price twice
                if (($numberOfElements > 1) && ($i < $numberOfElements)) {
                    $midQuery .= " AND"; // If this isn't the last part we want to filter for, then add an AND statement to the end
                }
            } elseif ($key == 'maxprice') {
                $i++;
                $addAndToPurchased++;
                $midQuery .= " price <= '$value'";
                if (($numberOfElements > 1) && ($i < $numberOfElements)) {
                    $midQuery .= " AND";
                }
            } elseif ($key == 'minmiles') {
                $i++;
                $addAndToPurchased++;
                $midQuery .= " miles >= '$value'";
                if (($numberOfElements > 1) && ($i < $numberOfElements)) {
                    $midQuery .= " AND";
                }
            } elseif ($key == 'maxmiles') {
                $i++;
                $addAndToPurchased++;
                $midQuery .= " miles <= '$value'";
                if (($numberOfElements > 1) && ($i < $numberOfElements)) {
                    $midQuery .= " AND";
                }
            } else {
                $i++;
                $addAndToPurchased++;
                $midQuery .= " $key = '$value'";
                if (($numberOfElements > 1) && ($i < $numberOfElements)) {
                    $midQuery .= " AND";
                }
            }
        }
    }
    if ($addAndToPurchased >= 1) {// Decides whether an and statement needs to be added to the first part of the query
        $query .= " AND";
    }
    $firstQuery = $query . "" . $midQuery . " LIMIT " . $offset . ", " . $numberOfRecords; // Constructs the full query
    $stmt = $pdo->prepare($firstQuery);
    $array = ['make', 'model', 'Reg', 'colour', 'description', 'miles', 'town', 'price'];
    $stmt->execute();

    $totalPrep = getQueryForCarFilter($midQuery, $pdo);
    $totalPrep->execute();
    $prep = $totalPrep->fetch(PDO::FETCH_ASSOC);
    $count = $prep['totalResults'];
}
?>
<script>
    function saveCarIndexForDeletion(value) {
        document.cookie = `carIndexForDeletion=${value}`;
    }

    function editCarReDirect(value) {
        window.location.href = "editCarDetails.php?index=" + value;
    }


    function deleteSelectedCar() {
        var xmlhttp = new XMLHttpRequest();
        window.location.href = "deleteCar.php?index=" + getCookieName(`carIndexForDeletion`) + "&redirectadmin=true";

    }
</script>
<title>
    <?php
    if ($isFilterBeingUsed) {
        echo "Browsing Cars";
    } elseif ($isSearchBeingUsed) {
        echo $navBarSearch;
    }

    ?> - MotoTrader.com
</title>
<section>
    <!-- Grid layout of cars-->
    <div class="row">
        <div class="col-1">

        </div>
        <div class="col-lg-10 border rounded-2" style="background-color:white;">
            <div class="container-fluid py-3">
                <b><?php echo "$count" ?></b> Results found
            </div>
            <hr>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userID = $row['userID'];
                $imgFileName = $row['pictureFilesName'];
                $carIndex = $row['carIndex'];
            ?>
                
                <div class="container-fluid row py-3">
                    <!-- item layout -->
                    <div class="row">
                        <div class="col-4 border py-2 shadow" style="background-color:whitesmoke;">
                            <!-- thumbnail-->
                            <?php
                            if ($imgFileName == 'image_unavailable.jpg') {
                                echo "<img src='pictures/image_unavailable.jpg' class='img-fluid' alt='' style='width:100%;height:auto;display:block;'>";
                            } else {
                                echo "<img src='pictures/$userID/$carIndex/$imgFileName' class='img-fluid' alt='' style='width:100%;height:auto;display:block;'>";
                            }
                            ?>
                        </div>
                        <div class="col-lg-7 d-table text-start pt-2">
                            <button class="d-table-row container-fluid fs-5 fw-bold text-start border-0" style="color:#ff8c00;background-color:white; padding-left:0px;">
                                <?php
                                echo "<a href=carDisplay.php?carIndex=" . $row['carIndex'] .
                                    " >" . $row['make'] . " " . $row[$array[1]] . "</a>";


                                ?>
                                

                            </button>
                            <div class="d-table-row container-fluid fs-5"><?php echo $row[$array[5]] ?> Miles</div>
                            <div class="d-table-row container-fluid fs-5"><?php echo "Condition: " . $row['carCondition'] ?></div>
                            <div class="d-table-row container-fluid fs-3 fw-bold" style="color:#ff8c00;">£<?php echo $row[$array[7]] ?></div>
                        </div>
                        <?php
                        if ($user_data['isAdmin'] == 1) { ?>
                            <div class="col-lg-1 position-relative">
                                <button onclick="saveCarIndexForDeletion(<?php echo $row['carIndex'] ?>)" class="btn btn-danger bi bi-trash border-0 
                            position-absolute top-0 end-0" data-bs-target="#deleteCar" data-bs-toggle="modal">

                                </button>
                                <button class="btn btn-primary border-0 position-absolute 
                            bottom-0 end-0" onclick="editCarReDirect(<?php echo $row['carIndex'] ?>)">
                                    Edit
                                </button>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </div>
                <hr>

            <?php
            }
            $next = $pageNumber + 1;
            $previous = $pageNumber - 1;
            $first = 1;

            if ($isFilterBeingUsed) {
                $postvariable = "&make=" . $_GET['make'] . "&model=" . $_GET['model'] . "&colour=" . $_GET['colour'] . "&minprice=" . $_GET['minprice'] . "&maxprice=" . $_GET['maxprice'] . "&minmiles=" . $_GET['minmiles'] . "&maxmiles=" . $_GET['maxmiles'];
            } else {
                $postvariable = "&navBarSearch=" . $_GET['navBarSearch'];
            }


            ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <?php
                        echo "<a class='page-link' href='searchCars.php?page=1" . "$postvariable'>"
                        ?>
                        <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php if ($pageNumber > 1) { // Previous search
                        echo '<li class="page-item">';
                        echo "<a class='page-link' href='searchCars.php?page=$previous" . "$postvariable'>Previous</a>";
                        echo '</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="page-item disabled">';
                        echo '<a class="page-link" tabindex="-1" aria-disabled="true">Previous</a>';
                        echo '</li>';
                    }


                    $next2 = $pageNumber + 2; // Current page
                    echo "<li class='page-item active'><a class='page-link' href='searchCars.php?page=$pageNumber" . "$postvariable'>" . $pageNumber . "</a></li>";


                    if (((($next - 1) * $numberOfRecords) + 1 > $count)) { // current page +1
                        echo "<li class='page-item disabled'>";
                    } else {
                        echo "<li class='page-item'>";
                    }

                    echo "<a class='page-link' href='searchCars.php?page=$next" . "$postvariable'>" . $next . "</a></li>";

                    if (((($next2 - 1) * $numberOfRecords) + 1 > $count)) { //current page +2
                        echo "<li class='page-item disabled'>";
                    } else {
                        echo "<li class='page-item'>";
                    }
                    echo "<a class='page-link' href='searchCars.php?page=$next2" . "$postvariable'>" . $next2 . "</a></li>";


                    if (((($next - 1) * $numberOfRecords) + 1 > $count)) { // Next page
                        echo "<li class='page-item disabled'>";
                    } else {
                        echo "<li class='page-item'>";
                    }

                    echo "<a class='page-link' href='searchCars.php?page=$next" . "$postvariable'>Next</a>";
                    ?>
                    </li>

                    <li class="page-item">
                        <?php
                        if ($count > $numberOfRecords) {
                            $lastPage = ceil($count / $numberOfRecords);
                            echo "<a class='page-link' href='searchCars.php?page=" . $lastPage . "" . "$postvariable'>";

                            echo '<span aria-hidden="true">&raquo;</span>';
                            echo '</a>';
                        }
                        ?>

                    </li>

                </ul>
            </nav>
            <!-- to here -->

        </div>
        <div class="col-1">

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