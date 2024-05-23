<!-- adding data -->
<div class="bg-modal">
    <div class="modal-content">
        <div class="close"><img src="../img/close.png" id="close" alt="cross"></div>
        <form action="subject.php" method="POST">
        <h2 style='text-align:center;'>Adding New Subjects!</h2>
            <div class="input">
                <label for="subject">Subject Name:</label><br>
                <input type="text" name="subject" id="subject" style="" required value="<?php echo $subject; ?>">
            </div>
            <div class="input">
                <label for="category">Subject Category:</label>

                <select name="category" id="category" required value="<?php echo $category; ?>">
                <?php
                    $category = "SELECT * from subject_categories";
                    $result = $conn->query($category);

                    if ($result->num_rows > 0) {
                        while ( $rows = $result->fetch_assoc()) {
                           echo "<option value=".$rows['subj_cat_id'].">".$rows['name']."</option>";
                        }       
                        }else{
                    }
                ?>
                    
                </select>
            </div>
            <div class="btn">
                <button type="submit" name="subject_submit">Submit</button>
            </div>
        </form>
    </div>
</div>
