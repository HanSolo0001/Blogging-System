<?php

//include database connection
    require_once('./includes/connect.php');
// get record of database
$record_count = count($db->query("SELECT COUNT(post_id) FROM posts")->fetchAll());
//amount displayed
$per_page = 5;
//number of pages
$pages = ceil($record_count/$per_page);

// get page number
if(isset($_GET['p']) && is_numeric($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1;
}
if($page<=0) {
    $start = 0;
} else {
    $start = $page * $per_page - $per_page;
}
$prev = $page - 1;
$next = $page + 1;

$query = $db->prepare("SELECT post_id, title, LEFT(body, 300) AS body, category FROM posts INNER JOIN categories ON categories.category_id=posts.category_id ORDER BY post_id DESC LIMIT $start, $per_page");
$query->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
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
</style>
<body>
    <div id="menu">
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="new_post.php">Create New Post</a></li>
            <li><a href="#">Delete Post</a></li>
            <li><a href="./admin/logout.php">Log Out</a></li>
            <li><a href="./index_blog.php">Blog Home Page</a></li>
        </ul>
    </div>
    <div id="container">
        <?php 
            while($row = $query->fetch()):
            $lastspace = strrpos($row['body'], ' ');
        ?>
        <article>
            <h2><?php echo $row['title']?></h2>
            <p><?php echo substr($row['body'], 0, $lastspace)."<a href='post.php?id={$row['post_id']}'> Read More</a>"?></p>
            <p>Category: <?php echo $row['category']?></p>
        </article>
        <?php
            endwhile;
        ?>
        <?php
            if($prev > 0) {
                echo "<a href'index_blog.php?p=$prev'>Prev</a>";
            }
            if($page < $pages) {
                echo "<a href'index_blog.php?p=$next'>Next</a>";
            }
        ?>
    </div>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
</body>
</html>