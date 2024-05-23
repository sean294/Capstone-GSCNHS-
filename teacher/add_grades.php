<?php include("../include/header.php"); ?>


<div class="main-content">
<?php include("../error-handler/error-handler.php"); ?>
    <div class="form-container">

        <div class="form-wrapper">

            <form action="add_grades.php" method="POST" class="display_subjects" id="display_subjects">
                <div class="hero">
                    <div class="header">
                        <div class="header_name">
                            <h1>Adding Grades</h1>
                        </div>

                    </div>

                    <div class="header-text">
                        <span>Student</span>
                        <span>Subject</span>
                        <span>Quarter 1</span>
                        <span>Quarter 2</span>
                        <span>Quarter 3</span>
                        <span>Quarter 4</span>
                        <span>School Year</span>
                        <span>Strand</span>
                        <span id="add-more"><a href="#" class="add-more">Add More</a></span>
                        
                    </div>                   
                </div>


                <div class="inputs">
                    <div class="input-form">
                        <select name="student[]" class="select" required >
                            <option value="">--Please Select a Student--</option>
                            <?php
                                $select_class = $conn->prepare("SELECT 
                                                                    *
                                                                from 
                                                                    students");
                                $select_class->execute();
                                $result = $select_class->get_result();

                                if ($result->num_rows > 0) {
                                    while ($rows = $result->fetch_assoc()) {
                                        echo "<option value='".$rows['student_id']."'>".$rows['fullname']."</option>";
                                    }
                                    
                                }else{

                                }
                                
                            ?>
                        </select>
                    </div>

                    <div class="input-form">
                        <select name="class[]" class="select" required>
                            <!-- <option value="">--Please Select Subject--</option> -->
                            <?php
                                $select_class = $conn->prepare("SELECT class_id as 'class_id', subj.name as 'subjects' from classes class left join subjects subj on class.subject_id = subj.subject_id where employee_id = '$users_id'");
                                $select_class->execute();
                                $result = $select_class->get_result();

                                if ($result->num_rows > 0) {
                                    while ($rows = $result->fetch_assoc()) {
                                        echo "<option value='".$rows['class_id']."'>".$rows['subjects']."</option>";
                                    }
                                    
                                }else{

                                }
                                
                            ?>
                        </select>
                    </div>

                    <div class="input-form">
                        <input type="number" step="0.01" id="input_one" name="quarter_one[]" class="grades" value="" required placeholder="First Quarter">
                    </div>
                    <div class="input-form">
                        <input type="number" step="0.01" id="input_two" name="quarter_two[]" class="grades" value="" required placeholder="Second Quarter">
                    </div>
                    <div class="input-form">
                        <input type="number" step="0.01" id="input_three" name="quarter_three[]" class="grades" value="" required placeholder="Third Quarter">
                    </div>
                    <div class="input-form">
                        <input type="number" step="0.01" id="input_four" name="quarter_four[]" class="grades" value="" required placeholder="Fourth Quarter">
                    </div>
                    <div class="input-form">
                        <select name="sy[]" class="select" required>
                            <option value="">--Please Select S.Y--</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2026-2027">2026-2027</option>
                            <option value="2027-2028">2027-2028</option>
                        </select>
                    </div>

                    <div class="input-form">
                        <select name="strand[]" class="select" required>
                            <option value="">--Please Select Strand--</option>
                            <?php
                                $select_class = $conn->prepare("SELECT 
                                                                    *
                                                                from 
                                                                    strands
                                                                where 
                                                                    strand_id");
                                $select_class->execute();
                                $result = $select_class->get_result();

                                if ($result->num_rows > 0) {
                                    while ($rows = $result->fetch_assoc()) {
                                        echo "<option value='".$rows['strand_id']."'>".$rows['name']."</option>";
                                    }
                                    
                                }else{

                                }
                                
                            ?>
                        </select>
                    </div>
                    <!-- <div>
                        <button class="add-more">Add More</button>
                    </div> -->
                </div>
                <div class="new-form"></div>
                <div class="btn-submit">
                    <button type="submit" name="add_grades">Submit</button>
                </div>
            </form>

        </div>

    </div>

</div>
<script>
$(document).ready(function() {
        $(".select").select2();
        
        $(document).on('click', '.btn-remove', function(){
            $(this).closest('.inputs').remove();
        })

        $(document).on('click', '.add-more', function(){
            $('.new-form').append(`<?php
                $select_student = $conn->prepare("SELECT * FROM students");
                $select_student->execute();
                $result = $select_student->get_result();
                $students_options = "";
                if ($result->num_rows > 0) {
                    while ($rows = $result->fetch_assoc()) {
                        $students_options .= "<option value='".$rows['student_id']."'>".$rows['fullname']."</option>";
                    }
                }
                $select_class = $conn->prepare("SELECT class_id as 'class_id', subj.name as 'subjects' from classes class left join subjects subj on class.subject_id = subj.subject_id where employee_id = '$users_id'");
                $select_class->execute();
                $result = $select_class->get_result();
                $classes_options = "";
                if ($result->num_rows > 0) {
                    while ($rows = $result->fetch_assoc()) {
                        $classes_options .= "<option value='".$rows['class_id']."'>".$rows['subjects']."</option>";
                    }
                }
            ?>
            <div class="inputs">
                <div class="input-form">
                    <select name="student[]" class="select" required>
                        <option value="">--Please Select a Student--</option>
                        <?= $students_options ?>
                    </select>
                </div>

                <div class="input-form">
                    <select name="class[]" class="select" required>
                        <?= $classes_options ?>
                    </select>
                </div>

                <div class="input-form">
                    <input type="number" step="0.01" id="input_one" name="quarter_one[]" class="grades" value="" required placeholder="First Quarter">
                </div>
                <div class="input-form">
                    <input type="number" step="0.01" id="input_two" name="quarter_two[]" class="grades" value="" required placeholder="Second Quarter">
                </div>
                <div class="input-form">
                    <input type="number" step="0.01" id="input_three" name="quarter_three[]" class="grades" value="" required placeholder="Third Quarter">
                </div>
                <div class="input-form">
                    <input type="number" step="0.01" id="input_four" name="quarter_four[]" class="grades" value="" required placeholder="Fourth Quarter">
                </div>
                <div class="input-form">
                    <select name="sy[]" class="select" required>
                        <option value="">--Please Select S.Y--</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                        <option value="2027-2028">2027-2028</option>
                    </select>
                </div>

                <div class="input-form">
                    <select name="strand[]" class="select" required>
                        <option value="">--Please Select Strand--</option>
                        <?php
                            $select_class = $conn->prepare("SELECT * FROM strands");
                            $select_class->execute();
                            $result = $select_class->get_result();
                            if ($result->num_rows > 0) {
                                while ($rows = $result->fetch_assoc()) {
                                    echo "<option value='".$rows['strand_id']."'>".$rows['name']."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="">
                    <button class="btn-remove" onclick="sink()">Remove</button>
                </div>
            </div>`);
            $(".new-form .select").select2();
        });
    });

    function sink() {
        var element = document.getElementById("box");
        element.classList.add("sink"); // Add the "sink" class to apply the effect
        
        // Remove the "sink" class after a delay to reset the effect
        setTimeout(function() {
            element.classList.remove("sink");
        }, 300); // Adjust the delay (in milliseconds) to match the transition duration
    }

</script>

<?php include("../include/footer.php"); ?> 

