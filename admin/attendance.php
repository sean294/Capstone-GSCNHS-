<?php include("../include/header.php"); ?>

<div class="main-content">
    <h1>Attendance</h1>
    <?php include("../error-handler/error-handler.php"); ?>
    <div class="table-container">
        <div class="header">
            <div class="search-bar header">
                <button onclick="printTable()" style="background-color:cyan;color:black;border:none;padding:5px 8px;">Print</button>
                <!-- <input style="margin-left:30px;" type="date" name="search" id="search_date" placeholder="Search Here....">
                <button id="search_button">Search</button> -->
            </div>
        </div>
        <div class="table-wrapper">
            <table id="myTable">
                <thead>
                    <tr>
                        <th hidden>ID</th>
                        <th>Fullname</th>
                        <th>Time In Am</th>
                        <th>Time Out Am</th>
                        <th>Time In Pm</th>
                        <th>Time Out Pm</th>
                        <th>Date</th>
                        <th hidden colspan="2" style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="table-container">
                    <?php

                        $datenow = date("m/d/Y");
                        

                        $select_students= "SELECT
                                                *
                                            FROM 
                                                attendance
                                            where
                                                date = '$datenow'
                                            order by
                                                id DESC";
                        $result = $conn->query($select_students);

                        if ($result->num_rows > 0) {
                            while ($rows = $result->fetch_assoc()) {?>
                            <tr>
                                <td hidden class="attendance_id"><?php echo $rows['id']; ?></td>
                                <td><?php echo $rows['fullname']; ?></td>
                                <td><?php echo $rows['time_in_am']; ?></td>
                                <td><?php echo $rows['time_out_am']; ?></td>
                                <td><?php echo $rows['time_in_pm']; ?></td>
                                <td><?php echo $rows['time_out_pm']; ?></td>
                                <td><?php echo date('F d, Y', strtotime($rows['date'])); ?></td>
                                <!-- <td style="text-align:center"><?php echo date('H:i:m A', strtotime($rows['date'])); ?></td> -->
                                <!-- <td hidden id="update_icon" style="text-align:center;"><a href="" class="edit_data"><i class="fa-solid fa-pencil"></i></a></td>
                                <td hidden id="delete_icon" class="delete_icon" style="text-align:center;"><a href="" class="delete_data"><i class="fa-solid fa-trash-can"></i></i></a></td> -->
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
<script>
function printTable() {
    window.print();
}
$(document).ready(function (){
    $(document).on('click', '#search_button', function (e){
        e.preventDefault();

        let search_date = $('#search_date').val();
        console.log(search_date);
        
        $.ajax({
            type: 'POST',
            url: "../control/controller.php",
            data: {
                'search_btn_admin':true,
                'search_d': search_date},
            success: function(response) {
                $('#table-container').html(response);
            }
        });
    });
});
</script>
<?php include("../include/footer.php"); ?>
