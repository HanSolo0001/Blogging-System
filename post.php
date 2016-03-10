<?php

if(!isset($_GET['id'])) {
    header('Location: index_blog.php');
    exit();
} else {
    $id = $_GET['id'];
}
//include database connection
   require_once('./includes/connection.php');
if(!is_numeric($id)) {
    header('Location: index_blog.php');
}
$query = $db->prepare("SELECT title, body FROM posts WHERE post_id=:post_id");
$query->bindParam(':post_id', $id);
$query->execute();
$results = $query->fetch();
if(count($results) == 0) {
    header('Location: index_blog.php');
    exit();
}
// define variables and set to empty values
$nameErr = $emailErr = $commentErr = "";
$name = $email = $comment = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed"; 
        }
    }
    
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
        }
    }
    if (empty($_POST["comment"])) {
        $comment = "";
        $commentErr = "Comment is required";
    } else {
        $comment = test_input($_POST["comment"]);
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if($addComment = $db->prepare("INSERT INTO comments(post_id, email_add, name, comment) VALUES (:post_id, :email, :name, :comment)")) {
    $addComment->bindParam(':post_id', $id);
    $addComment->bindParam(':email', $email);
    $addComment->bindParam(':name', $name);
    $addComment->bindParam(':comment', $comment);
    $addComment->execute();
    echo "Thank you! Your comment was added.";
    $addComment->closeCursor();   
} else {
    echo "Error";
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
<style type="text/css">
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
    label {
        display: block;
    }
    .error {
        color: #FF0000;
    }
</style>
</head>
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
            <div id="post">
                <?php
                    echo "<h2>".$results['title']."</h2>";
                    echo "<p>".$results['body']."</p>";
                ?>
            </div>
            <hr>
            <div id="addComments">
                <p><span class="error">* required field.</span></p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?id=$id"?>" method="POST">
                    Name: <input type="text" name="name">
                    <span class="error">* <?php echo $nameErr;?></span>
                    <br><br>
                    E-mail: <input type="text" name="email">
                    <span class="error">* <?php echo $emailErr;?></span>
                    <br><br>
                    Comment: <textarea name="comment" rows="5" cols="40"></textarea>
                    <span class="error">* <?php echo $commentErr;?></span>
                    <br><br>
                    <input type="hidden" name="post_id" value="<?php echo $id?>" />
                    <input type="submit" name="submit" value="Submit" />
                </form>    
            </div>
            <hr>    
            <div id="Comments">
                <?php
                    $query = $db->query("SELECT * FROM comments WHERE post_id='$id' ORDER BY comment_id DESC");
                    while($row = $query->fetchObject()):
                ?>
                <div>
                    <h5><?php echo $row->name?></h5>
                    <blockquote><?php echo $row->comment?></blockquote>
                </div>
                <?php
                    endwhile;        
                ?>
            </div>
        </div>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    </body>
</html>
