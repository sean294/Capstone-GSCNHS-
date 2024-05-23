<?php
session_unset();
session_destroy();

// Redirect to the login page (adjust the path as needed)
header("Location: index.php");
exit();