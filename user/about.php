<?php

include_once('db.php');
include_once('header.php');

if($_SESSION['true'] != true){
    echo 'not true';
    header("location:login.php");
    exit;
  }
  
?>