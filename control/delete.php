<?php

// <--------------------------------------MODAL DELETE FOR CLASSES------------------------------->
if (isset($_POST['classes_delete'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete_classes = $conn->prepare("DELETE FROM classes WHERE class_id = ?");
        $delete_classes->bind_param("i", $id);
        if ($delete_classes->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR CLASSES------------------------------->




// <--------------------------------------MODAL DELETE FOR EMPLOYEES------------------------------->
if (isset($_POST['employee_delete'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete_emp = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $delete_emp->bind_param("i", $id);
        if ($delete_emp->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
        // Close the prepared statement
        // $delete_emp->close();
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR EMPLOYEES------------------------------->



// <--------------------------------------MODAL DELETE FOR ROOMS------------------------------->
if (isset($_POST['delete_room'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
        $delete->bind_param("i", $id);
        if ($delete->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
        // Close the prepared statement
        // $delete_emp->close();
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR ROOMS------------------------------->



// <--------------------------------------MODAL DELETE FOR STUDENTS------------------------------->
if (isset($_POST['students_delete'])) {
    try {
        // Assuming you have sanitized your employee_id value; consider using prepared statements
        $id = $_POST['id'];

        // Use prepared statement to prevent SQL injection
        $delete_student = $conn->prepare("DELETE FROM students WHERE student_id = ?");

        // Bind parameter
        $delete_student->bind_param("i", $id);

        // Execute the delete
        if ($delete_student->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR STUDENTS------------------------------->



// <--------------------------------------MODAL DELETE FOR SUBJECTS------------------------------->
if (isset($_POST['subjects_delete'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete_student = $conn->prepare("DELETE FROM subjects WHERE subject_id = ?");
        $delete_student->bind_param("i", $id);
        
        if ($delete_student->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR SUBJECTS------------------------------->





// <--------------------------------------MODAL DELETE FOR USERS------------------------------->
if (isset($_POST['users_account_deleted'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete_student = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $delete_student->bind_param("i", $id);
        
        if ($delete_student->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL DELETE FOR USERS------------------------------->





// <--------------------------------------DELETE FOR GRADES------------------------------->
if (isset($_POST['modal_grade_delete'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $delete_student = $conn->prepare("DELETE FROM grades WHERE grade_id = ?");
        $delete_student->bind_param("i", $id);
        
        if ($delete_student->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Data successfully deleted!', '', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Data Failed to delete!', '$conn->error', 'error')</script>";
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------DELETE FOR GRADES------------------------------->