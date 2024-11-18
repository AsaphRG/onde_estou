<?php
require "../config.php";

$id = $_POST['id'];

$query = "DELETE FROM author WHERE id_author=$id";

if ($conn->query($query) === TRUE) {
    header("Location: /PIE3/pages/author/authors.php?success=$id");
    exit();
} else {
    header("Location: /PIE3/pages/author/authors.php?error=$id");
    exit();
}
