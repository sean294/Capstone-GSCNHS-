<?php include("../include/header.php"); ?>

<div class="main-content">
<?php include("../error-handler/error-handler.php"); ?>
                <h1>Dashboard</h1>
                <div class="first-row row">
                    <div class="column student">
                        <div class="count">
                            <img src="../img/student.jpg" alt="student">
                            <?php
                                $student_count = "SELECT COUNT(*) AS 'student' from students where employee_id = $users_id";
                                $result = $conn->query($student_count);

                                if ($result->num_rows > 0) {
                                    # code...
                                    $rows = $result->fetch_assoc();
                                    echo "<span>".$rows['student']." My student</span>";
                                }else{
                                    echo "<span>Error</span>";
                                }
                            ?>
                            
                        </div>
                    </div>
                    <div class="column strands">
                        <div class="count">
                            <img src="../img/course.png" alt="course">
                            <?php
                                $strand_count = "SELECT COUNT(*) AS 'strand' from strands";
                                $result = $conn->query($strand_count);

                                if ($result->num_rows > 0) {
                                    # code...
                                    $rows = $result->fetch_assoc();
                                    echo "<span>".$rows['strand']."  strands</span>";
                                }else{
                                    echo "<span>Error</span>";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="column employee">
                        <div class="count">
                            <img src="../img/employee.png" alt="employee">
                            <?php
                                $employee_count = "SELECT COUNT(*) AS 'employee' from employees";
                                $result = $conn->query($employee_count);

                                if ($result->num_rows > 0) {
                                    # code...
                                    $rows = $result->fetch_assoc();
                                    echo "<span>".$rows['employee']."  employee</span>";
                                }else{
                                    echo "<span>Error</span>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="second-row row">
                    <div class="column subject">
                        <div class="count">
                            <img src="../img/subject.png" alt="subject">
                            <?php
                                $subject_count = "SELECT COUNT(*) AS 'subject' from subjects";
                                $result = $conn->query($subject_count);

                                if ($result->num_rows > 0) {
                                    # code...
                                    $rows = $result->fetch_assoc();
                                    echo "<span>".$rows['subject']."  subject</span>";
                                }else{
                                    echo "<span>Error</span>";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="column rooms">
                        <div class="count">
                            <img src="../img/room.png" alt="rooms">
                            <?php
                                $rooms_count = "SELECT COUNT(*) AS 'room' from rooms";
                                $result = $conn->query($rooms_count);

                                if ($result->num_rows > 0) {
                                    # code...
                                    $rows = $result->fetch_assoc();
                                    echo "<span>".$rows['room']."  room</span>";
                                }else{
                                    echo "<span>Error</span>";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="column classes">
                        <div class="count">
                            <img src="../img/classes.png" alt="classes">
                            <?php
                                $class_count = "SELECT COUNT(*) AS 'class' from classes";
                                $result = $conn->query($class_count);

                                if ($result->num_rows > 0) {
                                    # code...
                                    $rows = $result->fetch_assoc();
                                    echo "<span>".$rows['class']."  classes</span>";
                                }else{
                                    echo "<span>Error</span>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- <div class="third-row row">

                </div> -->
         </div>
    </div>
<?php
    include("../modals/modals.php"); 
?>

<?php include("../include/footer.php"); ?> 