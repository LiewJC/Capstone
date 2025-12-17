<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Select Date & Time</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
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

        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 0.5rem;
        }

        .flatpickr-calendar {
            max-width: 600px; 
            font-size: 18px;
        }
        #end_time {
            background-color: #eee;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<?php echo $__env->make('nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="flex-grow max-w-4xl mx-auto p-6">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Select Date & Time</h2>

    <?php if(session('error')): ?>
        <div class="mb-6 text-center text-red-600 font-semibold"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <?php
        $totalDuration = 0;
        foreach ($cartItems as $item) {
            $totalDuration += intval($item['service']['duration']);
        }
        $roundedDuration = ceil($totalDuration / 60) * 60;
    ?>

    <div class="text-center mb-8">
        <strong>Total Duration:</strong> <?php echo e($totalDuration); ?> mins (Rounded: <?php echo e($roundedDuration); ?> mins)
    </div>

    <div class="flex flex-col md:flex-row md:space-x-10 justify-center items-start mb-10">

        <!-- Calendar container -->
        <div id="calendar-container" class="mb-6 md:mb-0 p-6 bg-white rounded-lg border border-gray-300 shadow-sm">
            <label for="datePicker" class="block mb-2 font-semibold text-gray-700">Choose a Date:</label>
            <input type="text" id="datePicker" name="date" placeholder="Select Date" readonly required
                class="w-full cursor-pointer rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" />
        </div>

        <!-- Booking form -->
        <form id="bookingForm" method="GET" action="<?php echo e(route('payment.page')); ?>" class="w-full max-w-md bg-white rounded-lg border border-gray-300 p-6 shadow-sm">

            <input type="hidden" name="date" id="hidden_date" />
            <input type="hidden" name="start_time" id="hidden_start_time" />
            <input type="hidden" name="end_time" id="hidden_end_time" />

            <div class="mb-6">
                <label for="time_slot" class="block mb-2 font-semibold text-gray-700">Available Start Times:</label>
                <select name="time_slot" id="time_slot" required
                    class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Select a time slot --</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="end_time" class="block mb-2 font-semibold text-gray-700">End Time:</label>
                <input type="text" id="end_time" disabled
                    class="w-full rounded border border-gray-300 px-3 py-2 bg-gray-100" />
            </div>

            <button type="submit" id="proceedButton" disabled
                class="w-full bg-primary-700 hover:bg-primary-800 text-white font-semibold py-3 rounded transition disabled:opacity-50">
                Proceed to Payment
            </button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const schedules = <?php echo json_encode($schedules, 15, 512) ?>;
    const bookings = <?php echo json_encode($bookings, 15, 512) ?>;
    const cartItems = <?php echo json_encode($cartItems, 15, 512) ?>;

    let selectedDateStr = "";
    let roundedDuration = 0;

    let totalDuration = 0;
    cartItems.forEach(item => {
        totalDuration += parseInt(item.service.duration || 0);
    });
    roundedDuration = Math.ceil(totalDuration / 60) * 60;

    function timeToMinutes(timeStr) {
        const [h, m] = timeStr.split(":").map(Number);
        return h * 60 + m;
    }

    function minutesToTime(min) {
        const h = Math.floor(min / 60);
        const m = min % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    function isSlotAvailable(startMin, durationMin, scheduleEndMin, bookings) {
        const endMin = startMin + durationMin;
        if (endMin > scheduleEndMin) return false;

        for (const booking of bookings) {
            const bookingStart = timeToMinutes(booking.timeStart);
            const bookingEnd = timeToMinutes(booking.timeEnd);

            if ((startMin < bookingEnd) && (endMin > bookingStart)) {
                return false;
            }
        }

        return true;
    }

    flatpickr("#datePicker", {
        inline: true,
        minDate: new Date().fp_incr(1),
        maxDate: new Date().fp_incr(180),
        dateFormat: "Y-m-d",
        disable: [
            function(date) {
                const dayNames = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
                const dayName = dayNames[date.getDay()];
                return !schedules.find(s => s.day_of_week === dayName);
            }
        ],
        onChange: function(selectedDates, dateStr) {
            const date = selectedDates[0];
            selectedDateStr = dateStr;

            document.getElementById('hidden_date').value = dateStr;
            document.getElementById('hidden_start_time').value = "";
            document.getElementById('hidden_end_time').value = "";

            const dayName = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"][date.getDay()];
            const schedule = schedules.find(s => s.day_of_week === dayName);
            const storeBookings = bookings.filter(b => b.datetime.startsWith(dateStr));

            const select = document.getElementById('time_slot');
            const endTimeInput = document.getElementById('end_time');
            select.innerHTML = '<option value="">-- Select a time slot --</option>';
            endTimeInput.value = "";
            document.getElementById('proceedButton').disabled = true;

            if (!schedule) return;

            const scheduleStart = timeToMinutes(schedule.start_time);
            const scheduleEnd = timeToMinutes(schedule.end_time);

            for (let min = scheduleStart; min + roundedDuration <= scheduleEnd; min += 30) {
                if (isSlotAvailable(min, roundedDuration, scheduleEnd, storeBookings)) {
                    const slot = minutesToTime(min);
                    const opt = document.createElement("option");
                    opt.value = slot;
                    opt.text = slot;
                    select.appendChild(opt);
                }
            }
        }
    });

    document.getElementById('time_slot').addEventListener("change", function () {
        const startTime = this.value;
        if (startTime) {
            const startMin = timeToMinutes(startTime);
            const endMin = startMin + roundedDuration;
            const endTime = minutesToTime(endMin);

            document.getElementById('end_time').value = endTime;
            document.getElementById('hidden_start_time').value = startTime;
            document.getElementById('hidden_end_time').value = endTime;

            document.getElementById('proceedButton').disabled = false;
        } else {
            document.getElementById('end_time').value = "";
            document.getElementById('hidden_start_time').value = "";
            document.getElementById('hidden_end_time').value = "";
            document.getElementById('proceedButton').disabled = true;
        }
    });
</script>

</body>
</html>
<?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/select-date.blade.php ENDPATH**/ ?>