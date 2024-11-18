
<?php
require_once 'config.php';
require ABSOLUTE_PATH.'/partials/init.php';
?>

    <section>
        <h3>Bem vindo!</h3>
        <p>Este é o sistema de controle de livros, páginas e anotações.</p>
        <p>Este projeto é destinado à matéria de Projeto Integrador Extensionista 3, Universidade de Marília, desenvolvido pelo aluno Asaph Resina Gil.</p>
        <p>A proposta do PIE3 é o desenvolvimento de um sistema que possua as operações básicas de um banco de dados, um CRUD. Sendo necessário o desenvolvimento em uma temática específica, diferente da demonstrada em aula junto ao professor.</p>
        <p>Desta forma acabei por pensar num sistema onde eu pudesse registrar minhas leituras, criando um ambiente para registro da página atual, criação de anotações e controle dos livros lidos.</p>
        <p>Para desenvolver este sistema utilizei as seguintes tecnologias:</p>
        <ul>
            <li>HTML</li>
            <li>CSS</li>
            <li>Javascript</li>
            <li>PHP</li>
            <li>MariaDB</li>
            <li>Bootstrap</li>
        </ul>
        <p>Apresentado o projeto, sem mais delongas, vamos ao sistema.</p>
        <p>Antes de tudo para conseguir cadastrar um livro cadastre primeiro seu autor e sua editora.</p>
        <ul>
            <li><a href="<?= 'pages/author/authors.php' ?>">Autores</a></li>
            <li><a href="<?= 'pages/publisher/publishers.php' ?>">Editoras</a></li>
            <li><a href="<?= 'pages/book/books.php' ?>">Livros</a></li>
        </ul>
        <p>De bônus eu vou colocar um caminho pro banco de dados aqui embaixo sem toda a estilização pra ver se garante minha nota.</p>
        <p><a href="<?= 'pages/the_db.php' ?>">Banco de dados</a></p>
    </section>
<?php require ABSOLUTE_PATH.'/partials/end.php'; ?>