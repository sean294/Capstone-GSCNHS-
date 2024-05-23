<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Students's</h1>
    <?php include("../error-handler/error-handler.php"); ?>
    <div class="table-container">
        <div class="header">
            <div class="search-bar header">
                <input type="text" name="search" id="search" placeholder="Search Here....">
            </div>
            <div class="add header">
                <button id="add-btn">Add</button>
            </div>
            <div class="qr header">
                <button id="qr">QR</button>
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
                        <th>Address</th>
                        <th>Father</th>
                        <th>Mother</th>
                        <th>Parents Contacts</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_students= "SELECT
                                                student_id,
                                                fullname,
                                                gender,
                                                address,
                                                father_name,
                                                mother_name,
                                                parent_contact,
                                                image as 'image'
                                            FROM 
                                                students; ";
                        $result = $conn->query($select_students);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td hidden class="student_id"><?php echo $rows['student_id']; ?></td>
                                <td style="text-align:center;"><img class="profile" src="<?php echo $rows['image']; ?>" alt="" id="profile" style="width:80%; height:40px; border-radius:50px"></td>
                                <td><?php echo $rows['fullname']; ?></td>
                                <td><?php echo $rows['gender']; ?></td>
                                <td><?php echo $rows['address']; ?></td>
                                <td><?php echo $rows['father_name']; ?></td>
                                <td><?php echo $rows['mother_name']; ?></td>
                                <td><?php echo $rows['parent_contact']; ?></td>
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
    include("add_students.php");
    include("../modals/modals.php");
    include("qr_code.php"); 
?> 
<script src="../js/preview.js"></script>

<?php include("../include/footer.php"); ?>
<script src="../js/pop-up-qr.js"></script>
<script>
    
// students
// students
// students
// students
// students
// students

// for updating Data
$(document).ready(function () {
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        let student_update_id = $(this).closest('tr').find('.student_id').text();
        console.log(student_update_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'student_update':true,
                'student_id':student_update_id,
            },
            success:function(response_student_update){
                console.log(response_student_update);
                $('.display').html(response_student_update);
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


// for profile

    $(document).on('click', '.profile', function (e) {
        e.preventDefault();
        let student_update_profile_id = $(this).closest('tr').find('.student_id').text();
        console.log(student_update_profile_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'student_update_profile':true,
                'student_id':student_update_profile_id,
            },
            success:function(response_student_update_profile){
                console.log(response_student_update_profile);
                $('.display-profile').html(response_student_update_profile);
                $('.update-profile').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
            
        });
        $('.close-profile').on('click', function () {
            $('.modal-update-profile').css({
                display: 'none'
            });
        });

    });


// for deleting data

    $(document).on('click', '.delete_data', function (e) {
        e.preventDefault();
        let student_delete_id = $(this).closest('tr').find('.student_id').text();
        console.log(student_delete_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'student_delete':true,
                'student_id':student_delete_id,
            },
            success:function(response_student_delete){
                console.log(response_student_delete);
                $('.display').html(response_student_delete);
                $('.delete').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
            
        });
        $('.close').on('click', function () {
            console.log('Close');
            $('.modal-delete').css({
                display: 'none'
            });
        });
    });
    $('#search').keyup(function() {
        var query_id = $(this).val();

        $.ajax({
            url: '../control/controller.php',
            method: 'POST',
            data: { 
                'student_search':true,
                'student': query_id,
            },
            success: function(response) {
                $('#table-display').html(response);
            }
        });
    });

});


</script>