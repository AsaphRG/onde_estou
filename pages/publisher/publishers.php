<?php
require '../../partials/init.php';
$new_path = '/pages/publisher/new_publisher.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$publishers = mysqli_query($conn, 'SELECT * FROM publisher');
?>
<section class="list-items">
    <div class="container text-center">
        <div class="row">
            <div class="col-10">Nome da editora</div>
            <div class="col">Deletar</div>
        </div>
        <?php foreach ($publishers as $publisher) : ?>
            <div class="row">
                <a class="col-10" href="/PIE3/pages/publisher/publisher.php?id=<?= $publisher['id_publisher'] ?>">
                    <div><?= $publisher['name'] ?></div>
                </a>
                <div class="col">
                    <form action="/PIE3/dbconnect/delete_publisher.php" method="post"><input type="hidden" name="csrf_token" value="<?= $csrf_token ?>"><input type="hidden" name="id" value="<?= $publisher['id_publisher'] ?>"><button type="submit" class="btn btn-danger btn-sm"><i class='fa-solid fa-xmark'></i></button></form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>