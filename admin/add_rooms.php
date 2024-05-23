<!-- adding data -->
<div class="bg-modal">
    <div class="modal-content">
        <div class="close"><img src="../img/close.png" id="close" alt="cross"></div>
        <form action="room.php" method="POST">
        <h2 style='text-align:center;'>Adding New Rooms!</h2>
            <div class="input">
                <label for="room">Room Name:</label><br>
                <input type="text" name="room" id="room" style="" require value="<?php echo $room; ?>">
            </div>
            <div class="input">
                <label for="strand">Strand:</label>

                <select name="strand" id="strand" require value="<?php echo $strand; ?>">
                <?php
                    $strand = "SELECT * from strands";
                    $result = $conn->query($strand);

                    if ($result->num_rows > 0) {
                        while ( $rows = $result->fetch_assoc()) {
                           echo "<option value=".$rows['strand_id'].">".$rows['name']."</option>";
                        } 
                        }else{
                    }
                ?>
                    
                </select>
            </div>
            <div class="btn">
                <button type="submit" name="room_submit">Submit</button>
            </div>
        </form>
    </div>
</div>
