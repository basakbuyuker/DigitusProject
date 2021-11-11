<?php

    $con=mysqli_connect('localhost', 'root', '', 'digitusproject');
    
    /*
    if(!$con){
        echo 'Connection error';
    }
    */

    function escape($string){
        global $con;
        return mysqli_real_escape_string($con, $string);
    }
    //query func
    function Query($query){
        global $con;
        return mysqli_query($con, $query);
    }
    //Confirmation func
    function confirm($result){
        global $con;
        if(!$result){
            die('Query failde.'.mysqli_error($con));
        }
    }
    //ftech data from database
    function fatech_data($result){      
        return mysqli_fetch_assoc($result);
    }
    //Row values from database
    function row_count($count){
        return mysqli_num_rows($count);
    }

?>