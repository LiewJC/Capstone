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
        <div class="mt-6">
    <h3 class="text-lg font-semibold mb-2">Vehicle Information</h3>

    <form method="POST" action="<?php echo e(route('update.vehicle.info')); ?>" class="flex flex-col gap-2">
        <?php echo csrf_field(); ?>
        <textarea name="vehicle_info" rows="3" 
            class="border border-gray-300 rounded-md p-2 w-full focus:ring-2 focus:ring-primary-500 focus:outline-none"
            placeholder="Enter your vehicle details (e.g., Toyota Vios 2020, Plate ABC1234)"><?php echo e(old('vehicle_info', $vehicleInfo)); ?></textarea>

        <button type="submit" 
            class="bg-primary-700 hover:bg-primary-800 text-white font-semibold py-2 px-4 rounded-md w-fit transition duration-200">
            Update Vehicle Info
        </button>
    </form>

    <?php if(session('vehicle_success')): ?>
        <p class="text-green-600 mt-2"><?php echo e(session('vehicle_success')); ?></p>
    <?php endif; ?>
</div>
<br/>
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

        <div class="payment-options mt-6" role="radiogroup" aria-label="Select Payment Method">
    <div class="payment-option" data-method="card" tabindex="0" role="radio" aria-checked="false" aria-labelledby="label-card">
        <input type="radio" name="payment_method" id="pm-card" value="card" hidden>
        <img src="https://cdn-icons-png.flaticon.com/512/633/633611.png" alt="Card Icon" />
        <label id="label-card" for="pm-card">Credit / Debit Card</label>
    </div>
    <div class="payment-option" data-method="ewallet" tabindex="0" role="radio" aria-checked="false" aria-labelledby="label-ewallet">
        <input type="radio" name="payment_method" id="pm-ewallet" value="ewallet" hidden>
        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041873.png" alt="E-Wallet Icon" />
        <label id="label-ewallet" for="pm-ewallet">E-Wallet</label>
    </div>
    <div class="payment-option" data-method="cash" tabindex="0" role="radio" aria-checked="false" aria-labelledby="label-cash">
        <input type="radio" name="payment_method" id="pm-cash" value="cash" hidden>
        <img src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/svgs/solid/money-bill-wave.svg" alt="Cash Icon" />
        <label id="label-cash" for="pm-cash">Cash on the Spot</label>
    </div>
</div>

<!-- Credit / Debit Card Form -->
<form method="POST" action="<?php echo e(route('card.checkout')); ?>" id="card-form" style="display: none;" class="mt-6">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="date" value="<?php echo e($date); ?>">
    <input type="hidden" name="start_time" value="<?php echo e($startTime); ?>">
    <input type="hidden" name="end_time" value="<?php echo e($endTime); ?>">
    <input type="hidden" name="store_id" value="<?php echo e($store->store_id); ?>">
    <input type="hidden" name="final_price" value="<?php echo e($finalPrice); ?>">
    <?php if(session('discount')): ?>
        <input type="hidden" name="discount_code" value="<?php echo e(session('discount')['code']); ?>">
    <?php endif; ?>

    <div class="grid gap-3">
        <div>
            <label class="block font-medium">Card Number</label>
            <input type="text" name="card_number" maxlength="16" required 
                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-primary-500 focus:outline-none"
                placeholder="1234 5678 9012 3456">
        </div>

        <div class="flex gap-3">
            <div class="flex-1">
                <label class="block font-medium">Expiry Date</label>
                <input type="text" name="expiry_date" required 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-primary-500 focus:outline-none"
                    placeholder="MM/YY">
            </div>
            <div class="w-1/3">
                <label class="block font-medium">CCV</label>
                <input type="text" name="ccv" maxlength="4" required 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-primary-500 focus:outline-none"
                    placeholder="123">
            </div>
        </div>
    </div>

    <button type="submit" class="pay-button">Pay with Credit / Debit Card</button>
</form>

<form method="POST" action="<?php echo e(route('ewallet.checkout')); ?>" id="ewallet-form" style="display: none;" class="mt-6">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="date" value="<?php echo e($date); ?>">
    <input type="hidden" name="start_time" value="<?php echo e($startTime); ?>">
    <input type="hidden" name="end_time" value="<?php echo e($endTime); ?>">
    <input type="hidden" name="store_id" value="<?php echo e($store->store_id); ?>">
    <input type="hidden" name="final_price" value="<?php echo e($finalPrice); ?>">
    <?php if(session('discount')): ?>
        <input type="hidden" name="discount_code" value="<?php echo e(session('discount')['code']); ?>">
    <?php endif; ?>

    <div class="grid gap-3">
        <div>
            <label class="block font-medium">Select E-Wallet</label>
            <select name="ewallet_type" required 
                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-primary-500 focus:outline-none">
                <option value="">-- Select E-Wallet --</option>
                <option value="TnG">Touch 'n Go</option>
                <option value="GrabPay">GrabPay</option>
                <option value="Boost">Boost</option>
                <option value="ShopeePay">ShopeePay</option>
            </select>
        </div>

    </div>

    <button type="submit" class="pay-button bg-blue-600 hover:bg-blue-700">Submit E-Wallet Payment</button>
</form>

<form method="POST" action="<?php echo e(route('cash.checkout')); ?>" id="cash-form" style="display: none;" class="mt-6">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="date" value="<?php echo e($date); ?>">
    <input type="hidden" name="start_time" value="<?php echo e($startTime); ?>">
    <input type="hidden" name="end_time" value="<?php echo e($endTime); ?>">
    <input type="hidden" name="store_id" value="<?php echo e($store->store_id); ?>">
    <input type="hidden" name="final_price" value="<?php echo e($finalPrice); ?>">
    <?php if(session('discount')): ?>
        <input type="hidden" name="discount_code" value="<?php echo e(session('discount')['code']); ?>">
    <?php endif; ?>

    <button type="submit" class="pay-button bg-primary-700 hover:bg-primary-800">Confirm Cash Booking</button>
</form>
<?php if($errors->any()): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
        <ul class="list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

    </div>

    <script>
    const paymentOptions = document.querySelectorAll('.payment-option');
    const cardForm = document.getElementById('card-form');
    const ewalletForm = document.getElementById('ewallet-form');
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
            selectedOption.setAttribute('aria-checked', 'true');
        }

        cardForm.style.display = 'none';
        ewalletForm.style.display = 'none';
        cashForm.style.display = 'none';

        if (method === 'card') {
            cardForm.style.display = 'block';
        } else if (method === 'ewallet') {
            ewalletForm.style.display = 'block';
        } else if (method === 'cash') {
            cashForm.style.display = 'block';
        }
    }
</script>


</body>
        <footer class="bg-primary-700 text-white text-center py-4">
            <p>&copy; <?php echo e(date('Y')); ?> Capstone. All Rights Reserved.</p>
        </footer>

</html>
<?php /**PATH C:\xampp\htdocs\CapStone\resources\views/payment.blade.php ENDPATH**/ ?>