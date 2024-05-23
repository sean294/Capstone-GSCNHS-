<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Users</h1>
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
                        <th>Username</th>
                        <th>Fullname</th>
                        <th>Position</th>
                        <th>Date Added</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_students= "SELECT
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
                                                u.user_id";
                        $result = $conn->query($select_students);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td hidden class="user_id"><?php echo $rows['id']; ?></td>
                                <td><?php echo $rows['username']; ?></td>
                                <td><?php echo $rows['fullname']; ?></td>
                                <td><?php echo $rows['position']; ?></td>
                                <td><?php echo $rows['date']; ?></td>
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
    include("add_users.php"); 
    include("../modals/modals.php"); 
?>
<?php include("../include/footer.php"); ?>
<script>


$(document).ready(function() {
    // Edit button click event handler
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('.user_id').text();
        console.log(id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'user_account_update':true,
                'user_id':id,
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
        var id = $(this).closest('tr').find('.user_id').text();
        console.log(id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'user_account_delete':true,
                'user_id':id,
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
                'users_search':true,
                'users': query_id,
            },
            success: function(response) {
                $('#table-display').html(response);
            }
        });
    });
});

</script>
