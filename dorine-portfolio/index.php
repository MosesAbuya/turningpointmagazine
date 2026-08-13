<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurningPoint - Under Maintenance</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f2f2f2;
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .maintenance-container {
            background-color: #e60000;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .maintenance-container h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .maintenance-container p {
            font-size: 1.25rem;
            margin-bottom: 30px;
        }

        .maintenance-container .btn {
            background-color: #fff;
            color: #e60000;
            border-radius: 20px;
            font-size: 1.1rem;
            padding: 10px 30px;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .maintenance-container .btn:hover {
            background-color: #d8d8d8;
            color: #e60000;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9rem;
            color: #fff;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="maintenance-container">
        <h1>TurningPoint is Under Maintenance</h1>
        <p>We're currently working on some improvements to make your experience even better. Please check back later.</p>
        <a href="mailto:support@turningpoint.com" class="btn">Contact Support</a>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> TurningPoint. All rights reserved.</p>
        <p><a href="mailto:support@turningpoint.com">Email Support</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
