<!-- For Adding Data for Employees Modal -->

<div class="bg-modal">
    <div class="modal-content">
        <div class="close"><img src="../img/close.png" id="close" alt="cross"></div>
        <form action="employee.php" method="POST" enctype="multipart/form-data">
        <h2 style='text-align:center;'>Adding New Employee's!</h2>
            <div class="frame input" id="preview"></div>
            <div class="input">
                
                <label for="image">Select Profile::</label>
                <input type="file" name="image_file" id="image_file" onchange="getImagePreview(event)">
            </div>
            <div class="input">
                <label for="fullname">Fullname:</label>
                <input type="text" name="fullname" require value="<?php echo $fullname; ?>">
            </div>
            <div class="input">
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" require value="<?php echo $gender ?>">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="input">
                <label for="contact">Contact No:</label>
                <input type="text" name="contact" id="contact" require value="<?php echo $contact; ?>">
            </div>
            <div class="input">
                <label for="Address">Address:</label>
                <textarea name='address' id='Address' cols='17' rows='5' require value="<?php echo $address; ?>"></textarea>
            </div>
            <div class="input">
                <label for="position">Position:</label>

                <select name="position" id="position" require value="<?php echo $position; ?>">
                <?php
                    $position = "SELECT * from positions";
                    $result = $conn->query($position);

                    if ($result->num_rows > 0) {
                        while ( $rows = $result->fetch_assoc()) {
                           echo "<option value=".$rows['position_id'].">".$rows['position']."</option>";
                        }       
                        }else{
                    }
                ?>
                    
                </select>
            </div>
            <div class="btn">
                <button type="submit" name="employee_submit">Submit</button>
            </div>
        </form>
    </div>
</div>


