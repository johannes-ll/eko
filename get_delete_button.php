<?php
session_start();

$authorId = $_GET['authorId'];

if (
    isset($_SESSION['role'], $_SESSION['user_id']) &&
    (
        $_SESSION['role'] === 'admin' ||
        $_SESSION['user_id'] == $authorId
    )
) {

    echo '<button class="delete-btn">Delete</button>';
}