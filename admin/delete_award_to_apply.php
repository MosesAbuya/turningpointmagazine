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
    $stmt = $pdo->prepare("DELETE FROM awards_to_apply WHERE id = ?");
    $stmt->execute([$award_id]);
}

header('Location: awards_to_apply.php');
exit;
?>