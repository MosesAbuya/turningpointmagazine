<?php include('connection2.php'); ?>
<h1>BASE_URL Test</h1>
<p>BASE_URL value: "<?= BASE_URL ?>"</p>
<p>HTTP_HOST: "<?= $_SERVER['HTTP_HOST'] ?>"</p>
<p>SCRIPT_NAME: "<?= $_SERVER['SCRIPT_NAME'] ?>"</p>
<p><a href="<?= BASE_URL ?>about">Click to test About link</a></p>
