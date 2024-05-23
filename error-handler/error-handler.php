<?php

if (isset($_SESSION['success-alert']) && $_SESSION['success-alert'] != '') {
    echo $_SESSION['success-alert'];
    unset($_SESSION['success-alert']);
}
if (isset($_SESSION['warning-alert']) && $_SESSION['warning-alert'] != '') {
    echo $_SESSION['warning-alert'];
    unset($_SESSION['warning-alert']);
}
if (isset($_SESSION['error-alert']) && $_SESSION['error-alert'] != '') {
    echo $_SESSION['error-alert'];
    unset($_SESSION['error-alert']);
}