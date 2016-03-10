<?php

session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: ./admin/login.php');
    exit();
}
//include database connection
    require_once('./includes/connection.php');
// post count
$post_count = $db->query("SELECT * FROM posts");
// comment count
$comment_count = $db->query("SELECT * FROM comments");

if(isset($_POST['submit'])) {
    $newCategory = $_POST['newCategory'];
    if(!empty($newCategory)) {
            $query = $db->prepare("INSERT INTO categories (category) VALUES (?)");
            $query->bindParam(1, $newCategory);
            $query->execute();
            $newCategory = filter_input(INPUT_POST, 'newCategory', FILTER_SANITIZE_URL);
        if($query) {
            echo "New Category Added";
        } else {
            echo "Error";
        }
    } else {
        echo "Missing New Category";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
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
    #mainContent {
        clear: both;
        margin-top: 5px;
        font-size: 25px;
    }
    #header {
        height: 80px;
        line-height: 80px;
    }
    #container #header h1 {
        font-size: 45px;
        margin: 0;
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
        <div id="mainContent">
            <table>
                <tr>
                    <td>Total Blog Posts</td>
                    <td><?php echo $post_count->rowCount(); ?></td>
                </tr>
                <br>
                <tr>
                    <td>Total Comments</td>
                    <td><?php echo $comment_count->rowCount(); ?></td>
                </tr>
            </table>
            <br>
            <div id="categoryForm">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                    <label for="category">Add New Category: </label><input type="text" name="newCategory" /> 
                    <input type="submit" name="submit" value="submit" />
                </form>
            </div>
        </div>
    </div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</body>
</html>
