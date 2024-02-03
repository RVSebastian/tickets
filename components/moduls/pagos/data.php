<?php


include '../../db/cn.php';
$sql = "SELECT * FROM pagos ORDER BY fecha_autorizado DESC, fecha DESC";
$result_task_all = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result_task_all) {
    // Initialize an empty array to store the data
    $data = array();

    // Fetch each row from the result set and add it to the data array
    while ($row = mysqli_fetch_assoc($result_task_all)) {
        $data[] = $row;
    }

    // Close the database connection
    mysqli_close($conn);

    // Encode the data array as JSON and echo it
    echo json_encode($data);
} else {
    // Handle the case where the query failed
    echo json_encode(array('error' => 'Query failed.'));
}

?>