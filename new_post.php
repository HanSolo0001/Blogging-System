<?php

session_start();
//include database connection
    require_once('./includes/connect.php');
if(!isset($_SESSION['user_id'])) {
    header('Location: ./admin/login.php');
    exit();
}
if(isset($_POST['submit'])) {
    //get the blog data
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category = $_POST['category'];
    $query = $db->prepare("INSERT INTO posts(user_id, title, body, category_id, posted) VALUES (:user_id, :title, :body, :category, :date)");
    $query->bindParam(':user_id', $user_id);
    $query->bindParam(':title', $title);
    $query->bindParam(':body', $body);
    $query->bindParam(':category', $category);
    $query->bindParam(':date', $date);
    $user_id = $_SESSION['user_id'];
    date_default_timezone_set("America/Louisville");
    $date = date("Y-m-d H:i:s");
    $body = htmlentities($body);
    $query->execute();
    if($title && $body && $category) {
        if($query) {
            echo "Post added";
        } else {
            echo "Error";
        }
    } else {
        echo "Missing information";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<style>
    #container {
        padding: 10px;
        width: 800px;
        margin: auto;
        backgroung: white;
    }
    #menu {
        height: 40px;
        line-height: 40px;
    }
    #menu ul {
        margin: 0;
        padding: 0;
    }
    #menu ul li {
        display: inline;
        list-style: none;
        margin-right: 10px;
        font-size: 18px;
    }
    #wrapper {
        margin: auto;
        width: 800px;
    }
    label {
        display: block;
    }
</style>
</head>
<body>
    <div id="container">
        <div id="menu">
            <ul>
                <li><a href="./index.php">Home</a></li>
                <li><a href="new_post.php">Create New Post</a></li>
                <li><a href="#">Delete Post</a></li>
                <li><a href="./admin/logout.php">Log Out</a></li>
                <li><a href="./index_blog.php">Blog Home Page</a></li>
            </ul>
        </div>
    </div>
    <div id="wrapper">
        <div id="content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                <label>Title:</label><input type="text" name="title" />
                <label for="body">Body:</label>
                <textarea name="body" cols=50 rows=10></textarea>
                <label>Category:</label>
                <select name="category">
                    <?php
                        $query = $db->query("SELECT * FROM categories");
                        while($row = $query->fetchObject()) {
                            echo "<option value='".$row->category_id."'>".$row->category."</option>";
                        }
                    ?>
                </select>
                <br>
                <br>
                <input type="submit" name='submit' value="Submit" />
            </form>
        </div>
    </div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</body>
</html>