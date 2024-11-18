<?php
require '../../partials/init.php';
$new_path = '/pages/book/new_book.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$books = mysqli_query($conn, 'SELECT * FROM book');
?>

<section>
    <div class="item">
        <p><?= $book['name'] ?></p>
    </div>
    <?php
    $id = $book['id_book'];
    $authors = mysqli_query($conn, "SELECT * FROM book_author WHERE id_book = $id");
    foreach($authors as $author) : ?>
    
    <?php endforeach; ?>
</section>
<?php require ABSOLUTE_PATH.'/partials/end.php' ?>