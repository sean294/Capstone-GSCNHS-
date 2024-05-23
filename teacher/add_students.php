<!-- For adding Data of student table -->

<div class="bg-modal">
    <div class="modal-content">
        <div class="close"><img src="../img/close.png" id="close" alt="cross"></div>
        <form action="student.php" method="POST" enctype="multipart/form-data">
            <h2 style='text-align:center;'>Adding New Students!</h2>
            <div class="frame input" id="preview"></div>
            <div class="input">
                
                <label for="image">Select Profile:</label>
                <input type="file" name="image_file" id="image_file" onchange="getImagePreview(event)">
            </div>
            <div class="input">
                <label for="fullname">Fullname:</label>
                <input type="text" name="fullname" required value="<?php echo $fullname; ?>">
            </div>
            <div class="input">
                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required value="<?php echo $gender;?>">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="input">
                <label for="contact">Contact No:</label>
                <input type="text" name="contact" id="contact" required value="<?php echo $contact; ?>">
            </div>
            <div class="input">
                <label for="Address">Address:</label>
                <textarea name="address" id="Address" cols="17" rows="5" required><?php echo $address; ?></textarea>
            </div>
            <div class="input">
                <label for="father_name">Father's name:</label>
                <input type="text" name="father_name" required value="<?php echo $father; ?>">
            </div>
            <div class="input">
                <label for="mother_name">Mother's name:</label>
                <input type="text" name="mother_name" required value="<?php echo $mother; ?>">
            </div>
            <div class="input">
                <label for="parent_contact">Parent Contact:</label>
                <input type="text" name="parent_contact" id="parent_contact" required value="<?php echo $parent_contact; ?>">
            </div>
            <div class="input">
                <label hidden for="teacher">Teacher:</label>
                <input hidden readonly type="text" name="teacher" id="teacher" required value="<?php echo $users_id ?>">
            </div>
            <div class="btn">
                <button type="submit" name="student_submit">Submit</button>
            </div>
        </form>
    </div>
</div>


