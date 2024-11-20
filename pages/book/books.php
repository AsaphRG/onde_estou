<?php
require '../../partials/init.php';
$new_path = '/pages/book/new_book.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$books = mysqli_query($conn, 'SELECT * FROM book');

?>

<section class="list-items">
    <div class="container text-center">
        <div class="row">
            <div class="col-6">Nome do livro</div>
            <div class="col">Página atual</div>
            <div class="col">Finalizado?</div>
            <div class="col">Deletar</div>
        </div>
        <?php foreach($books as $book) : ?>
            <div class="row">
                <a href="/PIE3/pages/book/book.php?id=<?= $book['id_book'] ?>" class="col-6">
                    <div>
                        <p><?= $book['title'] ?></p>
                    </div>
                </a>
                <a href="/PIE3/pages/book/book.php?id=<?= $book['id_book'] ?>" class="col">
                    <div>
                        <p><?= $book['actual_page'] ?></p>
                    </div>
                </a>
                <a href="/PIE3/pages/book/book.php?id=<?= $book['id_book'] ?>" class="col">
                    <div>
                        <p><?= $book['finished'] ? 'Finalizado' : 'Não finalizado' ?></p>
                    </div>
                </a>
                <div class="col">
                    <form action="/PIE3/dbconnect/delete_book.php" method="post"><input type="hidden" name="id" value="<?= $book['id_book'] ?>"><button type="submit" class="btn btn-danger btn-sm"><i class='fa-solid fa-x'></i></button></form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require ABSOLUTE_PATH.'/partials/end.php' ?>