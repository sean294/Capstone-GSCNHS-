<?php

// <--------------------------------------MODAL DELETE FOR CLASSES------------------------------->
if (isset($_POST['class_delete'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['class_id']);
        $select = "SELECT
                        *
                    FROM 
                        classes
                    WHERE 
                        class_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='classes.php' method='POST'>
                <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['class_id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='classes_delete'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR CLASSES------------------------------->



// <--------------------------------------MODAL DELETE FOR EMPLOYEES------------------------------->
if (isset($_POST['click_delete'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $select = "SELECT
                        employee_id as 'id',
                        fullname as 'fullname',
                        gender as 'gender',
                        contact_no as 'contact',
                        address as 'address',
                        positions.position as 'position',
                        image as 'image'
                    FROM 
                        employees
                    LEFT JOIN positions on positions.position_id = employees.position_id
                    WHERE 
                        employee_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='employee.php' method='POST' enctype='multipart/form-data'>
                <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='employee_delete'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR EMPLOYEES------------------------------->




// <--------------------------------------MODAL DELETE FOR ROOMS------------------------------->
if (isset($_POST['room_delete'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['room_id']);
        $select = "SELECT
                        room_id
                    FROM 
                        rooms
                    WHERE 
                        room_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='room.php' method='POST' enctype='multipart/form-data'>
                    <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['room_id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='delete_room'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL DELETE FOR ROOMS------------------------------->




// <--------------------------------------MODAL DELETE FOR STUDENTS------------------------------->
if (isset($_POST['student_delete'])){
    try {
        $id = $_POST['student_id'];
        $select = "SELECT
                        *
                    FROM 
                        students 
                    WHERE 
                        student_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='student.php' method='POST' enctype='multipart/form-data'>
                <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fullname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['student_id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='students_delete'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL DELETE FOR STUDENTS------------------------------->




// <--------------------------------------MODAL DELETE FOR SUBJECTS------------------------------->
if (isset($_POST['subject_delete'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['subject_id']);
        $select = "SELECT
                        subject_id as 'id',
                        subjects.name as 'subject',
                        subject_categories.name as 'category'
                    FROM 
                        subjects 
                    LEFT JOIN 
                        subject_categories on subject_categories.subj_cat_id = subjects.subj_cat_id
                    WHERE 
                    subject_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='subject.php' method='POST' enctype='multipart/form-data'>
                <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='subjects_delete'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL DELETE FOR SUBJECTS------------------------------->





// <--------------------------------------MODAL DELETE FOR USERS------------------------------->
if (isset($_POST['user_account_delete'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $select = "SELECT
                        *
                    FROM 
                        users
                    WHERE 
                        user_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='users.php' method='POST'>
                <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['user_id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='users_account_deleted'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL DELETE FOR USERS------------------------------->





// <--------------------------------------MODAL DELETE FOR GRADES------------------------------->
if (isset($_POST['grade_delete'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['grade_id']);
        $select = "SELECT
                        *
                    FROM 
                        grades
                    WHERE 
                        grade_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='grades.php' method='POST'>
                <h1 style='text-align:center;color:red'>Are you sure you want to delete this record?</h1>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                    <input hidden type='text' name='id' value='".$rows['grade_id']."'>
                </div>
                <div class='btn'>
                    <button type='submit' name='modal_grade_delete'>Delete</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL DELETE FOR GRADES------------------------------->