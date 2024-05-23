<?php

// <--------------------------------------UPDATE FOR CLASSES------------------------------->
if (isset($_POST['class_Update'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $employee = mysqli_real_escape_string($conn, $_POST['employee']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $room = mysqli_real_escape_string($conn, $_POST['room']);
        $day = mysqli_real_escape_string($conn, $_POST['day']);
        $startDateTime = mysqli_real_escape_string($conn, $_POST['start']);
        $endDateTime = mysqli_real_escape_string($conn, $_POST['end']);
        $sy = mysqli_real_escape_string($conn, $_POST['school_year']);

        $start = new DateTime($startDateTime);
        $end = new DateTime($endDateTime);

        $allowedTimeAm = new Datetime('8:00');
        $allowedTimePm = new Datetime('17:00');
    
        $interval = $start->diff($end);
        $hoursDifference = $interval->h;
        $minutesDifference = $interval->i;
        
        $totalMinutesDifference = $hoursDifference * 60 + $minutesDifference;
        
    
        if ($start < $allowedTimeAm) {
            $_SESSION['warning-alert'] = "<script>swal('Error!', 'Allowed time is Start at 8AM. Error-> ".$start->format('H:i:s A')."', 'warning')</script>";
        }elseif ($end > $allowedTimePm) {
            $_SESSION['warning-alert'] = "<script>swal('Error!', 'Allowed time is Ends at 5PM. Error-> ".$end->format('H:i:s A')."', 'warning')</script>";
        }elseif ($totalMinutesDifference < 60) {
            $_SESSION['warning-alert'] = "<script>swal('Error!', 'Time schedule should not less than an hour!. Error-> ".$start->format('H:i:s A')." and ".$end->format('H:i:s A')."', 'warning')</script>";
        }elseif ($totalMinutesDifference > 120){
            $_SESSION['warning-alert'] = "<script>swal('Error!', 'Time should no exceed 2hours!. Error-> ".$start->format('H:i:s A')." and ".$end->format('H:i:s A')."', 'warning')</script>";
        }else{
            $startFormatted = $start->format('H:i:s');
            $endFormatted = $end->format('H:i:s');
    
                // firts check if the subjects and rooms are overlapping or have duplication and time as well
            $check = $conn->prepare("SELECT * FROM classes WHERE class_id != ? AND  employee_id = ? AND subject_id = ? AND room_id = ? AND day = ? AND start = ? AND end = ?");
            $check->bind_param("iiiisss", $id, $employee, $subject, $room, $day, $startFormatted , $endFormatted);
            $check->execute();
            $result_check = $check->get_result();
    
                // check if the subjects and rooms and times are overlapping
            $second_check = $conn->prepare("SELECT * FROM classes WHERE class_id != ? AND employee_id = ? AND subject_id = ? AND room_id = ? AND day = ? AND (end > ?) AND (start < ?)");
            $second_check->bind_param("iiiisss", $id,  $employee, $subject, $room, $day, $startFormatted , $endFormatted);
            $second_check->execute();
            $result_second_check = $second_check->get_result();

                // check if classes have the same class as the room
            $third_check = $conn->prepare("SELECT * FROM classes WHERE class_id != ? AND subject_id = ? AND room_id = ?");
            $third_check->bind_param("iii", $id, $subject, $room);
            $third_check->execute();
            $result_third_check = $third_check->get_result();

            // check if there is time overlapping
            $fourth_check = $conn->prepare("SELECT * FROM classes WHERE class_id != ? AND room_id = ? AND day = ? AND (end > ?) AND (start < ?)");
            $fourth_check->bind_param("iisss", $room, $day, $startFormatted , $endFormatted);
            $fourth_check->execute();
            $result_fourth_check = $fourth_check->get_result();

            // check if there is a class where the same teacher the same time as well as the same day but different room
            $fifth_check = $conn->prepare("SELECT * FROM classes WHERE class_id != ? AND  employee_id = ? AND subject_id = ? and day = ? AND (end > ?) AND (start < ?)");
            $fifth_check->bind_param("iiisss", $id, $employee, $subject, $day, $startFormatted , $endFormatted);
            $fifth_check->execute();
            $result_fifth_check = $fifth_check->get_result();
    
            if ($result_check->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Class already exists!', 'warning')</script>";
            }elseif($result_second_check->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Class schedule time overlapping with existing ones!', 'warning')</script>";
            }elseif($result_third_check->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Class already exists in this room!', 'warning')</script>";
            }elseif($result_fifth_check->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Class Schedule is overlapping with different classes!', 'warning')</script>";
            }elseif($result_fourth_check->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Class time is overlapping to another class', 'warning')</script>";
            }else {
    
                $update_class = $conn->prepare("UPDATE classes SET employee_id = ?, subject_id = ?, room_id =?, day = ?, start = ?, 
                end = ?, school_year = ? WHERE class_id = ?");
                $update_class->bind_param("iiissssi", $employee, $subject, $room, $day, $startFormatted , $endFormatted, $sy, $id);
                $result = $update_class->execute();
    
                if ($result) {
                    $_SESSION['success-alert'] = "<script>swal('Data successfully updated!', '', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Data Failed to insert!', '$conn->error', 'error')</script>";
                }
            }
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        // echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------UPDATE FOR CLASSES------------------------------->






// <--------------------------------------UPDATE FOR EMPLOYEES------------------------------->
if (isset($_POST['employee_update'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);
    
        if (!preg_match('/^[A-Z a-z]+$/', $fullname)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only ".$fullname."', 'warning')</script>";
        }elseif (!preg_match('/^[+0-9]+$/', $contact)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Numbers and plus symbol only! $contact', 'warning')</script>";
        }else{
            $check = $conn->prepare("SELECT * FROM employees WHERE employee_id != ? AND fullname = ?");
            $check->bind_param("is", $id, $fullname);
            $check->execute();
            $result = $check->get_result();
        
            $second_check = $conn->prepare("SELECT * FROM employees WHERE employee_id != ? AND contact_no = ?");
            $second_check->bind_param("is", $id, $contact);
            $second_check->execute();
            $second_result = $second_check->get_result();
        
            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Name already exist for other records!', 'warning')</script>";
            }elseif ($second_result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Contact number already exist for other records!', 'warning')</script>";
            }else {
                $update_emp = $conn->prepare("UPDATE employees SET fullname = ?, gender = ?, 
                contact_no = ?, address = ?, position_id = ? WHERE employee_id = ?");
                $update_emp->bind_param("ssssii", $fullname, $gender, $contact, $address, $position, $id);
            
                if ($update_emp->execute()) {
                    $_SESSION['success-alert'] = "<script>swal('Data successfully updated!', '', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Data Failed to insert!', '$conn->error', 'error')</script>";
                }
            }
        } 
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        // echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------UPDATE FOR EMPLOYEES------------------------------->







// <--------------------------------------UPDATE FOR EMPLOYEES-PROFILE------------------------------->
if (isset($_POST['emp_update_picture'])) {
    try {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        if ($_FILES['image_file']['name'] != '') {
            $allowed_extensions = array("jpeg", "jpg", "png");
            $file_split = explode('.', $_FILES['image_file']['name']);
            $result_ext = end($file_split);

            if (in_array($result_ext, $allowed_extensions)) {
                if ($_FILES['image_file']['size'] < 5000000) {
                    $updated_name = $fullname . '.' . $result_ext;
                    $updated_path = "../images/updated/employee/" . $updated_name;

                    $update_profile = $conn->prepare("UPDATE employees SET image = ? WHERE employee_id = ?");
                    $update_profile->bind_param("si", $updated_path, $id);
                    
                    if ($update_profile->execute()) {
                        move_uploaded_file($_FILES['image_file']['tmp_name'], $updated_path);
                        $_SESSION['success-alert'] = "<script>swal('Profile successfully updated!', '', 'success')</script>";
                    } else {
                        $_SESSION['error-alert'] = "<script>swal('Failed to updated profile!', '', 'error')</script>";
                    }
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'File size is too big', 'error')</script>";
                }
            } else {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Invalid image file', 'warning')</script>";
            }
        } else {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Please select a file', 'warning')</script>";
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        // echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------UPDATE FOR EMPLOYEES-PROFILE------------------------------->






// <--------------------------------------UPDATE FOR ROOMS------------------------------->
if (isset($_POST['rooms_update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);
    $strand = mysqli_real_escape_string($conn, $_POST['strand']);

    if (!preg_match('/^[A-Z a-z 0-9]+$/', $room)) {
        $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters and Numbers only ".$room."', 'warning')</script>";
    }else {

        $check = $conn->prepare("SELECT * FROM rooms where room_id != ? AND name = ?");
        $check->bind_param("is", $id, $room);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Room name already exist!', 'warning')</script>";
        }else {
            $insert_room = $conn->prepare("UPDATE rooms SET name = ?, strand_id = ? where room_id = ?");
            $insert_room->bind_param("sii", $room, $strand, $id);
            if ($insert_room->execute()) {
                $_SESSION['success-alert'] = "<script>swal('Data successfully updated!', '', 'success')</script>";
            }else {
                $_SESSION['error-alert'] = "<script>swal('Erro Updating Dat!', '', 'error')</script>";
            } 
        }        
    }
}
// <--------------------------------------UPDATE FOR ROOMS------------------------------->






// <--------------------------------------UPDATE FOR STUDENTS------------------------------->
if (isset($_POST['students_update'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $father = mysqli_real_escape_string($conn, $_POST['father_name']);
        $mother = mysqli_real_escape_string($conn, $_POST['mother_name']);
        $parent_contact = mysqli_real_escape_string($conn, $_POST['parent_contact']);

        if (!preg_match('/^[A-Z a-z]+$/', $fullname)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only $fullname', 'warning')</script>";
        }elseif (!preg_match('/^[A-Z a-z]+$/', $father)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only $father', 'warning')</script>";
        }elseif (!preg_match('/^[A-Z a-z]+$/', $mother)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only $mother', 'warning')</script>";
        }elseif (!preg_match('/^[+0-9]+$/', $parent_contact)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Numbers and special symbols only $parent_contact', 'warning')</script>";
        }elseif (!preg_match('/^[+0-9]+$/', $contact)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Numbers and special symbols only $contact', 'warning')</script>";
        }else{
            
            $check = $conn->prepare("SELECT * FROM students WHERE student_id != ? and fullname = ? AND contact_no = ?");
            $check->bind_param("iss", $id, $fullname, $contact);
            $check->execute();
            $result = $check->get_result();

            $contact_check = $conn->prepare("SELECT * FROM students WHERE student_id != ? and contact_no = ?");
            $contact_check->bind_param("is", $id, $contact);
            $contact_check->execute();
            $contact_check_result = $contact_check->get_result();

            $parent_contact_check = $conn->prepare("SELECT * FROM students WHERE student_id != ? and parent_contact = ?");
            $parent_contact_check->bind_param("is", $id, $contact);
            $parent_contact_check->execute();
            $parent_result = $parent_contact_check->get_result();

            if ($result->num_rows > 0){
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Name or Contact Number already exist in records! $fullname and $contact', 'warning')</script>";
            }elseif ($contact_check_result->num_rows > 0){
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Contact number aleary exist in records! $contact', 'warning')</script>";
            }elseif ($parent_result->num_rows > 0){
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Parent contact already exist! $parent_contact', 'warning')</script>";
            }else {
                $update_student = $conn->prepare("UPDATE students SET fullname = ?, gender = ?, 
                contact_no = ?, address = ?, father_name = ?, mother_name = ?, parent_contact = ? WHERE student_id = ?");
            
                // Bind parameters
                $update_student->bind_param("sssssssi", $fullname, $gender, $contact, $address, $father, $mother, $parent_contact, $id);

                if ($update_student->execute()) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully updated!', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to updated data!: " . $conn->error . "', 'error')</script>";
                }
            }     
        } 
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        // echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------UPDATE FOR STUDENTS------------------------------->





// <--------------------------------------UPDATE FOR STUDENTS-PROFILE------------------------------->
if (isset($_POST['stud_update_picture'])) {
    try {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        if ($_FILES['image_file']['name'] != '') {
            $allowed_extensions = array("jpeg", "jpg", "png");
            $file_split = explode('.', $_FILES['image_file']['name']);
            $result_ext = end($file_split);

            if (in_array($result_ext, $allowed_extensions)) {
                if ($_FILES['image_file']['size'] < 200000) {
                    $updated_name = $fullname . '.' . $result_ext;
                    $updated_path = "../images/updated/student/" . $updated_name;

                    $update_profile = $conn->prepare("UPDATE students SET image = ? WHERE student_id = ?");
                    $update_profile->bind_param("si", $updated_path, $id);
                    
                    if ($update_profile->execute()) {
                        move_uploaded_file($_FILES['image_file']['tmp_name'], $updated_path);
                        $_SESSION['success-alert'] = "<script>swal('Profile successfully updated!', '', 'success')</script>";
                    } else {
                        $_SESSION['error-alert'] = "<script>swal('Failed to updated profile!', '', 'error')</script>";
                    }
                } else {
                    $_SESSION['warning-alert'] = "<script>swal('Warning!', 'File too big!', 'warning')</script>";
                }
            } else {
                $_SESSION['error-alert'] = "<script>swal('Error!', 'invalid Image File!', 'error')</script>";
            }
        } else {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Please select file', 'warning')</script>";
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        // echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------UPDATE FOR STUDENTS-PROFILE------------------------------->






// <--------------------------------------UPDATE FOR SUBJECTS------------------------------->
if (isset($_POST['subjects_update'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
    
        if (!preg_match('/^[A-Z a-z 0-9]+$/', $subject)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters and Numbers only ".$subject."', 'warning')</script>";
        }else{
            $check = $conn->prepare("SELECT * FROM subjects WHERE name = ? AND subject_id != ?");
            $check->bind_param("si", $subject, $id);
            $check->execute();
            $result = $check->get_result();
    
            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Subject already exists!', 'warning')</script>";
            } else {
                // Updating the subject
                $update_subject = $conn->prepare("UPDATE subjects SET name = ?, subj_cat_id = ? WHERE subject_id = ?");
                $update_subject->bind_param("sii", $subject, $category, $id);
    
                if ($update_subject->execute()) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully updated!', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to update data!: " . $conn->error . "', 'error')</script>";
                }
            }
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        // echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------UPDATE FOR SUBJECTS------------------------------->






// <--------------------------------------UPDATE FOR USERS-PERSONAL-INFORMATION------------------------------->
//this is the update after you click the PERSONAL INFORMATION then click the update
if (isset($_POST['users_update_profile_information'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);
    
        if (!preg_match('/^[A-Z a-z]+$/', $fullname)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only ".$fullname."', 'warning')</script>";
        }elseif (!preg_match('/^[0-9]+$/', $contact)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Numbers only ".$contact."', 'warning')</script>";
        }else{
            $check = $conn->prepare("SELECT * FROM employees WHERE employee_id != ? AND fullname = ?");
            $check->bind_param("is", $id, $fullname);
            $check->execute();
            $result = $check->get_result();
        
            $second_check = $conn->prepare("SELECT * FROM employees WHERE employee_id != ? AND contact_no = ?");
            $second_check->bind_param("is", $id, $contact);
            $second_check->execute();
            $second_result = $second_check->get_result();
        
            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Name already exist in records!', 'warning')</script>";
            }elseif ($second_result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Contact number already exist in records!', 'warning')</script>";
            }else {
                $update_emp = $conn->prepare("UPDATE employees SET fullname = ?, gender = ?, 
                contact_no = ?, address = ?, position_id = ? WHERE employee_id = ?");
            
                // Bind parameters
                $update_emp->bind_param("ssssii", $fullname, $gender, $contact, $address, $position, $id);
            
                if ($update_emp->execute()) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully updated!', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to update data!: " . $conn->error . "', 'error')</script>";
                }
            }
        } 
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
}
// <--------------------------------------UPDATE FOR USERS-PERSONAL-INFORMATION------------------------------->





// <--------------------------------------UPDATE FOR USERS-ACCOUNTS------------------------------->
if (isset($_POST['account_update'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $check_users = $conn->prepare("SELECT * FROM users WHERE user_id != ? AND username = ?");
        $check_users->bind_param("is", $id, $username);
        $check_users->execute();
        $result = $check_users->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Username already exist in records!', 'warning')</script>";
        }else {
            $hashpass = password_hash($password, PASSWORD_DEFAULT);

            $update_users = $conn->prepare("UPDATE users SET username = ?, password = ? where user_id = ?");
            $update_users->bind_param("iss", $id, $username, $hashpass);

            if ($update_users->execute()) {
                $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully updated!', 'success')</script>";
            } else {
                $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to update data!: " . $conn->error . "', 'error')</script>";
            }
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
}
// <--------------------------------------UPDATE FOR USERS-ACCOUNTS------------------------------->





// <--------------------------------------UPDATE FOR USERS-PROFILE-PICTURE------------------------------->
if (isset($_POST['update_users_profile'])) {
    try {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        if ($_FILES['image_file']['name'] != '') {
            $allowed_extensions = array("jpeg", "jpg", "png");
            $file_split = explode('.', $_FILES['image_file']['name']);
            $result_ext = end($file_split);

            if (in_array($result_ext, $allowed_extensions)) {
                if ($_FILES['image_file']['size'] < 200000) {
                    $updated_name = $fullname . '.' . $result_ext;
                    $updated_path = "../images/updated/employee/" . $updated_name;

                    $update_profile = $conn->prepare("UPDATE employees SET image = ? WHERE employee_id = ?");
                    $update_profile->bind_param("si", $updated_path, $id);
                    
                    if ($update_profile->execute()) {
                        move_uploaded_file($_FILES['image_file']['tmp_name'], $updated_path);
                        $_SESSION['success-alert'] = "<script>swal('Success!', 'Profile successfully updated!', 'success')</script>";
                    } else {
                        $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to updated profile!', 'error')</script>";
                    }
                } else {
                    $_SESSION['warning-alert'] = "<script>swal('Warning!', 'File size is too big', 'warning')</script>";
                }
            } else {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Invalid image file', 'warning')</script>";
            }
        } else {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Please select a file', 'warning')</script>";
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
}
// <--------------------------------------UPDATE FOR USERS-PROFILE-PICTURE------------------------------->




// <-------------------------------UPDATE USERS-ACCOUNT USING ADMIN(TABLE)--------------------------------->
if (isset($_POST['users_account_update_modal'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $re_password = mysqli_real_escape_string($conn, $_POST['re-password']);

        if ($password == $re_password) {
            $check_users = $conn->prepare("SELECT * FROM users WHERE user_id != ? AND username = ?");
            $check_users->bind_param("is", $id, $username);
            $check_users->execute();
            $result = $check_users->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Username already exist in records!', 'warning')</script>";
            }else {
                $hashpass = password_hash($password, PASSWORD_DEFAULT);

                $update_users = $conn->prepare("UPDATE users SET username = ?, password = ? where user_id = ?");
                $update_users->bind_param("ssi", $username, $hashpass, $id);

                if ($update_users->execute()) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully updated!', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to update data!: " . $conn->error . "', 'error')</script>";
                }
            }            
        }else {
            $_SESSION['error-alert'] = "<script>swal('Error!', 'Password not match!', 'error')</script>";
        }


    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
        //throw $th;
    }
}
// <-------------------------------UPDATE USERS-ACCOUNT USING ADMIN------------------------------->







// <-------------------------------------------UPDATE FOR GRADES-------------------------------------->
if (isset($_POST['updates_student_grades'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $quarter_one = mysqli_real_escape_string($conn, $_POST['quarter_one']);
        $quarter_two = mysqli_real_escape_string($conn, $_POST['quarter_two']);
        $quarter_three = mysqli_real_escape_string($conn, $_POST['quarter_three']);
        $quarter_four = mysqli_real_escape_string($conn, $_POST['quarter_four']);

        $update_users = $conn->prepare("UPDATE grades SET quarter_one = ?, quarter_two = ?, 
                                        quarter_three = ?, quarter_four = ? where grade_id = ?");
        $update_users->bind_param("ssssi", $quarter_one, $quarter_two, $quarter_three, $quarter_four, $id);
        
        if ($update_users->execute()) {
            $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully updated!', 'success')</script>";
        } else {
            $_SESSION['error-alert'] = "<script>swal('Error!', 'Failed to update data!: " . $conn->error . "', 'error')</script>";
        }       

    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
}
// <-------------------------------------------UPDATE FOR GRADES-------------------------------------->