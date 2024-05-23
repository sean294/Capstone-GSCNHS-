<?php

$conn = mysqli_connect("localhost", "root", "", "gschns-shs");

if (!$conn) {
    # code...
    die("Connection Failed".mysqli_connect_error());
}
session_start();

$error = array();
$success = array();
$warning = array();
$id = "";
$fullname = "";
$address = "";
$contact = "";
$father = "";
$mother = "";
$parent_contact = "";
$strand = "";
$username = "";
$password = "";
$room = "";
$day = "";
$startDateTime = "";
$endDateTime = "";
$sy = "";
$gender = "";
$subject = "";
$category = "";
$teacher = "";



include("delete.php");
include("insert.php");
include("login.php");
include("modal_delete.php");
include("modal_update.php");
include("update.php");
include("search.php");
include("bargraph.php");


