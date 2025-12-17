<!DOCTYPE html>
<html>

<head>
    <title>Confirm Payment</title>
      <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            100: '#dbeafe',
                            500: '#3b82f6',
                            700: '#1d4ed8',
                        },
                        secondary: {
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 0.5rem;
        }

        .summary-box {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            background-color: #f9f9f9;
        }

        .summary-box h2 {
            margin-bottom: 20px;
        }

        .summary-box p {
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .pay-button{
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .pay-button:hover {
            background-color: #218838;
        }

        .discount-form {
            margin-top: 20px;
        }

        .discount-info {
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }

        .error-message {
            margin-top: 10px;
            color: red;
        }

        .payment-options {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .payment-option {
            flex: 1 1 200px;
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            user-select: none;
        }

        .payment-option:hover {
            border-color: #007bff;
            background-color: #f4faff;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option.selected {
            border-color: #007bff;
            background-color: #e6f0ff;
            box-shadow: 0 0 8px #007bff;
        }

        .payment-option img {
            max-width: 60px;
            max-height: 40px;
            object-fit: contain;
        }

        .payment-option label {
            font-weight: bold;
            cursor: pointer;
            display: block;
        }
    </style>
</head>

<?php echo $__env->make('nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body>

    <div class="summary-box">
        <h2>Confirm Your Booking</h2>

        <p><strong>Store:</strong> <?php echo e($store->name); ?></p>
        <p><strong>Address:</strong> <?php echo e($store->address); ?></p>

        <p><strong>Date:</strong> <?php echo e($date); ?></p>
        <p><strong>Start Time:</strong> <?php echo e($startTime); ?></p>
        <p><strong>End Time:</strong> <?php echo e($endTime); ?></p>

        <h3>Selected Services</h3>

        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Duration (mins)</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $totalDuration = 0;
                    $totalPrice = 0;
                ?>

                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->service->name); ?></td>
                        <td><?php echo e($item->service->duration); ?></td>
                        <td><?php echo e(number_format($item->service->price, 2)); ?></td>
                    </tr>
                    <?php
                        $totalDuration += (int) $item->service->duration;
                        $totalPrice += $item->service->price;
                    ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <tr>
                    <td class="total">Total</td>
                    <td class="total"><?php echo e($totalDuration); ?> mins</td>
                    <td class="total">RM<?php echo e(number_format($totalPrice, 2)); ?></td>
                </tr>

                <?php if(session('discount')): ?>
                    <?php
                        $discount = session('discount');
                        $discountAmount = $totalPrice * ($discount['percentage'] / 100);
                        $finalPrice = $totalPrice - $discountAmount;
                    ?>
                    <tr>
                        <td colspan="2" class="total">Discount (<?php echo e($discount['code']); ?> - <?php echo e($discount['percentage']); ?>%)</td>
                        <td class="total">- RM<?php echo e(number_format($discountAmount, 2)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="total">Total After Discount</td>
                        <td class="total">RM<?php echo e(number_format($finalPrice, 2)); ?></td>
                    </tr>
                <?php else: ?>
                    <?php $finalPrice = $totalPrice; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <form method="POST" action="<?php echo e(route('apply.discount')); ?>" class="discount-form">
            <?php echo csrf_field(); ?>
            <label for="discount_code">Have a discount code?</label><br>
            <input type="text" name="discount_code" placeholder="Enter code" required>
            <button type="submit"
    class="bg-primary-700 hover:bg-primary-800 text-white font-semibold py-1.5 px-3 rounded transition duration-200">
    Apply Discount
</button>


            <?php if(session('error')): ?>
                <div class="error-message"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <?php if(session('discount')): ?>
                <div class="discount-info">Discount "<?php echo e(session('discount')['code']); ?>" applied!</div>
            <?php endif; ?>
        </form>

        <div class="payment-options" role="radiogroup" aria-label="Select Payment Method">
            <div class="payment-option" data-method="stripe" tabindex="0" role="radio" aria-checked="false" aria-labelledby="label-stripe">
                <input type="radio" name="payment_method" id="pm-stripe" value="stripe" hidden>
                <img src="https://stripe.com/img/v3/home/twitter.png
" alt="Stripe Logo" />
                <label id="label-stripe" for="pm-stripe">Credit/Debit Card (Stripe)</label>
            </div>
            <div class="payment-option" data-method="paypal" tabindex="0" role="radio" aria-checked="false" aria-labelledby="label-paypal">
                <input type="radio" name="payment_method" id="pm-paypal" value="paypal" hidden>
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal Logo" />
                <label id="label-paypal" for="pm-paypal">PayPal</label>
            </div>
            <div class="payment-option" data-method="cash" tabindex="0" role="radio" aria-checked="false" aria-labelledby="label-cash">
                <input type="radio" name="payment_method" id="pm-cash" value="cash" hidden>
                <img src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/svgs/solid/money-bill-wave.svg
" alt="Cash Icon" />
                <label id="label-cash" for="pm-cash">Cash on the Spot</label>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('stripe.checkout')); ?>" id="stripe-form" style="display: none;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($date); ?>">
            <input type="hidden" name="start_time" value="<?php echo e($startTime); ?>">
            <input type="hidden" name="end_time" value="<?php echo e($endTime); ?>">
            <input type="hidden" name="store_id" value="<?php echo e($store->store_id); ?>">
            <input type="hidden" name="final_price" value="<?php echo e($finalPrice); ?>">
            <?php if(session('discount')): ?>
                <input type="hidden" name="discount_code" value="<?php echo e(session('discount')['code']); ?>">
            <?php endif; ?>

            <button type="submit" class="pay-button">Pay with Credit/Debit Card</button>
        </form>

        <div id="paypal-form" style="display: none; margin-top: 20px;">
            <div id="paypal-button-container"></div>
        </div>

        <form method="POST" action="<?php echo e(route('cash.checkout')); ?>" id="cash-form" style="display: none;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($date); ?>">
            <input type="hidden" name="start_time" value="<?php echo e($startTime); ?>">
            <input type="hidden" name="end_time" value="<?php echo e($endTime); ?>">
            <input type="hidden" name="store_id" value="<?php echo e($store->store_id); ?>">
            <input type="hidden" name="final_price" value="<?php echo e($finalPrice); ?>">
            <?php if(session('discount')): ?>
                <input type="hidden" name="discount_code" value="<?php echo e(session('discount')['code']); ?>">
            <?php endif; ?>

            <button type="submit" class="pay-button" style="background-color: #007bff;">Confirm Cash Booking</button>
        </form>

    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=AeYuBMu7lNjXkKNX_2kfRvNE6kgu7yIA2TfnY7HDsAxOnzhucIhjlx-4y89aDkRcTDagZ7-sLkiMuxG0&currency=MYR"></script>

    <script>
        const paymentOptions = document.querySelectorAll('.payment-option');
        const stripeForm = document.getElementById('stripe-form');
        const paypalForm = document.getElementById('paypal-form');
        const cashForm = document.getElementById('cash-form');

        paymentOptions.forEach(option => {
            option.addEventListener('click', () => {
                selectPayment(option.dataset.method);
            });
            option.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectPayment(option.dataset.method);
                }
            });
        });

        function selectPayment(method) {
            paymentOptions.forEach(opt => {
                opt.classList.remove('selected');
                opt.setAttribute('aria-checked', 'false');
            });
            const selectedOption = [...paymentOptions].find(opt => opt.dataset.method === method);
            if (selectedOption) {
                selectedOption.classList.add('selected');
                selectedOption.querySelector('input[type=radio]').checked = true;
                selectedOption.setAttribute('aria-checked', 'true');
            }

            stripeForm.style.display = 'none';
            paypalForm.style.display = 'none';
            cashForm.style.display = 'none';

            if (method === 'stripe') {
                stripeForm.style.display = 'block';
            } else if (method === 'paypal') {
                paypalForm.style.display = 'block';
            } else if (method === 'cash') {
                cashForm.style.display = 'block';
            }
        }

        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo e(number_format($finalPrice, 2, ".", "")); ?>'
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert('Payment completed by ' + details.payer.name.given_name);

                    const bookingData = {
                        store_id: "<?php echo e($store->store_id); ?>",
                        date: "<?php echo e($date); ?>",
                        start_time: "<?php echo e($startTime); ?>",
                        end_time: "<?php echo e($endTime); ?>",
                        final_price: "<?php echo e($finalPrice); ?>",
                        discount_code: "<?php echo e(session('discount')['code'] ?? ''); ?>"
                    };

                    const params = new URLSearchParams(bookingData).toString();
                    window.location.href = `/payment/success1?${params}`;
                });
            },
            onCancel: function (data) {
                alert('Payment cancelled.');
            },
            onError: function (err) {
                console.error(err);
                alert('An error occurred during PayPal payment.');
            }
        }).render('#paypal-button-container');
    </script>

</body>
        <footer class="bg-primary-700 text-white text-center py-4">
            <p>&copy; <?php echo e(date('Y')); ?> Capstone. All Rights Reserved.</p>
        </footer>

</html>
<?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/payment.blade.php ENDPATH**/ ?>