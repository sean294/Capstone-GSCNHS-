<?php

if (isset($_POST['bargraph'])) {
    $subject_id = $_POST['subject_id'];
    $max = $_POST['query_max'];
    $min = $_POST['query_min'];
    $select_data = $conn->prepare("SELECT
                                        COUNT(*) AS total_rows,
                                        (SUM(CASE 
                                            WHEN class_id = ? AND quarter_one >= ? AND quarter_one <= ? THEN 1
                                            ELSE 0
                                        END) / COUNT(*)) * 100 AS quarter_one_percentage,
                                        (SUM(CASE 
                                            WHEN class_id = ? AND quarter_two >= ? AND quarter_two <= ? THEN 1
                                            ELSE 0
                                        END) / COUNT(*)) * 100 AS quarter_two_percentage,
                                        (SUM(CASE 
                                            WHEN class_id = ? AND quarter_three >= ? AND quarter_three <= ? THEN 1
                                            ELSE 0
                                        END) / COUNT(*)) * 100 AS quarter_three_percentage,
                                        (SUM(CASE 
                                            WHEN class_id = ? AND quarter_four >= ? AND quarter_four <= ? THEN 1
                                            ELSE 0
                                        END) / COUNT(*)) * 100 AS quarter_four_percentage,
                                        (SUM(CASE 
                                            WHEN class_id = ? AND average >= ? AND average <= ? THEN 1
                                            ELSE 0
                                        END) / COUNT(*)) * 100 AS average_percentage
                                    FROM 
                                        grades
                                    WHERE 
                                        class_id = ?");

    $select_data->bind_param("issississississi", $subject_id, $min, $max, $subject_id, $min, $max, $subject_id, $min, $max, 
    $subject_id, $min, $max, $subject_id, $min, $max, $subject_id);
    $select_data->execute();
    $result = $select_data->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
            echo json_encode($data);
    }
}
?>

