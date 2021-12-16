<?php
function checkIfAdminOrOwnsCar($loggedInUserData, $dataOfOwnerOfCar)
{
    if($loggedInUserData['userID']==$dataOfOwnerOfCar['userID'])
    {
        return true;
    } 
    elseif($loggedInUserData['isAdmin']==true)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function check_login($pdo)
{
    if(isset($_SESSION['userID'])) // If we have an active session, then return all the data related to that user
    {
        $userid = $_SESSION['userID'];
        $query= $pdo->prepare("SELECT * FROM users WHERE userID=? LIMIT 1");
        $query->execute([$userid]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            return $result;
        }

    }

    
}

function redirect($url)
{
    if(!headers_sent())
    {
        header('Location: '.$url);
        exit;

    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
function getQueryForCarFilter($midQuery,$pdo)
{
    console_log("we r in functions now");
    console_log($midQuery);
    if (!empty($midQuery)) {
        console_log("function midquery");
        return $pdo->prepare("SELECT COUNT('carIndex') as 'totalResults' FROM cars WHERE purchased=0 AND" . $midQuery . " GROUP BY 'carIndex'");
    } else {
        console_log("We r in this part");
        return $pdo->prepare("SELECT COUNT('carIndex') as 'totalResults' FROM cars WHERE purchased=0 GROUP BY 'carIndex'");
    }
}
function limit_text($text,$limit)
{
    if(strlen($text)<=$limit)
    {
        return $text;
    }
    else
    {
        return substr($text,0,$limit).'...';
    }
    
}



