<?php

class Book {
    protected $title;
    protected $subtitle;
    protected $id_publisher;
    protected $authors;
    protected $actual_page;
    protected $finished;

    public function __construct($title, $id_publisher, $authors, $actual_page, $finished, $subtitle = null) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->id_publisher = $id_publisher;
        $this->authors = $authors;
        $this->actual_page = $actual_page;
        $this->finished = $finished;
    }

    static public function create_book($conn, $title, $publication_year,$id_publisher, $authors, $actual_page, $finished, $subtitle = null) {
        $sql = new $conn->prepare("INSERT INTO book (title, subtitle, publication_year, id_publisher, actual_page, finished) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param('ssiiib', $title, $subtitle, $publication_year, $id_publisher, $actual_page, $finished);
        $sql->execute();
    }
}
