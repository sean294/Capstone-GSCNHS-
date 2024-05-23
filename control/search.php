<?php

// <--------------------------------------SEARCH FOR SUBJECTS-------------------------------------->
if (isset($_POST['subject_search'])) {
    $query = $_POST['subject_id'];
    $sql = "SELECT
                subject_id as 'id',
                subjects.name as 'subject',
                subject_categories.name 'category'
            FROM 
                subjects
            LEFT JOIN
                subject_categories on subject_categories.subj_cat_id = subjects.subj_cat_id
            WHERE
                subjects.name like '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                <td class="subject_id">' . $row['id'] . '</td>
                <td>' . $row['subject'] . '</td>
                <td>' . $row['category'] . '</td>
                <td id="update_icon" style="text-align:center;"><a href="" class="edit_data"><i class="fa-solid fa-pencil"></i></a></td>
                <td id="delete_icon" class="delete_icon" style="text-align:center;"><a href="" class="delete_data"><i class="fa-solid fa-trash-can"></i></a></td>
                </tr>';
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR SUBJECTS-------------------------------------->



// <--------------------------------------SEARCH FOR USERS-------------------------------------->
if (isset($_POST['users_search'])) {
    $query = $_POST['users'];
    $sql = "SELECT
                u.user_id as 'id',
                u.username as 'username',
                emp.fullname as 'fullname',
                p.position as 'position',
                DATE_FORMAT(u.date_added, '%M %d ,%Y %h:%i:%s %p') as 'date'
            FROM 
                users u
            left join
                employees emp on emp.employee_id = u.employee_id
            left join
                positions p on p.position_id = emp.position_id
            WHERE
                u.username like '%$query%' OR emp.fullname like '%$query%' OR p.position like '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            echo '<tr>
                    <td hidden class="user_id">'.$rows['id'].'</td>
                    <td>'.$rows['username'].'</td>
                    <td>'.$rows['fullname'].'</td>
                    <td>'.$rows['position'].'</td>
                    <td>'.$rows['date'].'</td>
                    <td id="update_icon" style="text-align:center;"><a href="" class="edit_data"><i class="fa-solid fa-pencil"></i></a></td>
                    <td id="delete_icon" class="delete_icon" style="text-align:center;"><a href="" class="delete_data"><i class="fa-solid fa-trash-can"></i></i></a></td>
                </tr>';
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR USERS-------------------------------------->




// <--------------------------------------SEARCH FOR CLASSES-------------------------------------->
if (isset($_POST['class_search'])) {
    $query = $_POST['class'];
    $sql = "SELECT
                classes.class_id as 'id',
                employees.image as 'image',
                employees.fullname as 'fullname',
                employees.gender as 'gender',
                subjects.name as 'subject',
                rooms.name as 'room',   
                classes.day as 'day',
                classes.start as 'start',
                classes.end as 'end'
            FROM 
                classes 
            LEFT JOIN 
                employees on employees.employee_id = classes.employee_id
            LEFT JOIN 
                subjects on subjects.subject_id = classes.subject_id
            LEFT JOIN 
                rooms on rooms.room_id = classes.room_id
            where
                    employees.fullname like '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            echo "<tr>
                    <td hidden class='class_id'>".$rows['id']."</td>
                    <td style='text-align:center; '><img src='".$rows['image']."' alt=' id='profile' style='width:60%; height:40px;  border-radius:50px'></td>
                    <td>".$rows['fullname']."</td>
                    <td>".$rows['gender']."</td>
                    <td>".$rows['subject']."</td>
                    <td>".$rows['room']."</td>
                    <td>".$rows['day']."</td>
                    <td>".$rows['start']."</td>
                    <td>".$rows['end']."</td>
                    <td id='update_icon' class='update_icon' style='text-align:center;'><a href='' class='edit_data'><i class='fa-solid fa-pencil'></i></a></td>
                    <td id='delete_icon' class='delete_icon' style='text-align:center;'><a href='' class='delete_data'><i class='fa-solid fa-trash-can'></i></i></a></td>
                </tr>";
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR CLASSES-------------------------------------->





// <--------------------------------------SEARCH FOR ROOM-------------------------------------->
if (isset($_POST['room_search'])) {
    $query = $_POST['room'];
    $sql = "SELECT
                r.room_id as 'r_id',
                r.name as 'room',
                s.name as 'strand'
            FROM 
                rooms r
            left join
                strands s on s.strand_id = r.strand_id
            WHERE
                r.name like '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            echo "<tr>
                    <td hidden class='room_id'>".$rows['r_id']."</td>
                    <td>".$rows['room']."</td>
                    <td>".$rows['strand']."</td>
                    <td id='update_icon' style='text-align:center;'><a href='' class='edit_data'><i class='fa-solid fa-pencil'></i></a></td>
                    <td id='delete_icon' class='delete_icon' style='text-align:center;'><a href='' class='delete_data'><i class='fa-solid fa-trash-can'></i></i></a></td>
                </tr>";
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR ROOM-------------------------------------->





// <--------------------------------------SEARCH FOR EMPLOYEES-------------------------------------->
if (isset($_POST['employee_search'])) {
    $query = $_POST['employee'];
    $sql = "SELECT
                emp.employee_id as 'id',
                emp.fullname as 'fullname',
                emp.gender as 'gender',
                emp.contact_no as 'contact',
                emp.address as 'address',
                positions.position as 'position',
                emp.image as 'image'
            FROM 
                employees emp
            LEFT JOIN 
                positions on positions.position_id = emp.position_id
            where
                emp.fullname like '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            echo "<tr>
                    <td hidden class='employee_id'>".$rows['id']."</td>
                    <td style='text-align:center; '><img class='profile' src='".$rows['image']."' alt=' id='profile' style='width:60%; height:40px;  border-radius:50px'></td>
                    <td>".$rows['fullname']."</td>
                    <td>".$rows['gender']."</td>
                    <td>".$rows['contact']."</td>
                    <td>".$rows['address']."</td>
                    <td>".$rows['position']."</td>
                    <td id='update_icon' style='text-align:center;'><a href='' class='edit_data'><i class='fa-solid fa-pencil'></i></a></td>
                    <td id='delete_icon' class='delete_icon' style='text-align:center;'><a href='' class='delete_data'><i class='fa-solid fa-trash-can'></i></i></a></td>
                </tr>";
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR EMPLOYEES-------------------------------------->





// <--------------------------------------SEARCH FOR STUDENT-------------------------------------->
if (isset($_POST['student_search'])) {
    $query = $_POST['student'];
    $sql = "SELECT
                student_id,
                fullname,
                gender,
                address,
                father_name,
                mother_name,
                parent_contact,
                image as 'image'
            FROM 
                students 
            where
                fullname like '%$query%';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            echo "<tr>
                    <td hidden class='student_id'>".$rows['student_id']."</td>
                    <td style='text-align:center;'><img class='profile' src='".$rows['image']."' alt=' id='profile' style='width:80%; height:40px; border-radius:50px'></td>
                    <td>".$rows['fullname']."</td>
                    <td>".$rows['gender']."</td>
                    <td>".$rows['address']."</td>
                    <td>".$rows['father_name']."</td>
                    <td>".$rows['mother_name']."</td>
                    <td>".$rows['parent_contact']."</td>
                    <td id='update_icon' style='text-align:center;'><a href='' class='edit_data'><i class='fa-solid fa-pencil'></i></a></td>
                    <td id='delete_icon' class='delete_icon' style='text-align:center;'><a href='' class='delete_data'><i class='fa-solid fa-trash-can'></i></i></a></td>
                </tr>";
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR STUDENT-------------------------------------->




// <--------------------------------------SEARCH FOR GRADES-------------------------------------->
if (isset($_POST['grade_search'])) {
    $query = $_POST['grade'];
    $query2 = $_POST['max'];
    $query3 = $_POST['min'];


    // Define the base SQL query
    $sql = "SELECT
                g.grade_id AS grade_id,
                stud.image AS profile,
                stud.fullname AS fullname,
                stud.gender AS gender,
                subj.name AS subject,
                g.quarter_one AS quarter_one,
                g.quarter_two AS quarter_two,
                g.quarter_three AS quarter_three,
                g.quarter_four AS quarter_four,
                g.average AS average,
                g.school_year AS sy,
                s.name AS strand,
                emp.fullname AS teacher
            FROM 
                grades g
            LEFT JOIN
                classes class ON class.class_id = g.class_id
            LEFT JOIN
                subjects subj ON subj.subject_id = class.subject_id
            LEFT JOIN
                students stud ON stud.student_id = g.student_id
            LEFT JOIN
                employees emp ON emp.employee_id = class.employee_id
            LEFT JOIN
                strands s ON s.strand_id = g.strand_id
            where";

    // Check if grade filters are provided
    if (empty($query) && !empty($query2) && !empty($query3)) {
        $sql .= " (g.average >= '$query3' AND g.average <= '$query2')";
        $sql .= " ORDER BY g.average DESC";
    }
    if (!empty($query) && !empty($query2) && !empty($query3)) {
        $sql .= " (subj.name LIKE '%$query%') AND (g.average >= '$query3' AND g.average <= '$query2')";
        $sql .= " ORDER BY g.average DESC";
    }
    // Add filters for fullname and subject if provided
    if (!empty($query && empty($query2) && empty($query3))) {
        $sql .= " subj.name LIKE '%$query%' OR stud.fullname LIKE '%$query%'";
    }

    // Add ordering by average in descending order
    

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($rows = $result->fetch_assoc()) {
            echo "<tr>
                    <td hidden class='grade_id'>".$rows['grade_id']."</td>
                    <td style='text-align:center; '><img src='".$rows['profile']."' alt=' id='profile' style='width:60%; height:40px;  border-radius:50px'></td>
                    <td>".$rows['fullname']."</td>
                    <td>".$rows['gender']."</td>
                    <td>".$rows['subject']."</td>
                    <td>".$rows['quarter_one']."</td>
                    <td>".$rows['quarter_two']."</td>
                    <td>".$rows['quarter_three']."</td>
                    <td>".$rows['quarter_four']."</td>
                    <td>".$rows['average']."</td>
                    <td>".$rows['sy']."</td>
                    <td>".$rows['strand']."</td>
                    <td id='update_icon' style='text-align:center;'><a href='' class='edit_data'><i class='fa-solid fa-pencil'></i></a></td>
                    <td id='delete_icon' class='delete_icon' style='text-align:center;'><a href='' class='delete_data'><i class='fa-solid fa-trash-can'></i></i></a></td>
                </tr>";
        }
    } else {
        echo '<p>No results found</p>';
    }
}
// <--------------------------------------SEARCH FOR GRADES-------------------------------------->


// <--------------------------------------SEARCH FOR ATTENDANCE BY DATES FOR TEACHERS ONLY-------------------------------------->
if (isset($_POST['search_btn'])) {
    $date = $_POST['search_d'];
    $user = $_POST['username_id'];
    // Convert the date to the desired format
    $formatted_date = date('m/d/Y', strtotime($date));

    // Prepare the SQL query to retrieve data for the specified date
    $select_attendance = "SELECT 
                            att.id as id,
                            att.fullname as fullname,
                            att.datetime as datetime,
                            stud.employee_id as employee_id,
                            att.status as status
                        FROM 
                            attendance_history att
                        left join
                            students stud on att.fullname = stud.fullname
                        WHERE 
                            date = '$formatted_date' and employee_id = '$user'";

    // Execute the query
    $result = $conn->query($select_attendance);

    // Check if there are any records for the specified date
    if ($result->num_rows > 0) {
        // Fetch and display the records
        while ($rows = $result->fetch_assoc()) {?>
            <tr>
                <td hidden class="attendance_id"><?php echo $rows['id']; ?></td>
                <td><?php echo $rows['fullname']; ?></td>
                <td style="text-align:center"><?php echo date('F d, Y', strtotime($rows['datetime'])); ?></td>
                <td style="text-align:center"><?php echo date('H:i:m A', strtotime($rows['datetime'])); ?></td>
                <td style="text-align:center"><?php echo $rows['status']; ?></td>
            </tr>
        <?php }
    } else {
        // If no records found for the specified date
        echo "<tr><td colspan='5' style='text-align:center'>No records found for the selected date.</td></tr>";
    }
}

// <--------------------------------------SEARCH FOR ATTENDANCE BY DATES-------------------------------------->



// <--------------------------------------SEARCH FOR ATTENDANCE BY DATES FOR TEACHERS ONLY-------------------------------------->
if (isset($_POST['search_btn_admin'])) {
    $date = $_POST['search_d'];
    // Convert the date to the desired format
    $formatted_date = date('m/d/Y', strtotime($date));

    // Prepare the SQL query to retrieve data for the specified date
    $select_attendance = "SELECT 
                            *
                        FROM 
                            attendance_history att
                        WHERE 
                            date = '$formatted_date'
                        order by
                            id DESC";

    // Execute the query
    $result = $conn->query($select_attendance);

    // Check if there are any records for the specified date
    if ($result->num_rows > 0) {
        // Fetch and display the records
        while ($rows = $result->fetch_assoc()) {?>
            <tr>
                <td hidden class="attendance_id"><?php echo $rows['id']; ?></td>
                <td><?php echo $rows['fullname']; ?></td>
                <td style="text-align:center"><?php echo date('F d, Y', strtotime($rows['datetime'])); ?></td>
                <td style="text-align:center"><?php echo date('H:i:m A', strtotime($rows['datetime'])); ?></td>
                <td style="text-align:center"><?php echo $rows['status']; ?></td>
            </tr>
        <?php }
    } else {
        // If no records found for the specified date
        echo "<tr><td colspan='5' style='text-align:center'>No records found for the selected date.</td></tr>";
    }
}

// <--------------------------------------SEARCH FOR ATTENDANCE BY DATES-------------------------------------->