<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $pdo = connect();
    $award_id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM personal_awards_won WHERE id = ?");
    $stmt->execute([$award_id]);
}

header('Location: personal_awards_won.php');
exit;
?>