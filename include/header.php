<?php


include("../control/controller.php");


if (isset($_SESSION['username']) || isset($_SESSION['employee_id'])) {
    $username = $_SESSION['username'];
    // echo $_SESSION['employee_id'];
}elseif(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}
else {
    header("Location: index.php");
    exit();
}
$sql = $conn->prepare("SELECT
            emp.employee_id as 'emp_id',
            emp.fullname as 'fullname',
            emp.image as 'image',
            p.position as 'position'
        FROM 
            users u
        left join 
            employees emp on emp.employee_id = u.employee_id
        left join
            positions p on p.position_id = emp.position_id
        WHERE 
            username = ?");
$sql->bind_param("s", $username);
$sql->execute();
$result = $sql->get_result();
$result->num_rows > 0;
$rows = $result->fetch_assoc();
$user_fullname = $rows['fullname'];
$users_id = $rows['emp_id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php
    include("head_content.php");
?>
</head>

<body>
    <div class="container">
        <div class="nav-container">
            <div class="logo">
                <img src="../img/images.jpg" alt="logo">
            </div>
            <div class="navbar">
                <nav>
                    <ul>
                        <li class="nav-links"><a class="" href="dashboard.php">Dashboard</a></li class="nav-links">
                        <li class="nav-links"><a class="" href="grades.php">Grades</a></li class="nav-links">
                        <li class="nav-links"><a class="registrar" href="student.php">Students</a></li class="nav-links">
                        <li class="nav-links"><a class="registrar admin" href="attendance.php ">Attendance</a></li class="nav-links">
                        <li class="nav-links"><a class="nav-links-teachers registrar principal" href="employee.php">Employees</a></li
                            class="nav-links">
                        <li class="nav-links "><a class="nav-links-teachers registrar" href="room.php">Rooms</a></li>
                        <li class="nav-links "><a class="nav-links-teachers registrar" href="subject.php">Subjects</a></li>
                        <li class="nav-links "><a class="nav-links-teachers registrar" href="classes.php">Classes</a></li>
                        <li class="nav-links "><a class="nav-links-teachers registrar principal" href="users.php">Users</a></li>
                    </ul>
                </nav>
            </div>
            <div class="user">
                <div class="fixed">
                    <div class="profile_image">
                        <img class="profile_pic" src="<?php echo $rows['image']; ?>" alt="user">
                    </div>
                    <p id="user_name"><a class="username" href=""><?php echo $user_fullname; ?></a> </p>
                    <div class="setting">
                        <p class="personal" data-user-data="<?php echo $user_fullname; ?>"><i class="fa fa-info-circle"
                                aria-hidden="true"></i>Personal Information</p>
                        <p class="account" data-account-user="<?php echo $username ?>"><i class="fa fa-cog"
                                aria-hidden="true"></i>Account Setting</p>
                        <p class="user_profile" data-profile-user="<?php echo $user_fullname; ?>"><i
                                class="fa-solid fa-user"></i>Profile Picture</p>
                        <p class="logout"><a href="../logout.php"><i class="fa fa-sign-out"
                                    aria-hidden="true"></i>Logout</a></p>
                    </div>
                </div>

            </div>
        </div>