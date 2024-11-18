<?php
    require '../../partials/init.php';
    $new_path = '/pages/author/new_author.php';
    require ABSOLUTE_PATH.'/partials/create_link.php';
    $authors = mysqli_query($conn, "SELECT * FROM author");
?>

<section>
    <div class="container text-center">
        <div class="row">
            <div class="col-10">Nome do autor</div>
            <div class="col">Deletar</div>
        </div>
        <?php foreach ($authors as $author) : ?>
            <div class="row">
                <div class="col-10"><?= $author['name'] ?></div>
                <div class="col">
                    <form action="/PIE3/dbconnect/delete_author.php" method="post"><input type="hidden" name="id" value="<?= $author['id_author'] ?>"><button type="submit" class="btn btn-danger btn-sm"><i class='fa-solid fa-x'></i></button></form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>