<?php

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $user = $conn->prepare("SELECT 
                                u.username, 
                                u.password,
                                emp.employee_id,
                                emp.fullname as 'fullname',
                                p.position as 'position'
                            FROM 
                                users u
                            LEFT JOIN
                                employees emp on emp.employee_id = u.employee_id
                            left join
                                positions p on p.position_id = emp.position_id
                            WHERE 
                                u.username = ?");
    $user->bind_param("s", $username);
    $user->execute();
    $result = $user->get_result();

    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();
        $hashedPassword = $rows['password'];
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['employee_id'] = $rows['employee_id'];
            if ($rows['position'] == "Administrator" || $rows['position'] == "Administrative Assistant" || 
            $rows['position'] == "Principal" || $rows['position'] == "Vice Principal") {
                $_SESSION['success-alert'] = "<script>swal('Login Successfully', 'Welcome ".$rows['fullname']."', 'success')</script>";
                header("Location: admin/dashboard.php");
                exit();
            }elseif ($rows['position'] == "Teacher") {
                $_SESSION['success-alert'] = "<script>swal('Login Successfully', 'Welcome ".$rows['fullname']."', 'success')</script>";
                header("Location: teacher/dashboard.php");
                exit();
            }
            
        } else {
            $_SESSION['error-alert'] = "<script>swal('Error!', 'Username and Password Incorrect!: " . $conn->error . "', 'error')</script>";
        }
    } else {
        $_SESSION['error-alert'] = "<script>swal('Error!', 'Username and Password Incorrect!: " . $conn->error . "', 'error')</script>";
    }
}






