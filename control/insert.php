<?php

// $allowedTimeAm = new Datetime('8:00');
// $allowedTimePm = new Datetime('17:00');

// $startExist = new Datetime('9:00');
// $endExist = new Datetime('11:00');

// $start = new Datetime('8:00');
// $end = new Datetime('9:00');

// $startExist = new Datetime('9:06');
// $check = new Datetime('9:05');

// $interval = $start->diff($end);
// $hoursDifference = $interval->h;
// $minutesDifference = $interval->i;

// $totalMinutesDifference = $hoursDifference * 60 + $minutesDifference;

// if ($startExist <= $check) {
//     echo "Already Mark!";
// } else {
//     echo "Successfully Mark";
// }


// if ($endExist > $start && $startExist < $end) {
//     echo "Schedules overlap <br>";
// } else {
//     echo "Schedules do not overlap <br>";
// }

// if ($start < $allowedTimeAm) {
//     echo "Allowed time is Start at 8AM";
// }elseif ($end > $allowedTimePm) {
//     echo "Allowed time is Ends at 5PM";
// }elseif ($totalMinutesDifference < 60){
//     echo "Should no less than an hour";
// }elseif ($totalMinutesDifference > 120){
//     echo "Time should no exceed 2hours";
// }else{
//     echo "not overlapp";
// }



// <--------------------------------------this is for classes------------------------------->
if (isset($_POST['class_submit'])) {
    try {
        $employee = mysqli_real_escape_string($conn, $_POST['employee']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $room = mysqli_real_escape_string($conn, $_POST['room']);
        $day = mysqli_real_escape_string($conn, $_POST['day']);
        $startDateTime = mysqli_real_escape_string($conn, $_POST['start']);
        $endDateTime = mysqli_real_escape_string($conn, $_POST['end']);
        // $start = new DateTime($conn, $_POST['start']);
        // $end =  new DateTime($conn, $_POST['end']);
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
            $check = $conn->prepare("SELECT * FROM classes WHERE employee_id = ? AND subject_id = ? AND room_id = ? AND day = ? AND start = ? AND end = ?");
            $check->bind_param("iiisss", $employee, $subject, $room, $day, $startFormatted , $endFormatted);
            $check->execute();
            $result_check = $check->get_result();
    
                // check if the subjects and rooms and times are overlapping
            $second_check = $conn->prepare("SELECT * FROM classes WHERE employee_id = ? AND subject_id = ? AND room_id = ? AND day = ? AND (end > ?) AND (start < ?)");
            $second_check->bind_param("iiisss", $employee, $subject, $room, $day, $startFormatted , $endFormatted);
            $second_check->execute();
            $result_second_check = $second_check->get_result();

                // check if classes have the same class as the room
            $third_check = $conn->prepare("SELECT * FROM classes WHERE subject_id = ? AND room_id = ?");
            $third_check->bind_param("ii",$subject, $room);
            $third_check->execute();
            $result_third_check = $third_check->get_result();

                // check if there is time overlapping
            $fourth_check = $conn->prepare("SELECT * FROM classes WHERE room_id = ? AND day = ? AND (end > ?) AND (start < ?)");
            $fourth_check->bind_param("isss", $room, $day, $startFormatted , $endFormatted);
            $fourth_check->execute();
            $result_fourth_check = $fourth_check->get_result();

            // check if there is a class where the same teacher the same time as well as the same day but different room
            $fifth_check = $conn->prepare("SELECT * FROM classes WHERE employee_id = ? AND subject_id = ? and day = ? AND (end > ?) AND (start < ?)");
            $fifth_check->bind_param("iisss",$employee, $subject, $day, $startFormatted , $endFormatted);
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
            }else{
    
                $insert = $conn->prepare("INSERT INTO classes (employee_id, subject_id, room_id, day, start, end, school_year) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insert->bind_param("iiissss", $employee, $subject, $room, $day, $startFormatted , $endFormatted, $sy);
                $result_insert = $insert->execute();
    
                if ($result_insert) {
                    $_SESSION['success-alert'] = "<script>swal('Data successfully added!', '', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Data Failed to insert!', '$conn->error', 'error')</script>";
                }
            }
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "')</script>";
    }
}
// <--------------------------------------this is for classes------------------------------->



// <--------------------------------------THIS IS FOR EMPLOTEES------------------------------->
if (isset($_POST['employee_submit'])) {
    try {
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
            if ($_FILES['image_file']['name'] != '') {
                // Define allowed extensions
                $allowed_ext = array("jpg", "png", "jpeg");
                
                // Get file extension
                $file_name_parts = explode('.', $_FILES['image_file']['name']);
                $ext = end($file_name_parts);
        
                if (in_array($ext, $allowed_ext)) {
                    if ($_FILES['image_file']['size'] < 200000) {
                        // Generate a unique filename based on date and time
                        $datetime = date("YmdHis");
                        $name = $fullname.'.' . $ext;
                        $path = "../images/" . $name;
         
                        $check = $conn->prepare("SELECT * FROM employees WHERE fullname = ?");
                        $check->bind_param("s", $fullname);
                        $check->execute();
                        $result = $check->get_result();
    
                        $second_check = $conn->prepare("SELECT * FROM employees WHERE contact_no = ?");
                        $second_check->bind_param("s", $contact);
                        $second_check->execute();
                        $second_result = $second_check->get_result();
                    
                        if ($result->num_rows > 0) {
                            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Name already exist for other records!', 'warning')</script>";
                        }elseif ($second_result->num_rows > 0) {
                            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Contact number already exist for other records!', 'warning')</script>";
                        }else{
                        $insert_employee = "INSERT INTO employees(employee_id, fullname, gender, contact_no, address, position_id, image) 
                        VALUES ('$datetime', '$fullname', '$gender', '$contact', '$address', '$position', '$path')";
    
                            if ($conn->query($insert_employee)) {
                                $_SESSION['success-alert'] = "<script>swal('Data successfully added!', '', 'success')</script>";
                                // Move uploaded file to the folder
                                move_uploaded_file($_FILES['image_file']['tmp_name'], $path);
                            } else {
                                $_SESSION['error-alert'] = "<script>swal('Data Failed to insert!', '$conn->error', 'error')</script>";
                            }
                        }
    
                    } else {
                        $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Image File is too big!', 'warning')</script>";
                    }
                } else {
                    $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Invalid image file!', 'warning')</script>";
                }
            } else {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Please select file!', 'warning')</script>";
            }
        }
    
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------THIS IS FOR EMPLOTEES------------------------------->




// <--------------------------------------THIS IS FOR ROOMS------------------------------->
if (isset($_POST['room_submit'])){
    try {
        $room = mysqli_real_escape_string($conn, $_POST['room']);
        $strand = mysqli_real_escape_string($conn, $_POST['strand']);
    
        if (!preg_match('/^[A-Z a-z 0-9]+$/', $room)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters and Numbers only ".$room."!', 'warning')</script>";
        }else{
            $check = $conn->prepare("SELECT * from rooms where name = ?");
            $check->bind_param("s", $room);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Subject already exist!', 'warning')</script>";
            }else{
                $insert_room = "INSERT INTO rooms(name, strand_id) values('$room', '$strand')";
    
                if ($conn->query($insert_room)) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully added!', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Something Error!', 'error')</script>";
                }
            }
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }

}
// <--------------------------------------THIS IS FOR EMPLOTEES------------------------------->




// <--------------------------------------THIS IS FOR STUDENTS------------------------------->
if (isset($_POST['student_submit'])) {
    try {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $father = mysqli_real_escape_string($conn, $_POST['father_name']);
        $mother = mysqli_real_escape_string($conn, $_POST['mother_name']);
        $parent_contact = mysqli_real_escape_string($conn, $_POST['parent_contact']);
        $teacher = mysqli_real_escape_string($conn, $_POST['teacher']);
    
        if (!preg_match('/^[A-Z a-z]+$/', $fullname)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only $fullname', 'warning')</script>";
        }elseif (!preg_match('/^[A-Z a-z]+$/', $father)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only $father', 'warning')</script>";
        }elseif (!preg_match('/^[A-Z a-z]+$/', $mother)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters only $mother', 'warning')</script>";
        }elseif (!preg_match('/^[0-9\+]+$/', $parent_contact)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Numbers and special symbols only $parent_contact', 'warning')</script>";
        }elseif (!preg_match('/^[0-9\+]+$/', $contact)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Numbers and special symbols only $contact', 'warning')</script>";
        }else {
            if ($_FILES['image_file']['name'] != '') {
    
                $allowed_ext = array("jpg", "png", "jpeg");
                $file_name_parts = explode('.', $_FILES['image_file']['name']);
                $ext = end($file_name_parts);
        
                if (in_array($ext, $allowed_ext)) {
                    if ($_FILES['image_file']['size'] < 200000) {
    
                        $datetime = date("YmdHis");
                        // $name = $fullname. ' '. ' '.$mname. ' '.$lname . '.' . $ext;
                        $name = $fullname. '.' . $ext;
                        $path = "../images/" . $name;
    
                        move_uploaded_file($_FILES['image_file']['tmp_name'], $path);
    
                        $check = $conn->prepare("SELECT * FROM students WHERE fullname = ? AND contact_no = ?");
                        $check->bind_param("ss", $fullname, $contact);
                        $check->execute();
                        $result = $check->get_result();

                        $contact_check = $conn->prepare("SELECT * FROM students WHERE contact_no = ?");
                        $contact_check->bind_param("s", $contact);
                        $contact_check->execute();
                        $contact_check_result = $contact_check->get_result();

                        $parent_contact_check = $conn->prepare("SELECT * FROM students WHERE parent_contact = ?");
                        $parent_contact_check->bind_param("s", $contact);
                        $parent_contact_check->execute();
                        $parent_result = $parent_contact_check->get_result();
    
                        if ($result->num_rows > 0){
                            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Name or Contact Number already exist for other records! $fullname and $contact', 'warning')</script>";
                        }elseif ($contact_check_result->num_rows > 0){
                            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Contact number aleary exist in records! $contact', 'warning')</script>";
                        }elseif ($parent_result->num_rows > 0){
                            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Parent contact already exist! $parent_contact', 'warning')</script>";
                        }else{
                            $insert_student = "INSERT INTO students(student_id, fullname, gender, contact_no, address, image, father_name, mother_name, parent_contact, employee_id) 
                            VALUES ('$datetime', '$fullname', '$gender', '$contact', '$address', '$path', '$father', '$mother',  '$parent_contact', '$teacher')";
        
                            if ($conn->query($insert_student)) {
                                $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully added!', 'success')</script>";
                            } else {
                                $_SESSION['error-alert'] = "<script>swal('Error!', 'Error: " . $conn->error . "', 'error')</script>";
                            }
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
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
    
}
// <--------------------------------------THIS IS FOR STUDENTS------------------------------->





// <--------------------------------------THIS IS FOR SUBJECTS------------------------------->
if (isset($_POST['subject_submit'])){
    try {
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
    
        if (!preg_match('/^[A-Z a-z 0-9\'\-]+$/', $subject)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Letters and Numbers only ".$subject."', 'warning')</script>";
        }else{
            $check = $conn->prepare("SELECT * from subjects where name = ?");
            $check->bind_param("s", $subject);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Subject already exist!', 'warning')</script>";
            }else{
                $insert_subject = "INSERT INTO subjects(name, subj_cat_id) values('$subject', '$category')";
    
                if ($conn->query($insert_subject)) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully added!', 'success')</script>";
                } else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Error: " . $conn->error . "', 'error')</script>";
                }
                
            }
        }
    } catch (Exception $error) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
}
// <--------------------------------------THIS IS FOR SUBJECTS------------------------------->





// <--------------------------------------THIS IS FOR USERS------------------------------->
if (isset($_POST['signup'])) {
    try {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = "123"; 
        $teacher = mysqli_real_escape_string($conn, $_POST['teacher']);

        if (!preg_match('/^[A-Za-z0-9]+$/', $username)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Username should only consist of numbers and letters! . $username', 'warning')</script>";
        } elseif (!preg_match('/^[A-Za-z0-9]+$/', $password)) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Password should only consist of letters and numbers! ', 'warning')</script>";
        } else {
            $check = $conn->prepare("SELECT * FROM users where username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $result = $check->get_result();

            $second_check = $conn->prepare("SELECT * FROM users where employee_id = ?");
            $second_check->bind_param("i", $teacher);
            $second_check->execute();
            $second_result = $second_check->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Username already exists! $username', 'warning')</script>";
            } elseif ($second_result->num_rows > 0) {
                $rows = $second_result->fetch_assoc();
                $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Teacher already exists! ". $rows['fullname']."', 'warning')</script>";
            } else {
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                $insert = $conn->prepare("INSERT INTO users(username, password, employee_id) values(?, ?, ?)");
                $insert->bind_param("ssi", $username, $hashPassword, $teacher);
                $insert_result = $insert->execute();

                if ($insert_result) {
                    $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully added!', 'success')</script>";
                }else {
                    $_SESSION['error-alert'] = "<script>swal('Error!', 'Error: " . $conn->error . "', 'error')</script>";
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['error-alert'] = "<script>swal('Error!', ''Error: " . $error->getMessage() . "!', 'error')</script>";
    }
}
// <--------------------------------------THIS IS FOR USERS------------------------------->



// <--------------------------------------THIS IS FOR ADDING GRADES------------------------------->
if (isset($_POST['add_grades'])) {
    $student = $_POST['student'];
    $class = $_POST['class'];
    $quarter_one = $_POST['quarter_one'];
    $quarter_two = $_POST['quarter_two'];
    $quarter_three = $_POST['quarter_three'];
    $quarter_four = $_POST['quarter_four'];
    $sy = $_POST['sy'];
    $strand = $_POST['strand'];

    foreach ($student as $key => $students) {
        
        $quarter_one_grade = $quarter_one[$key];
        $quarter_two_grade = $quarter_two[$key];
        $quarter_three_grade = $quarter_three[$key];
        $quarter_four_grade = $quarter_four[$key];

        if ($quarter_one_grade != 0 && $quarter_two_grade != 0 && $quarter_three_grade != 0 && $quarter_four_grade != 0){
            
            $average = ($quarter_one_grade + $quarter_two_grade + $quarter_three_grade + $quarter_four_grade) / 4;
        }

        // echo $quarter_one_grade."".$quarter_two_grade."".$quarter_three_grade."".$quarter_four_grade."".$average;

        $check_grades = $conn->prepare("SELECT * FROM grades where student_id = ? and class_id = ?");
        $check_grades->bind_param("ii", $students, $class[$key]);
        $check_grades->execute();
        $result = $check_grades->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['warning-alert'] = "<script>swal('Warning!', 'Some of the Data is already existed', 'warning')</script>";
        }else {
            // Insert data into the database
            $insert_grades = $conn->prepare("INSERT INTO grades (student_id, class_id, quarter_one, quarter_two, 
                                    quarter_three, quarter_four, average, school_year, strand_id) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_grades->bind_param("iissssssi", $students, $class[$key], $quarter_one_grade, $quarter_two_grade, 
                                        $quarter_three_grade, $quarter_four_grade, $average, $sy[$key], $strand[$key]);
            $grade_result = $insert_grades->execute();

            if ($grade_result) {
                $_SESSION['success-alert'] = "<script>swal('Success!', 'Data successfully added!', 'success')</script>";
            }else{
                $_SESSION['error-alert'] = "<script>swal('Error!', 'Insert Data Failed!: " . $conn->error . "', 'error')</script>";
            }
        }

    }

}
// <--------------------------------------THIS IS FOR ADDING GRADES------------------------------->






