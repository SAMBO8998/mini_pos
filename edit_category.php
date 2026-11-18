<?php
require_once "./includes/auth.php";
include "./config/db.php";
$id = $_GET['id']; 
$result = $conn->query("SELECT * FROM `categories` WHERE id = $id");
$category = mysqli_fetch_assoc($result);
if(isset($_POST['update'])){
    $name = $_POST['category'];
    $sql = "UPDATE `categories` SET `name`='$name' WHERE id = $id";
    if($conn->query($sql)){
        header("location: categories.php");
        exit();
    }else{
        echo "fail";
    }
}
include "./includes/header.php";
?>
<div class="container mt-4 w-75">
    <div class="card shadow-lg">
        <div class="card-header">
            <h5>Edit Categories</h5>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="category" class="form-label fw-bold text-uppercase text-muted fs-7">Category Name</label>
                    <input
                        type="text"
                        class="form-control form-control-lg rounded-3 shadow-sm"
                        name="category"
                        value="<?= htmlspecialchars($category['name']) ?>"
                        maxlength="150"
                        placeholder="Enter category name"
                        required>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="./categories.php" class="btn btn-secondary px-4 py-2 rounded-3 fw-semibold">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm" name="update">Update Category</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php include "./includes/footer.php"; ?>
