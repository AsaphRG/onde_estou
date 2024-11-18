<?php
require '../../partials/init.php';
$new_path = '/pages/publisher/new_publisher.php';
require ABSOLUTE_PATH.'/partials/create_link.php';
$publishers = mysqli_query($conn, 'SELECT * FROM publisher');
?>
<section>
    <div class="container text-center">
        <div class="row">
            <div class="col-10">Nome da editora</div>
            <div class="col">Deletar</div>
        </div>
        <?php foreach ($publishers as $publisher) : ?>
            <div class="row">
                <div class="col-10"><?= $publisher['name'] ?></div>
                <div class="col">
                    <form action="/PIE3/dbconnect/delete_publisher.php" method="post"><input type="hidden" name="id" value="<?= $publisher['id_publisher'] ?>"><button type="submit" class="btn btn-danger btn-sm"><i class='fa-solid fa-x'></i></button></form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require ABSOLUTE_PATH.'/partials/end.php' ?>