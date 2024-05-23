<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Grades</h1>
    <?php include("../error-handler/error-handler.php"); ?>
    <div class="table-container">
        <div class="header">
            <div class="search-bar header">
                <input type="text" name="search" id="search_names" placeholder="Search Here....">
                <input style="width:40px" type="text" name="search" id="search_max" placeholder="Grade">-
                <input style="width:40px" type="text" name="search" id="search_min" placeholder="Grade">
                <button id="search_button">Search</button>
            </div>
            <div class="search-bar header">
                
            </div>
            <div class="add header">
                <button id="add-btn"><a href="add_grades.php">Add</a></button>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th hidden>ID</th>
                        <th>Profile</th>
                        <th>Fullname</th>
                        <th>Gender</th>
                        <th>Subject</th>
                        <th>Quarter 1</th>
                        <th>Quarter 2</th>
                        <th>Quarter 3</th>
                        <th>Quarter 4</th>
                        <th>Final</th>
                        <th>S.Y</th>
                        <th>Strand</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_grades= "SELECT
                                                g.grade_id as 'grade_id',
                                                stud.image as 'profile',
                                                stud.fullname as 'fullname',
                                                stud.gender as 'gender',
                                                subj.name as 'subject',
                                                g.quarter_one as 'quarter_one',
                                                g.quarter_two as 'quarter_two',
                                                g.quarter_three as 'quarter_three',
                                                g.quarter_four as 'quarter_four',
                                                g.average as 'average',
                                                g.school_year as 'sy',
                                                s.name as 'strand',
                                                emp.fullname as 'teacher'
                                            from 
                                                grades g
                                            left join
                                                strands s on s.strand_id = g.strand_id
                                            LEFT JOIN
                                                classes class on class.class_id = g.class_id
                                            left join
                                                employees emp on emp.employee_id = class.employee_id
                                            left join
                                                subjects subj on subj.subject_id = class.subject_id
                                            left join
                                                students stud on stud.student_id = g.student_id
                                            ORDER BY stud.fullname ASC";
                        $result = $conn->query($select_grades);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            
                    <tr>
                        <td hidden class="grade_id"><?php echo $rows['grade_id']; ?></td>
                        <td style="text-align:center; "><img src="<?php echo $rows['profile']; ?>" alt="" id="profile" style="width:60%; height:40px;  border-radius:50px"></td>
                        <td><?php echo $rows['fullname']; ?></td>
                        <td><?php echo $rows['gender']; ?></td>
                        <td><?php echo $rows['subject']; ?></td>
                        <td><?php echo $rows['quarter_one']; ?></td>
                        <td><?php echo $rows['quarter_two']; ?></td>
                        <td><?php echo $rows['quarter_three']; ?></td>
                        <td><?php echo $rows['quarter_four']; ?></td>
                        <td><?php echo $rows['average']; ?></td>
                        <td><?php echo $rows['sy']; ?></td>
                        <td><?php echo $rows['strand']; ?></td>
                        <td id="update_icon" style="text-align:center;"><a href="" class="edit_data"><i class="fa-solid fa-pencil"></i></a></td>
                        <td id="delete_icon" class="delete_icon" style="text-align:center;"><a href="" class="delete_data"><i class="fa-solid fa-trash-can"></i></i></a></td>
                    </tr>
                    <?php
                            }
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
    // include("add_grades.php"); 
    include("../modals/modals.php"); 
?>

<?php include("../include/footer.php"); ?>
<script>
//update 
$(document).ready(function() {
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('.grade_id').text();
        console.log(id);

        $.ajax({
            method: "post",
            url: "../control/controller.php",
            data: {
                'grade_update': true,
                'grade_id': id,
            },
            success: function(response) {
                console.log(response);
                $('.display').html(response);
                $('.update').css({
                    display: 'flex'
                })
            },
            error: function(error) {
                console.error('Error updating data:', error);
            }

        });
        $('.close').on('click', function() {
            $('.modal-update').css({
                display: 'none'
            });
        });

    });


//delete

    $(document).on('click', '.delete_data', function (e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('.grade_id').text();
        console.log(id);

        $.ajax({
            method: "post",
            url: "../control/controller.php",
            data: {
                'grade_delete': true,
                'grade_id': id,
            },
            success: function(response) {
                console.log(response);
                $('.display').html(response);
                $('.delete').css({
                    display: 'flex'
                })
            },
            error: function(error) {
                console.error('Error updating data:', error);
            }

        });
        $('.close').on('click', function() {
            $('.modal-delete').css({
                display: 'none'
            });
        });

    });

        // Search input keyup event handler
    $('#search_button').click(function(e) {
        e.preventDefault();
        var query_id = $('#search_names').val();
        var query_max = $('#search_max').val();
        var query_min = $('#search_min').val();
        console.log(query_id);
        console.log(query_max);
        console.log(query_min);

        $.ajax({
            url: '../control/controller.php',
            method: 'POST',
            data: { 
                'grade_search':true,
                'grade': query_id,
                'max': query_max,
                'min': query_min,
            },
            success: function(response) {
                console.log(response)
                $('#table-display').html(response);
            }
        });
    });
});
</script>