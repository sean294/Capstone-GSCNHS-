<!-- adding classes modal -->
<div class="bg-modal">
    <div class="modal-content">
        <div class="close"><img src="../img/close.png" id="close" alt="cross"></div>
        <form action="classes.php" method="POST">
            <h2 style='text-align:center;'>Adding Classes Schedules!</h2>
            <div class="input">
                <label for="employee">Employee:</label>
                <select name="employee" id="employee">
                    <?php
                    $employee = "SELECT employee_id as 'employee_id', fullname as 'fullname' from employees";
                    $result = $conn->query($employee);

                    if ($result->num_rows > 0) {
                        while ( $rows = $result->fetch_assoc()) {
                           echo "<option value=".$rows['employee_id'].">".$rows['fullname']."</option>";
                        }       
                        }else{
                    }
                ?>
                </select>
            </div>
            <div class="input">
                <label for="subject">Subject:</label>

                <select name="subject" id="subject">
                    <?php
                    $subject = "SELECT subject_id as 'subject_id', name as 'subject' from subjects";
                    $result = $conn->query($subject);

                    if ($result->num_rows > 0) {
                        while ($rows = $result->fetch_assoc()) {
                           echo "<option value=".$rows['subject_id'].">".$rows['subject']."</option>";
                        }       
                        }else{
                    }
                ?>

                </select>
            </div>
            <div class="input">
                <label for="room">Room:</label>

                <select name="room" id="room">
                    <?php
                    $room = "SELECT room_id as 'room_id', name as 'room' from rooms";
                    $result = $conn->query($room);

                    if ($result->num_rows > 0) {
                        while ($rows = $result->fetch_assoc()) {
                           echo "<option value=".$rows['room_id'].">".$rows['room']."</option>";
                        }       
                        }else{
                    }
                ?>

                </select>
            </div>
            <div class="input">
                <label for="day">Day:</label>
                <select name="day" id="day" required value="<?php echo $day;?>">
                    <option value="MWF">MWF</option>
                    <option value="TTH">TTH</option>
                </select>
            </div>
            <div class="input">
                <label for="start">Start:</label>
                <input type="time" name="start" id="start" required value="<?php echo $startDateTime; ?>">
            </div>
            <div class="input">
                <label for="end">End:</label>
                <input type="time" name="end" id="end" required value="<?php echo $endDateTime; ?>">
            </div>
            <div class="input">
                <label for="school_year">School Year:</label>
                <select name="school_year" id="school_year" required value="<?php echo $sy; ?>">
                    <option value="2023-2024">2023-2024</option>
                </select>
            </div>
            <div class="btn">
                <button type="submit" name="class_submit">Submit</button>
            </div>
        </form>
    </div>
</div>