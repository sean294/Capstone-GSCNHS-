<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Classes</h1>
    <?php include("../error-handler/error-handler.php"); ?>
    <div class="table-container">
        <div class="header">
            <div class="search-bar header">
                <input type="text" name="search" id="search" placeholder="Search Here....">
            </div>
            <div class="add header">
                <button id="add-btn">Add</button>
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
                        <th>Room</th>
                        <th colspan="3" style="text-align:center;">Schedule</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_students= "SELECT
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
                                                classes.class_id";
                        $result = $conn->query($select_students);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td hidden class="class_id"><?php echo $rows['id']; ?></td>
                                <td style="text-align:center; "><img src="<?php echo $rows['image']; ?>" alt="" id="profile" style="width:60%; height:40px;  border-radius:50px"></td>
                                <td><?php echo $rows['fullname']; ?></td>
                                <td><?php echo $rows['gender']; ?></td>
                                <td><?php echo $rows['subject']; ?></td>
                                <td><?php echo $rows['room']; ?></td>
                                <td><?php echo $rows['day']; ?></td>
                                <td><?php echo $rows['start']; ?></td>
                                <td><?php echo $rows['end']; ?></td>
                                <td id="update_icon" class="update_icon" style="text-align:center;"><a href="" class="edit_data"><i class="fa-solid fa-pencil"></i></a></td>
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
    include("add_classes.php"); 
    include("../modals/modals.php"); 
?>

<?php include("../include/footer.php"); ?>
<script>


$(document).ready(function() {
    // Edit button click event handler
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        var class_update_id = $(this).closest('tr').find('.class_id').text();
        console.log(class_update_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'class_update':true,
                'class_id':class_update_id,
            },
            success:function(response){
                console.log(response);
                $('.display').html(response);
                $('.update').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
            
        });
        $('.close').on('click', function () {
            $('.modal-update').css({
                display: 'none'
            });
        });

    });

    // Delete button click event handler
    $(document).on('click', '.delete_data', function (e) {
        e.preventDefault();
        var class_delete_id = $(this).closest('tr').find('.class_id').text();
        console.log(class_delete_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'class_delete':true,
                'class_id':class_delete_id,
            },
            success:function(response){
                console.log(response);
                $('.display').html(response);
                $('.delete').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
            
        });
        $('.close').on('click', function () {
            $('.modal-delete').css({
                display: 'none'
            });
        });

    });

    // Search input keyup event handler
    $('#search').keyup(function() {
        var query_id = $(this).val();

        $.ajax({
            url: '../control/controller.php',
            method: 'POST',
            data: { 
                'class_search':true,
                'class': query_id,
            },
            success: function(response) {
                $('#table-display').html(response);
            }
        });
    });
});
</script>