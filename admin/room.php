<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Room's</h1>
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
                        <th>Rooms</th>
                        <th>Strands</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_students= "SELECT
                                                r.room_id as 'r_id',
                                                r.name as 'room',
                                                s.name as 'strand'
                                            FROM 
                                                rooms r
                                            left join
                                                strands s on s.strand_id = r.strand_id
                                            WHERE
                                                room_id";
                        $result = $conn->query($select_students);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td hidden class="room_id"><?php echo $rows['r_id']; ?></td>
                                <td><?php echo $rows['room']; ?></td>
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
    include("add_rooms.php"); 
    include("../modals/modals.php"); 
?>

<?php include("../include/footer.php"); ?>
<script>


$(document).ready(function() {
    // Edit button click event handler
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        var room_update_id = $(this).closest('tr').find('.room_id').text();
        console.log(room_update_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'room_update':true,
                'room_id':room_update_id,
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
        var room_delete_id = $(this).closest('tr').find('.room_id').text();
        console.log(room_delete_id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'room_delete':true,
                'room_id':room_delete_id,
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
                'room_search':true,
                'room': query_id,
            },
            success: function(response) {
                $('#table-display').html(response);
            }
        });
    });
});
</script>