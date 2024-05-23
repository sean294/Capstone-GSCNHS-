<div class="qr-modal">
        <div class="qr-content">
            <div class="qr-close"><img src="../img/close.png" id="qr-close" alt="cross"></div>
            <form enctype="multipart/form-data">
                <div class="frame input" id="qr_code" style="width:200px; height:200px; margin-top:15px;"></div>
                <div class="input">
                    <input type="text" name="fname" id="description" readonly hidden>
                </div>
                <div class="input">
                    <label for="student">Student:</label>
                    <select name="student" id="student" onchange="displayDescription(); makeCode();">
                        <option value="">Select Student:</option>
                        <?php
                            $parent = "SELECT student_id as 'student_id', fullname as 'fullname' from students";
                            $result = $conn->query($parent);

                            if ($result->num_rows > 0) {
                                while ($rows = $result->fetch_assoc()) {
                                    echo "<option value='".$rows['fullname']."' data-description='".$rows['fullname']."'>".$rows['fullname']."</option>";
                                }
                                
                            }
                        ?>
                    </select>
                </div>
                <script>
                    function displayDescription() {
                        const select = document.getElementById('student');
                        const descriptionTextarea = document.getElementById('description');
                        const selectedOption = select.options[select.selectedIndex];
                        const description = selectedOption.getAttribute('data-description');
                        descriptionTextarea.value = description;
                        console.log(description);
                    }
                </script>
                <script type="text/javascript">
                    var qrcode = new QRCode(document.getElementById("qr_code"), {
                        width: 700,
                        height: 700,
                        margin:40
                    });

                    function makeCode() {
                        var elText = document.getElementById("student");

                        if (!elText.value) {
                            alert("Input a text");
                            elText.focus();
                            return;
                        }

                        // Generate QR code
                        qrcode.makeCode(elText.value);
                    }

                    // Function to save QR code as image
                    function saveQRCodeAsImage() {
                        var studentSelect = document.getElementById("student");
                        var selectedOption = studentSelect.options[studentSelect.selectedIndex];
                        var studentName = selectedOption.textContent;

                        var canvas = document.getElementById("qr_code").getElementsByTagName("canvas")[0];
                        var dataURL = canvas.toDataURL("image/png");
                        var link = document.createElement("a");
                        link.href = dataURL;
                        link.download = "qrcode_" + studentName + ".png";
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                </script>
                <div class="btn">
                    <button type="button" onclick="saveQRCodeAsImage()">Save</button>
                </div>
            </form>
        </div>
    </div>