<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6ffe6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .success-box {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,128,0,0.3);
            text-align: center;
        }
        h1 {
            color: #28a745;
        }
        p {
            font-size: 18px;
            margin-top: 15px;
        }
        a {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: white;
            background-color: #28a745;
            padding: 12px 25px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="success-box">
    <h1>Payment Successful!</h1>
    <p>Thank you for your booking. Your payment has been received.</p>
    <a href="<?php echo e(url('/')); ?>">Return to Home</a>
</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\CapStone\resources\views/payment_success.blade.php ENDPATH**/ ?>