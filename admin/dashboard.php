<?php include("../include/header.php"); ?>

<div class="main-content" style="overflow-y:auto;">
    <?php include("../error-handler/error-handler.php"); ?>

    <div class="sub_main-content">
        <h1>Dashboard</h1>
        <div class="first-row row">
            <div class="column student">
                <div class="count">
                    <img src="../img/student.jpg" alt="student">
                    <?php
                                    $student_count = "SELECT COUNT(*) AS 'student' from students";
                                    $result = $conn->query($student_count);

                                    if ($result->num_rows > 0) {
                                        # code...
                                        $rows = $result->fetch_assoc();
                                        echo "<span>".$rows['student']."  student</span>";
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
    </div>
    <div class="sub_main-content" style="height:auto;background-color:rgb(179, 179, 179)">
        <div id="display_percent"></div>
        <div class="barchart" style="width:90%;height:100%;margin:0 auto;">
            <select name="" id="search_subject">
                <option value="">--Subjects Here--</option>
                <?php
        $select_subject = $conn->prepare("SELECT 
                                            DISTINCT g.class_id AS class_id, 
                                            subj.name AS subjects 
                                        FROM 
                                            grades g 
                                        LEFT JOIN 
                                            classes class ON class.class_id = g.class_id 
                                        LEFT JOIN 
                                            subjects subj ON class.subject_id = subj.subject_id;");
                    $select_subject->execute();
                    $result = $select_subject->get_result();
                    if ($result->num_rows > 0) {
                        while ($rows = $result->fetch_assoc()) {
                            echo "<option value='".$rows['class_id']."' data-subject='".$rows['subjects']."'>".$rows['subjects']."</option>";
                        }
                    }
                ?>
            </select>
            <input style="width:40px" type="text" name="search" id="search_max" placeholder="Grade">-
            <input style="width:40px" type="text" name="search" id="search_min" placeholder="Grade">
            <button id="search_button">Search</button>
            <div id="info" class="info" style="text-align:center;font-size:18px;padding:5px;font-weight:600;">Out of
                <span id="totalRows"></span> Student in this
                Subject <span id="selectedSubject"></span>
            </div>
            <canvas id="myChart" width="500" height="200" style="padding:10px 0px;"></canvas>
        </div>


        <script>
        $(document).ready(function() {
            $('#search_button').click(function(e) {
                e.preventDefault();
                var subject = $('#search_subject option:selected').data('subject');
                var subject_id = $('#search_subject').val();
                var query_max = $('#search_max').val();
                var query_min = $('#search_min').val();

                $.ajax({
                    url: '../control/controller.php',
                    method: 'POST',
                    data: {
                        'bargraph': true,
                        'subject_id': subject_id,
                        'query_max': query_max,
                        'query_min': query_min,
                    },
                    success: function(response) {
                        console.log(response);
                        var data = JSON.parse(response);
                        updateChart(data);

                        // Update the content of the selectedSubject span with the selected subject
                        $('#selectedSubject').text(subject);
                    }
                });
            });

            function updateChart(data) {
                var quarterOne = parseFloat(data['quarter_one_percentage']);
                var quarterTwo = parseFloat(data['quarter_two_percentage']);
                var quarterThree = parseFloat(data['quarter_three_percentage']);
                var quarterFour = parseFloat(data['quarter_four_percentage']);
                var average = parseFloat(data['average_percentage']);
                var total_rows = parseFloat(data['total_rows']);

                console.log("Quarter One:", quarterOne);
                console.log("Quarter Two:", quarterTwo);
                console.log("Quarter Three:", quarterThree);
                console.log("Quarter Four:", quarterFour);
                console.log("Average:", average);
                console.log("Total Rows:", total_rows);

                // Update the HTML with the total rows
                $('#totalRows').text(total_rows);


                const labels = ['Quarter1-%', 'Quarter2-%', 'Quarter3-%', 'Quarter4-%', 'Average-%'];
                var data = [quarterOne, quarterTwo, quarterThree, quarterFour, average, 100];

                var ctx = document.getElementById('myChart').getContext('2d');
                if (window.myChart instanceof Chart) {
                    // Destroy existing chart if it is an instance of Chart
                    window.myChart.destroy();
                }

                window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Percentage from Quater 1 to Final by Subjects',
                            data: data,
                            backgroundColor: [
                                'rgba(255, 26, 104, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 26, 104, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                ticks: {
                                    color: [
                                        'rgba(255, 26, 104)',
                                        'rgba(54, 162, 235)',
                                        'rgba(255, 206, 86',
                                        'rgba(75, 192, 192)',
                                        'rgba(153, 102, 255)'
                                    ]
                                },
                                grid: {
                                    color: 'black'
                                }
                            },
                            y: {
                                ticks: {
                                    color: 'black',
                                },
                                grid: {
                                    color: 'black'
                                }
                            },
                            beginAtZero: true
                        }
                    }
                });
            }
        });
        </script>
    </div>
</div>
<?php
    include("../modals/modals.php"); 
?>

<?php include("../include/footer.php"); ?>