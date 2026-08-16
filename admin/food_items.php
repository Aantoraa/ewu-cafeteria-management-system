<?php
require_once "../db.php";

if (isset($_POST['add_food'])) {

    $food_name = $_POST['food_name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $sql = "INSERT INTO food_items
            (food_name, category_id, description, price, availability)
            VALUES
            ('$food_name', '$category_id', '$description', '$price', '$availability')";

    $conn->query($sql);
}

$result = $conn->query("
    SELECT food_items.*, categories.category_name
    FROM food_items
    JOIN categories
    ON food_items.category_id = categories.category_id
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Food Items - EWU Cafeteria</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Food Items</h1>
<h2>Add New Food Item</h2>

<form method="POST">

    <label>Food Name:</label>
    <input type="text" name="food_name" required>

    <br><br>

    <label>Category:</label>
    <select name="category_id" required>

        <?php
        $categories = $conn->query("SELECT * FROM categories");

        while ($category = $categories->fetch_assoc()) {
        ?>

            <option value="<?php echo $category['category_id']; ?>">
                <?php echo $category['category_name']; ?>
            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Description:</label>
    <textarea name="description"></textarea>

    <br><br>

    <label>Price:</label>
    <input type="number" name="price" step="0.01" required>

    <br><br>

    <label>Availability:</label>
    <select name="availability">

        <option value="1">Available</option>
        <option value="0">Unavailable</option>

    </select>

    <br><br>

    <button type="submit" name="add_food">
        Add Food Item
    </button>

</form>
<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Food Name</th>
        <th>Category</th>
        <th>Description</th>
        <th>Price</th>
        <th>Availability</th>
        <th>Action</th>
    </tr>

    <?php while ($food = $result->fetch_assoc()) { ?>

        <tr>
            <td><?php echo $food['food_id']; ?></td>

            <td><?php echo $food['food_name']; ?></td>

            <td><?php echo $food['category_name']; ?></td>

            <td><?php echo $food['description']; ?></td>

            <td>৳<?php echo $food['price']; ?></td>

            <td>
                <?php
                echo $food['availability'] ? "Available" : "Unavailable";
                ?>
            </td>
            <td>
    <a href="edit_food.php?id=<?php echo $food['food_id']; ?>">
        Edit
    </a>

    |

    <a
    href="delete_food.php?id=<?php echo $food['food_id']; ?>"
    onclick="return confirm('Are you sure you want to delete this food item?');"
>
    Delete
</a>
</td>
        </tr>

    <?php } ?>

</table>

</body>

</html>