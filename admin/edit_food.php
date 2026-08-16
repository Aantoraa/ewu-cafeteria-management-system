<?php
require_once "../db.php";

$food_id = $_GET['id'];

if (isset($_POST['update_food'])) {

    $food_name = $_POST['food_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "UPDATE food_items
            SET food_name = '$food_name',
                description = '$description',
                price = '$price'
            WHERE food_id = $food_id";

    $conn->query($sql);

    header("Location: food_items.php");
    exit;
}

$result = $conn->query("SELECT * FROM food_items WHERE food_id = $food_id");

$food = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Food Item - EWU Cafeteria</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Edit Food Item</h1>

<form method="POST">

    <label>Food Name:</label>
<input
    type="text"
    name="food_name"
    value="<?php echo $food['food_name']; ?>"
    required
>

<br><br>

<label>Description:</label>
<textarea name="description"><?php echo $food['description']; ?></textarea>

<br><br>

<label>Price:</label>
<input
    type="number"
    name="price"
    step="0.01"
    value="<?php echo $food['price']; ?>"
    required
>

<br><br>

<button type="submit" name="update_food">
    Update Food Item
</button>

</form>

</body>

</html>