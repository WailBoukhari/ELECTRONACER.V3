<?php
include('db_cnx.php');

// Check if the category ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the category details based on the provided category ID
    $categoryQuery = "SELECT * FROM Categories WHERE category_id=$id";
    $result = mysqli_query($conn, $categoryQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $categorie = mysqli_fetch_assoc($result);
    } else {
        echo "Category not found.";
        exit();
    }
} else {
    echo "Category ID not provided.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission and update category details
    $category_id = $_GET["id"];
    $cat_name = $_POST['name_cat'];

    if (isset($_FILES['file'])) {
        $photo = basename($_FILES['file']['name']);
        $targetPath = './img/' . $photo;
        $tempPath = $_FILES['file']['tmp_name'];

        if (move_uploaded_file($tempPath, $targetPath)) {
            // Delete the current image file if it exists
            $currentImage = $_POST['current_image'];
            if ($currentImage && file_exists('./img/' . $currentImage)) {
                unlink('./img/' . $currentImage);
            }

            $conn->query("UPDATE Categories SET category_name='$cat_name', imag_category='$photo' WHERE category_id=$category_id");

            header("Location: admin-dashboard.php?page=category-management");
            exit();
        }
    } else {
        // If no new image is uploaded, update only the category name
        $conn->query("UPDATE Categories SET category_name='$cat_name' WHERE category_id=$category_id");

        header("Location: admin-dashboard.php?page=category-management");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
        integrity="sha512-oAvZuuYVzkcTc2dH5z1ZJup5OmSQ000qlfRvuoTTiyTBjwX1faoyearj8KdMq0LgsBTHMrRuMek7s+CxF8yE+w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>


    <form class="form-section" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3 w-50 mx-5">
            <label for="name_category">New Name</label>
            <input type="text" id="name_category" class="form-control" name="name_cat"
                value="<?php echo $categorie['category_name']; ?>">
        </div>

        <div class="form-group mb-3 w-50 mx-5">
            <label for="img_category">Current Photo</label>
            <img src="./img/<?php echo $categorie['imag_category']; ?>" alt="Current Image" style="max-width: 100%;">
        </div>

        <div class="form-group mb-3 w-50 mx-5">
            <label for="new_img_category">New Photo</label>
            <input type="file" id="new_img_category" class="form-control" name="file">
        </div>
        <button type="submit" name="submit" value="$categorie['imag_category']; ?>"
            class="btn btn-primary text-light mx-4">Modifier votre catégorie</button>
    </form>

</body>


</html>