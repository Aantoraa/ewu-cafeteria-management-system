<?php
require_once "../db.php";

if (isset($_POST['add_category'])) {

    $category_name = $_POST['category_name'];

    $sql = "INSERT INTO categories (category_name)
            VALUES ('$category_name')";

    $conn->query($sql);
}

if (isset($_POST['update_category'])) {

    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    $sql = "UPDATE categories
            SET category_name = '$category_name'
            WHERE category_id = $category_id";

    $conn->query($sql);
}

if (isset($_GET['delete'])) {

    $category_id = $_GET['delete'];

    $sql = "DELETE FROM categories
            WHERE category_id = $category_id";

    $conn->query($sql);

    header("Location: categories.php");
    exit;
}

$result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categories - EWU Cafeteria</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Categories</h1>

<h2>Add New Category</h2>

<form method="POST">

    <input
        type="text"
        name="category_name"
        placeholder="Category Name"
        required
    >

    <button type="submit" name="add_category">
        Add Category
    </button>

</form>

<hr>

<h2>Category List</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Action</th>
    </tr>

    <?php while ($category = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo $category['category_id']; ?>
            </td>

            <td>
                <?php echo $category['category_name']; ?>
            </td>

            <td>

                <form method="POST" style="display:inline;">

                    <input
                        type="hidden"
                        name="category_id"
                        value="<?php echo $category['category_id']; ?>"
                    >

                    <input
                        type="text"
                        name="category_name"
                        value="<?php echo $category['category_name']; ?>"
                        required
                    >

                    <button type="submit" name="update_category">
                        Edit
                    </button>

                </form>

                <a
                    href="categories.php?delete=<?php echo $category['category_id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this category?');"
                >
                    Delete
                </a>

            </td>

        </tr>

    <?php } ?>

</table>

</body>

</html>