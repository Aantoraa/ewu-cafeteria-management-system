<?php
require_once "../db.php";

$food_id = $_GET['id'];

$sql = "DELETE FROM food_items WHERE food_id = $food_id";

$conn->query($sql);

header("Location: food_items.php");
exit;
?>