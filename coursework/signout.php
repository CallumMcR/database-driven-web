<?php
session_start();
include('functions.php');

if(isset($_SESSION['userID']))
    {
        unset($_SESSION['userID']); // Removes the data held in the session
    }
    redirect('homePage.php');
    die;
