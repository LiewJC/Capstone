<!DOCTYPE html>
<html>
<head>
    <title>Payment Cancelled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .cancel-box {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255,0,0,0.3);
            text-align: center;
        }

        h1 {
            color: #dc3545;
        }

        p {
            font-size: 18px;
            margin-top: 15px;
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: white;
            background-color: #dc3545;
            padding: 12px 25px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="cancel-box">
    <h1>Payment Cancelled</h1>
    <p>Your payment was cancelled or failed. You can try again anytime.</p>
    <a href="{{ url('/') }}">Return to Home</a>
</div>

</body>
</html>
