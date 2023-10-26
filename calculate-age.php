<?php
$postData = file_get_contents("php://input");
$data = json_decode($postData, true); 

$dateOfBirth = $data['date_of_birth'];
$anotherDate = $data['another_date'];


// echo "{$dateOfBirth}, {$anotherDate}";

date_default_timezone_set("Asia/Dhaka");
// Validate input dates
$birthDate = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
$anotherDateObj = DateTime::createFromFormat('Y-m-d', $anotherDate);

// Check if the dates are valid
if (!$birthDate || !$anotherDateObj || $birthDate > $anotherDateObj) {
    // Invalid dates, return an error response
    $response = array(
        'status' => 'error',
        'message' => 'Invalid dates provided.'
    );
} else {
    // Calculate age in years, months, days, weeks, hours, minutes, and seconds
    $ageInterval = $birthDate->diff($anotherDateObj);
    $years = $ageInterval->y;
    $months = $ageInterval->m;
    $days = $ageInterval->d;
    // $weeks = floor($ageInterval->days / 7);
    $hours = $years * 365 * 24 + $months * 30 * 24 + $days * 24;
    $minutes = $hours * 60;
    $seconds = $minutes * 60;
    $weeks = floor(($years * 365 + $months * 30 + $days) / 7);

    // Prepare the response as an array
    $response = array(
        'status' => 'success',
        'message' => 'Age calculated successfully',
        'age' => array(
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'weeks' => $weeks,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        )
    );
}


header('Content-Type: application/json');
echo json_encode($response);
?>
