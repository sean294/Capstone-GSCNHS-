<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSCNHS | Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <script type="text/javascript" src="../js/qrcode.js"></script>
    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/select2.min.js"></script>
    <link rel="icon" href="../img/images-modified-icon.png" type="image/x-icon">
    <script src="../js/user_setting.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/chart.js"></script>
    <style>
    .readonly-link {
        pointer-events: none !important;
        color: #ded2d2a2 !important;
        /* Optional: Change color to indicate readonly state */
        text-decoration: none !important;
        /* Optional: Remove underline */
        cursor: default !important;
        /* Optional: Set cursor to default */
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #myTable, #myTable * {
            visibility: visible;
        }
        #myTable {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<script >
    
document.addEventListener('DOMContentLoaded', function() {
    var userPosition = "<?php echo $rows['position']; ?>";

    // Check user's position and make links readonly if the user is a teacher
    if (userPosition === "Teacher") {
        makeLinksReadonly();
        hideDeleteColumn();
    } else if (userPosition === "Admin" || userPosition === "Administrator") {
        showDeleteColumn();
    } else if (userPosition === "Principal" || userPosition === "Vice Principal") {
        makeLinksReadonlyForPrincipal();
        showDeleteColumn();
    } else if (userPosition === "Registrar") {
        makeLinksReadonlyForRegistrar();
        showDeleteColumn();
    }
});

function makeLinksReadonly() {
    // Get links to make readonly
    var linksToMakeReadonly = document.querySelectorAll('.nav-links-teachers');

    // Add readonly class to links
    linksToMakeReadonly.forEach(function(link) {
        link.classList.add('readonly-link');
    });
}    
function makeLinksReadonlyForPrincipal() {
    // Get links to make readonly
    var linksToMakeReadonly = document.querySelectorAll('.principal');

    // Add readonly class to links
    linksToMakeReadonly.forEach(function(link) {
        link.classList.add('readonly-link');
    });
}    
function makeLinksReadonlyForRegistrar() {
    // Get links to make readonly
    var linksToMakeReadonly = document.querySelectorAll('.registrar');

    // Add readonly class to links
    linksToMakeReadonly.forEach(function(link) {
        link.classList.add('readonly-link');
    });
}
function makeLinksReadonlyForAdmin() {
    // Get links to make readonly
    var linksToMakeReadonly = document.querySelectorAll('.admin');

    // Add readonly class to links
    linksToMakeReadonly.forEach(function(link) {
        link.classList.add('readonly-link');
    });
}
function hideDeleteColumn() {
    var deleteIcons = document.querySelectorAll('.delete_icon');
    deleteIcons.forEach(function(deleteIcon) {
        deleteIcon.hidden = true;
    });
}

function showDeleteColumn() {
    var deleteIcons = document.querySelectorAll('.delete_icon');
    deleteIcons.forEach(function(deleteIcon) {
        deleteIcon.hidden = false;
    });
    
}

</script>