<?php

// <--------------------------------------MODAL UPDATE FOR CLASSES------------------------------->
if (isset($_POST['class_update'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['class_id']);
        $select = "SELECT
                        class_id as 'id',
                        employees.image as 'image',
                        employees.fullname as 'fullname',
                        employees.gender as 'gender',
                        subjects.name as 'subject',
                        rooms.name as 'room',   
                        day as 'day',
                        start as 'start',
                        end as 'end',
                        school_year as 'sy',
                        employees.employee_id as 'employee_id',
                        subjects.subject_id as 'subject_id',
                        rooms.room_id as 'room_id'
                    FROM 
                        classes 
                    LEFT JOIN 
                        employees on employees.employee_id = classes.employee_id
                    LEFT JOIN 
                        subjects on subjects.subject_id = classes.subject_id
                    LEFT JOIN 
                        rooms on rooms.room_id = classes.room_id
                    where
                        class_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $employee = generateEmployee(
                    "SELECT employee_id as 'employee_id', fullname as 'employeename' FROM employees",'employeename','employee_id',
                    $rows['employee_id']);
                
    
                $subject = generateSubject("SELECT subject_id as 'subject_id', name as 'subject' from subjects", 'subject', 
                'subject_id', $rows['subject_id']);
    
                $room = generateRoom("SELECT room_id as 'room_id', name as 'room' from rooms", 'room', 
                'room_id', $rows['room_id']);
    
                if ($rows['day'] == "MWF") {
                    $day = "<option value='MWF'>MWF</option>
                            <option value='TTH'>TTH</option>";
                }else{
                    $day = "<option value='TTH'>TTH</option>
                            <option value='MWF'>MWF</option>";
                }

                // $startDateTime = new DateTime($rows['start']);
                // $formattedStartTime = $startDateTime->format('H:i');

                // $endDateTime = new DateTime($rows['end']);
                // $formattedEndTime = $startDateTime->format('H:i');
    
                echo "
                <form action='classes.php' method='POST'>
                <h2 style:'text-align:center;'>Updating Classes Schedule</h2>
                <div class='input' hidden>
                    <label for='class' hidden>class id:</label>
                    <input type='text' name='id' id='class' value='".$rows['id']."' hidden>
                </div>
                <div class='input'>
                    <label for='employee'>Employee:</label>
    
                    <select name='employee' id='employee'>
                        $employee
                    </select>
                </div>
                <div class='input'>
                    <label for='subject'>Subject:</label>
    
                    <select name='subject' id='subject'> 
                        $subject
                    </select>
                </div>
                <div class='input'>
                    <label for='room'>Room:</label>
    
                    <select name='room' id='room'>
                        $room
                    </select>
                </div>
                <div class='input'>
                    <label for='day'>Day:</label>
                    <select name='day' id='day'>
                        $day
                    </select>
                </div>
                <div class='input'>
                    <label for='start'>Start:</label>
                    <input type='time' name='start' id='start' value='".$rows['start']."' required>
                </div>
                <div class='input'>
                    <label for='end'>End:</label>
                    <input type='time' name='end' id='end' value='".$rows['end']."' required>
                </div>
                <div class='input'>
                    <label for='school_year'>School Year:</label>
                    <select name='school_year' id='school_year' require>
                        <option value='".$rows['sy']."'>".$rows['sy']."</option>
                    </select>
                </div>
                <div class='btn'>
                    <button type='submit' name='class_Update'>Update</button>
                </div>
            </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "')</script>";
    }

}
function generateEmployee($query, $textValue, $valueColumn, $selectedValue) {
    global $conn;

    $result = $conn->query($query);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $text = $row[$textValue]; // Use a different variable name to avoid overwriting the function parameter
            $selected = ($row[$valueColumn] == $selectedValue) ? "selected" : "";
            $options .= "<option value='{$row[$valueColumn]}' {$selected}>{$text}</option>";
        }
    }

    return $options;
}

function generateSubject($query, $textValue, $valueColumn, $selectedValue){
    global $conn;

    $result = $conn->query($query);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $text = $row[$textValue]; // Use a different variable name to avoid overwriting the function parameter
            $selected = ($row[$valueColumn] == $selectedValue) ? "selected" : "";
            $options .= "<option value='{$row[$valueColumn]}' {$selected}>{$text}</option>";
        }
    }

    return $options;
}
function generateRoom($query, $textValue, $valueColumn, $selectedValue){
    global $conn;

    $result = $conn->query($query);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $text = $row[$textValue]; // Use a different variable name to avoid overwriting the function parameter
            $selected = ($row[$valueColumn] == $selectedValue) ? "selected" : "";
            $options .= "<option value='{$row[$valueColumn]}' {$selected}>{$text}</option>";
        }
    }

    return $options;
}
// <--------------------------------------MODAL UPDATE FOR CLASSES------------------------------->




// <--------------------------------------MODAL UPDATE FOR EMPLOYEES------------------------------->
if (isset($_POST['click_update'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $select = "SELECT
                        employee_id as 'id',
                        fullname as 'fullname',
                        gender as 'gender',
                        contact_no as 'contact',
                        address as 'address',
                        positions.position as 'position',
                        image as 'image',
                        positions.position_id as 'position_id'
                    FROM 
                        employees
                    LEFT JOIN 
                        positions on positions.position_id = employees.position_id
                    WHERE 
                        employee_id = '$id'";
        $result = $conn->query($select);

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $position = generatePositions("SELECT position_id ,position from positions", 'position', 'position_id', 
                $rows['position_id']);

                if ($rows['gender'] == "Male") {
                    $gender = "<option value='Male'>Male</option>
                    <option value='Female'>Female</option>";
                }else {
                    $gender =  "<option value='Female'>Female</option>
                    <option value='Male'>Male</option>";
                }

                echo "
                <form action='employee.php' method='POST' enctype='multipart/form-data'>
                <h2>Employee's Update form</h2>
                <div class='input' hidden>
                    <label for='fname' hidden>UserId:</label>
                    <input type='text' name='id' value='".$rows['id']."' readonly hidden>
                </div>
                <div class='input'>
                    <label for='fullname'>Firstname:</label>
                    <input type='text' name='fullname' value='".$rows['fullname']."' required>
                </div>
                <div class='input'>
                    <label for='gender'>Gender:</label>
                    <select name='gender' id='gender'>
                        $gender
                    </select>
                </div>
                <div class='input'>
                    <label for='contact'>Contact No:</label>
                    <input type='text' name='contact' id='contact' value='".$rows['contact']."' required>
                </div>
                <div class='input'>
                    <label for='Address'>Address:</label>
                    <textarea name='address' id='Address' cols='17' rows='5' required>".$rows['address']."</textarea>
                </div>
                <div class='input'>
                    <label for='position'>Position:</label>
    
                    <select name='position' id='position'>
                        $position
                    </select>
                </div>
                <div class='btn'>
                    <button type='submit' name='employee_update'>Update</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
function generatePositions($query, $textValue, $valueColumn, $selectedValue){
    global $conn;

    $result = $conn->query($query);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $text = $row[$textValue]; // Use a different variable name to avoid overwriting the function parameter
            $selected = ($row[$valueColumn] == $selectedValue) ? "selected" : "";
            $options .= "<option value='$row[$valueColumn]' {$selected}>{$text}</option>";
        }
    }

    return $options;
}
// <--------------------------------------MODAL UPDATE FOR EMPLOYEES------------------------------->




// <--------------------------------------MODAL UPDATE FOR EMPLOYEES-PROFILE------------------------------->
if (isset($_POST['employee_update_profile'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $select_profile = $conn->prepare("SELECT employee_id, fullname, image from employees where employee_id = ?");
        $select_profile->bind_param("i", $id);
        $select_profile->execute();
        $result = $select_profile->get_result();

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='employee.php' method='POST' enctype='multipart/form-data'>
                <h2>Profile Update form</h2>
                <div class='input' hidden>
                    <label for='fname' hidden>UserId:</label>
                    <input type='text' name='id' value='".$rows['employee_id']."' readonly hidden>
                </div>
                <div class='input' hidden>
                    <label for='fullname' hidden>Firstname:</label>
                    <input type='text' name='fullname' value='".$rows['fullname']."' hidden>
                </div>
                <div class='frame input' id='preview' style='width:190px;height:150px;border:2px solid black;text-align:center;position:relative;border-radius:5px'><img style='width:98%;height:98%;margin:auto;border-radius:5px' src='".$rows['image']."' alt='Profile'></div>
                <div class='input'>
                    <label for='image'>Select Profile::</label>
                    <input type='file' name='image_file' id='image_file' required>
                </div>
                <div class='btn'>
                    <button type='submit' name='emp_update_picture'>Update</button>
                </div>
                </form>";
            }

        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL UPDATE FOR EMPLOYEES-PROFILE------------------------------->




// <--------------------------------------MODAL UPDATE FOR ROOM------------------------------->
if (isset($_POST['room_update'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['room_id']);
        $select = "SELECT
                        r.room_id as 'r_id',
                        r.name as 'room',
                        s.name as 'strand',
                        s.strand_id as 'strand_id'
                    FROM 
                        rooms r
                    LEFT JOIN
                        strands s ON s.strand_id = r.strand_id
                    WHERE
                        room_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $strand = generateStrand("SELECT strand_id, name from strands", 'name', 'strand_id', $rows['strand_id']);
                echo "
                <form action='room.php' method='POST'>
                <h2>Room Update form</h2>
                    <div class='input' hidden>
                        <label for='id' hidden>SubjectId:</label>
                        <input type='text' name='id' value='".$rows['r_id']."' hidden>
                    </div>
                    <div class='input'>
                        <label for='room'>Room Name:</label><br>
                        <input type='text' name='room' id='room' value='".$rows['room']."' required>
                    </div>
                    <div class='input'>
                        <label for='strand'>Strand:</label>
    
                        <select name='strand' id='strand' required>
                            $strand
                        </select>
                    </div>
                    <div class='btn'>
                        <button type='submit' name='rooms_update'>Update</button>
                    </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
function generateStrand($query, $textValue, $valueColumn, $selectedValue){
    global $conn;

    $result = $conn->query($query);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $text = $row[$textValue]; // Use a different variable name to avoid overwriting the function parameter
            $selected = ($row[$valueColumn] == $selectedValue) ? "selected" : "";
            $options .= "<option value='$row[$valueColumn]' {$selected}>{$text}</option>";
        }
    }

    return $options;
}
// <--------------------------------------MODAL UPDATE FOR ROOM------------------------------->





// <--------------------------------------MODAL UPDATE FOR STUDENTS------------------------------->
if (isset($_POST['student_update'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $select = "SELECT
                        student_id,
                        fullname,
                        gender,
                        address,
                        father_name,
                        mother_name,
                        parent_contact,
                        contact_no,
                        image
                    FROM 
                        students 
                    WHERE 
                        student_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {

                if ($rows['gender'] == "Male") {
                    $gender = "<option value='Male'>Male</option>
                    <option value='Female'>Female</option>";
                }else {
                    $gender =  "<option value='Female'>Female</option>
                    <option value='Male'>Male</option>";
                }
    
                echo "
                <form action='student.php' method='POST' enctype='multipart/form-data'>
                <h2>Student Update form</h2>
                <div class='input' hidden>
                    <label for='id' hidden>UserId:</label>
                    <input type='text' name='id' value='".$rows['student_id']."' readonly hidden>
                </div>
                <div class='input'>
                    <label for='fullname'>Firstname:</label>
                    <input type='text' name='fullname' value='".$rows['fullname']."' required>
                </div>
                <div class='input'>
                    <label for='gender'>Gender:</label>
                    <select name='gender' id='gender'>
                        $gender
                    </select>
                </div>
                <div class='input'>
                    <label for='contact'>Contact No:</label>
                    
                    <input type='text' name='contact' id='contact' value='".$rows['contact_no']."' required>
                </div>
                <div class='input'>
                    <label for='Address'>Address:</label>
                    <textarea name='address' id='Address' cols='17' rows='5' required>".$rows['address']."</textarea>
                </div>
                <div class='input'>
                    <label for='father_name'>Father's name:</label>
                    <input type='text' name='father_name' required value='".$rows['father_name']."' required>
                </div>
                <div class='input'>
                    <label for='mother_name'>Mother's name:</label>
                    <input type='text' name='mother_name' required value='".$rows['mother_name']."' required>
                </div>
                <div class='input'>
                    <label for='parent_contact'>Parent Contact:</label>
                    <input type='text' name='parent_contact' id='parent_contact' required value='".$rows['parent_contact']."' required>
                </div>
                <div class='btn'>
                    <button type='submit' name='students_update'>Update</button>
                </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
// <--------------------------------------MODAL UPDATE FOR STUDENTS------------------------------->





// <--------------------------------------MODAL UPDATE FOR STUDENTS-PROFILE------------------------------->
if (isset($_POST['student_update_profile'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $select_profile = $conn->prepare("SELECT student_id, fullname, image from students where student_id = ?");
        $select_profile->bind_param("i", $id);
        $select_profile->execute();
        $result = $select_profile->get_result();

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form action='student.php' method='POST' enctype='multipart/form-data'>
                <h2>Profile Update form</h2>
                <div class='input' hidden>
                    <label for='fname' hidden>UserId:</label>
                    <input type='text' name='id' value='".$rows['student_id']."' readonly hidden>
                </div>
                <div class='input' hidden>
                    <label for='fullname' hidden>Firstname:</label>
                    <input type='text' name='fullname' value='".$rows['fullname']."' hidden>
                </div>
                <div class='frame input' id='preview' style='width:190px;height:150px;border:2px solid black;text-align:center;position:relative;border-radius:5px'><img style='width:98%;height:98%;margin:auto;border-radius:5px' src='".$rows['image']."' alt='Profile'></div>
                <div class='input'>
                    <label for='image'>Select Profile::</label>
                    <input type='file' name='image_file' id='image_file' required>
                </div>
                <div class='btn'>
                    <button type='submit' name='stud_update_picture'>Update</button>
                </div>
                </form>";
            }

        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL UPDATE FOR STUDENTS-PROFILE------------------------------->






// <--------------------------------------MODAL UPDATE FOR SUBJECTS------------------------------->
if (isset($_POST['subject_update'])){
    try {
        $id = mysqli_real_escape_string($conn, $_POST['subject_id']);
        $select = "SELECT
                        subject_id as 'id',
                        subjects.name as 'subject',
                        subject_categories.name as 'category',
                        subject_categories.subj_cat_id as 'category_id'
                    FROM 
                        subjects 
                    LEFT JOIN 
                        subject_categories on subject_categories.subj_cat_id = subjects.subj_cat_id
                    WHERE 
                    subject_id = '$id'";
        $result = $conn->query($select);
    
        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $category = generateCategory("SELECT subj_cat_id, name from subject_categories", 'name', 'subj_cat_id', $rows['category_id']);
                echo "
                <form action='subject.php' method='POST' enctype='multipart/form-data'>
                <h2>Subjects Update Form</h2>
                    <div class='input' hidden>
                        <label for='fname' hidden>SubjectId:</label>
                        <input type='text' name='id' value='".$rows['id']."' hidden>
                    </div>
                    <div class='input'>
                        <label for='subject'>Subject Name:</label><br>
                        <input type='text' required name='subject' id='subject' style='' value='".$rows['subject']."'>
                    </div>
                    <div class='input'>
                        <label for='category'>Subject Category:</label>
    
                        <select name='category' id='category'>
                            $category
                        </select>
                    </div>
                    <div class='btn'>
                        <button type='submit' name='subjects_update'>Update</button>
                    </div>
                </form>";
            }
        }else{
    
        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }

}
function generateCategory($query, $textValue, $valueColumn, $selectedValue){
    global $conn;

    $result = $conn->query($query);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $text = $row[$textValue]; // Use a different variable name to avoid overwriting the function parameter
            $selected = ($row[$valueColumn] == $selectedValue) ? "selected" : "";
            $options .= "<option value='$row[$valueColumn]}' {$selected}>{$text}</option>";
        }
    }

    return $options;
}
// <--------------------------------------MODAL UPDATE FOR SUBJECTS------------------------------->





// <--------------------------------------MODAL UPDATE FOR USERS------------------------------->
if (isset($_POST['user_update'])) {
    try {
        $username = mysqli_real_escape_string($conn, $_POST['user_name']);


        $select = $conn->prepare("SELECT 
                                        employee_id as 'id',
                                        fullname,
                                        gender,
                                        contact_no,
                                        address,
                                        p.position as 'position',
                                        p.position_id as 'p_id'
                                    FROM 
                                        employees
                                    LEFT JOIN
                                        positions p on p.position_id = employees.position_id
                                    where 
                                        fullname = ?");
        $select->bind_param("s", $username);
        $select->execute();
        $result = $select->get_result();

        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();

            if ($rows['gender'] == "Male") {
                $gender = "<option value='Male'>Male</option>
                <option value='Female'>Female</option>";
            }else {
                $gender =  "<option value='Female'>Female</option>
                <option value='Male'>Male</option>";
            }

            echo "
            <form action='' method='POST' enctype='multipart/form-data'>
            <h2>Update Personal Information</h2>
            <div class='input' hidden>
                <label for='fname' hidden>UserId:</label>
                <input type='text' name='id' value='".$rows['id']."' readonly hidden>
            </div>
            <div class='input'>
                <label for='fullname'>Firstname:</label>
                <input type='text' name='fullname' value='".$rows['fullname']."' required>
            </div>
            <div class='input'>
                <label for='gender'>Gender:</label>
                <select name='gender' id='gender'>
                    $gender
                </select>
            </div>
            <div class='input'>
                <label for='contact'>Contact No:</label>
                <input type='text' name='contact' id='contact' value='".$rows['contact_no']."' required>
            </div>
            <div class='input'>
                <label for='Address'>Address:</label>
                <textarea name='address' id='Address' cols='17' rows='5' required>".$rows['address']."</textarea>
            </div>
            <div class='input'>
                <label for='position'>Position:</label>

                <select name='position' id='position'>
                    <option value=".$rows['p_id'].">".$rows['position']."</option>
                </select>
            </div>
            <div class='btn'>
                <button type='submit' name='users_update_profile_information'>Update</button>
            </div>
            </form>";
        }

    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
    
}
// <--------------------------------------MODAL UPDATE FOR USERS------------------------------->






// <--------------------------------------MODAL UPDATE FOR USERS-PROFILE------------------------------->
if (isset($_POST['users_profile'])) {
    try {
        $fullname = mysqli_real_escape_string($conn, $_POST['user_fullname']);
        $select_profile = $conn->prepare("SELECT employee_id, fullname, image from employees where fullname = ?");
        $select_profile->bind_param("s", $fullname);
        $select_profile->execute();
        $result = $select_profile->get_result();

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                echo "
                <form method='POST' enctype='multipart/form-data'>
                <h2>Update Personal Picture</h2>
                    <div class='input' hidden>
                        <label for='fname' hidden>UserId:</label>
                        <input type='text' name='id' value='".$rows['employee_id']."' readonly hidden>
                    </div>
                    <div class='input' hidden>
                        <label for='fullname' hidden>Firstname:</label>
                        <input type='text' name='fullname' value='".$rows['fullname']."' hidden>
                    </div>
                    <div class='frame input' id='preview' style='width:190px;height:150px;border:2px solid black;text-align:center;position:relative;border-radius:5px'><img style='width:98%;height:98%;margin:auto;border-radius:5px' src='".$rows['image']."' alt='Profile'></div>
                    <div class='input'>
                        <label for='image'>Select Profile::</label>
                        <input type='file' name='image_file' id='image_file' required>
                    </div>
                    <div class='btn'>
                        <button type='submit' name='update_users_profile'>Update</button>
                    </div>
                </form>";
            }

        }
    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
}
// <--------------------------------------MODAL UPDATE FOR USERS-PROFILE------------------------------->






// <--------------------------------------MODAL UPDATE FOR ACCOUNTS------------------------------->
if (isset($_POST['user_account'])) {
    try {
        $username = mysqli_real_escape_string($conn, $_POST['user_name']);

        $select = $conn->prepare("SELECT
                                        user_id,
                                        username,
                                        password
                                    FROM 
                                        users
                                    where 
                                        username = ?");
        $select->bind_param("s", $username);
        $select->execute();
        $result = $select->get_result();

        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();

            echo "
            <form action='' method='POST' enctype='multipart/form-data'>
            <h2>Update Personal account</h2>
            <div class='input' hidden>
                <label for='id' hidden>UserId:</label>
                <input type='text' name='id' id='u_id' value='".$rows['user_id']."' readonly hidden>
            </div>
            <div class='input'>
                <label for='username'>Username:</label>
                <input type='text' name='username' id='users' value='".$rows['username']."' required>
            </div>
            <div class='input'>
                <label for='password'>Password:</label>
                <input type='password' name='password' id='pass' value='' required>
            </div>
            <div class='btn'>
                <button type='submit' name='account_update'>Update</button>
            </div>
            </form>";
        }

    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    }
    
}
// <--------------------------------------MODAL UPDATE FOR ACCOUNTS------------------------------->



// <----------------------MODAL UPDATE FOR THE ACCOUNTS OF THE USERS WITH THE USE OF ADMINS--------------------->
if (isset($_POST['user_account_update'])) {
    try {
        $username = mysqli_real_escape_string($conn, $_POST['user_id']);

        $select = $conn->prepare("SELECT
                                        user_id,
                                        username,
                                        password
                                    FROM 
                                        users
                                    where 
                                        user_id = ?");
        $select->bind_param("s", $username);
        $select->execute();
        $result = $select->get_result();

        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();

            echo "
            <form action='users.php' method='POST'>
                <h2>Update Personal account</h2>
                <div class='input' hidden>
                    <label for='id' hidden>UserId:</label>
                    <input type='text' name='id' id='id' value='".$rows['user_id']."' readonly hidden>
                </div>
                <div class='input'>
                    <label for='username'>Username:</label>
                    <input type='text' name='username' id='username' value='".$rows['username']."' required>
                </div>
                <div class='input'>
                    <label for='password'>Password:</label>
                    <input type='password' name='password' id='password' value='' required>
                </div>
                <div class='input'>
                    <label for='password'>Re-Password:</label>
                    <input type='password' name='re-password' id='password' value='' required>
                </div>
                <div class='btn'>
                    <button type='submit' name='users_account_update_modal'>Update</button>
                </div>
            </form>";
        }

    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    } 
}
// <----------------------MODAL UPDATE FOR THE ACCOUNTS OF THE USERS WITH THE USE OF ADMINS--------------------->





// <-------------------------------------------MODAL UPDATE FOR GRADES-------------------------------------->
if (isset($_POST['grade_update'])) {
    try {
        $id = mysqli_real_escape_string($conn, $_POST['grade_id']);

        $select = $conn->prepare("SELECT
                                    g.grade_id as 'grade_id',
                                    stud.fullname as 'fullname',
                                    subj.name as 'subject',
                                    g.quarter_one as 'quarter_one',
                                    g.quarter_two as 'quarter_two',
                                    g.quarter_three as 'quarter_three',
                                    g.quarter_four as 'quarter_four'
                                from 
                                    grades g
                                LEFT JOIN
                                    classes class on class.class_id = g.class_id
                                left join
                                    subjects subj on subj.subject_id = class.subject_id
                                left join
                                    students stud on stud.student_id = g.student_id
                                
                                where 
                                    g.grade_id = ?");
        $select->bind_param("i", $id);
        $select->execute();
        $result = $select->get_result();

        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();

            echo "
            <form action='grades.php' method='POST'>
            <h2>Update Student Grade</h2>
            <div class='input' hidden>
                <label for='id' hidden>UserId:</label>
                <input type='text' name='id' id='id' value='".$rows['grade_id']."' readonly hidden>
            </div>
            <div class='input'>
                <label for='fullname'>Fullname:</label>
                <input readonly type='text' name='fullname' id='fullname' value='".$rows['fullname']."' required>
            </div>
            <div class='input'>
                <label for='subject'>Subjects:</label>
                <input readonly type='text' name='subject' id='subject' value='".$rows['subject']."' required>
            </div>
            <div class='input'>
                <label for='quarter_one'>First Quarter:</label>
                <input type='number' min='0' name='quarter_one' class='quarter_one' value='".$rows['quarter_one']."' required>
            </div>
            <div class='input'>
                <label for='quarter_two'>Second Quarter:</label>
                <input type='number' min='0' name='quarter_two' class='quarter_two' value='".$rows['quarter_two']."' required>
            </div>
            <div class='input'>
                <label for='quarter_three'>Third Quarter:</label>
                <input type='number' min='0' name='quarter_three' class='quarter_three' value='".$rows['quarter_three']."' required>
            </div>
            <div class='input'>
                <label for='quarter_four'>Fourth Quarter:</label>
                <input type='number' min='0' name='quarter_four' class='quarter_four' value='".$rows['quarter_four']."' required>
            </div>
            <div class='btn'>
                <button type='submit' name='updates_student_grades'>Update</button>
            </div>
            </form>";
        }

    } catch (Exception $error) {
        echo "<script>alert('Error: " . $error->getMessage() . "');</script>";
    } 
}
// <-------------------------------------------MODAL UPDATE FOR GRADES-------------------------------------->