<?php include("../include/header.php"); 

?>
<div class="main-content">
    <h1>Employee's</h1>
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
                        <th>Contact No</th>
                        <th>Address</th>
                        <th>Position</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_employee = "SELECT
                                                employee_id as 'id',
                                                fullname as 'fullname',
                                                gender as 'gender',
                                                contact_no as 'contact',
                                                address as 'address',
                                                positions.position as 'position',
                                                image as 'image'
                                            FROM 
                                                employees 
                                            LEFT JOIN 
                                                positions on positions.position_id = employees.position_id
                                            where
                                                employee_id;";
                        $result = $conn->query($select_employee);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td hidden class="employee_id"><?php echo $rows['id']; ?></td>
                                <td style="text-align:center; "><img class="profile" src="<?php echo $rows['image']; ?>" alt="" id="profile" style="width:60%; height:40px;  border-radius:50px"></td>
                                <td><?php echo $rows['fullname']; ?></td>
                                <td><?php echo $rows['gender']; ?></td>
                                <td><?php echo $rows['contact']; ?></td>
                                <td><?php echo $rows['address']; ?></td>
                                <td><?php echo $rows['position']; ?></td>
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
    include("add_employees.php");
    include("../modals/modals.php");

?> 
<script src="../js/preview.js"></script>

<?php include("../include/footer.php"); ?> 
<script>
    
// employees
// employees
// employees
// employees
// employees


// Updating 
$(document).ready(function () {
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        let click_update_id = $(this).closest('tr').find('.employee_id').text();
        console.log(click_update_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'click_update':true,
                'user_id':click_update_id,
            },
            success:function(response_click_update){
                console.log(response_click_update);
                $('.display').html(response_click_update);
                $('.modal-update').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
            
        });

    });
    $('.close').on('click', function () {
        console.log('Close');
        $('.modal-update').css({
            display: 'none'
        });
    });


// for profile updating

    $(document).on('click', '.profile', function (e) {
        e.preventDefault();
        let employee_update_profile_id = $(this).closest('tr').find('.employee_id').text();
        console.log(employee_update_profile_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'employee_update_profile':true,
                'user_id':employee_update_profile_id,
            },
            success:function(response_employee_update_profile){
                console.log(response_employee_update_profile);
                $('.display-profile').html(response_employee_update_profile);
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


// fatching data dynimically for deleting

    $(document).on('click', '.delete_data', function (e) {
        e.preventDefault();
        let click_delete_id = $(this).closest('tr').find('.employee_id').text();
        console.log(click_delete_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'click_delete':true,
                'user_id':click_delete_id,
            },
            success:function(response_click_delete){
                console.log(response_click_delete);
                $('.display').html(response_click_delete);
                $('.modal-delete').css({
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
                'employee_search':true,
                'employee': query_id,
            },
            success: function(response) {
                $('#table-display').html(response);
            }
        });
    });

});

</script>
