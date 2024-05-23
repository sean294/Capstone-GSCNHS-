<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Subject's</h1>
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
                        <th>ID</th>
                        <th>Subjects</th>
                        <th>Category</th>
                        <th colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-display">
                    <?php
                        $select_students= "SELECT
                                                subject_id as 'id',
                                                subjects.name as 'subject',
                                                subject_categories.name 'category'
                                            FROM 
                                                subjects
                                            LEFT JOIN
                                                subject_categories on subject_categories.subj_cat_id = subjects.subj_cat_id
                                            WHERE
                                                subject_id";
                        $result = $conn->query($select_students);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td class="subject_id"><?php echo $rows['id']; ?></td>
                                <td><?php echo $rows['subject']; ?></td>
                                <td><?php echo $rows['category']; ?></td>
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
    include("add_subjects.php"); 
    include("../modals/modals.php"); 
?>

<?php include("../include/footer.php"); ?>
<script>
$(document).ready(function() {
    // Edit button click event handler
    $(document).on('click', '.edit_data', function (e) {
        e.preventDefault();
        var id = $(this).closest('tr').find('.subject_id').text();
        console.log(id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'subject_update':true,
                'subject_id':id,
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
        var id = $(this).closest('tr').find('.subject_id').text();
        console.log(id);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'subject_delete':true,
                'subject_id':id,
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
                'subject_search':true,
                'subject_id': query_id,
            },
            success: function(response) {
                $('#table-display').html(response);
            }
        });
    });
});


</script>