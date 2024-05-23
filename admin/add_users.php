<!-- For adding Data of student table -->

<div class="bg-modal">
    <div class="modal-content">
        <div class="close"><img src="../img/close.png" id="close" alt="cross"></div>
        <form action="users.php" method="POST">
                <div class="header">
                    <header>
                        <h2>Add User Accounts</h2>
                    </header>
                </div>
                <div class="input">
                    <label for="username">Username:</label>
                    <input type="text" require value="" name="username" id="username">
                </div>
                <div class="input">
                    <label for="teacher">Teacher:</label>
                    <select name="teacher" id="teacher">
                        <option value="">Please Select User:</option>
                        <?php
                            $select = "SELECT employee_id, fullname FROM employees";
                            $result = $conn->query($select);

                            if ($result->num_rows > 0) {
                                while ($rows = $result->fetch_assoc()) {
                                    echo "<option value=".$rows['employee_id'].">".$rows['fullname']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
               
                <div class="btn">
                    <button type="submit" name="signup">SignUp</button>
                </div>
            </form>
    </div>
</div>


