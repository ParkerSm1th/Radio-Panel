<?php

$stmt = $conn->prepare("UPDATE timetable SET booked = 0, booked_type = 0");
$stmt->execute();
echo "updated";
?>
