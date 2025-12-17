

<?php $__env->startSection('title', 'Reports & Analytics'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Reports & Analytics</h1>

    <?php if(session('error')): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-6">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <form method="GET" action="<?php echo e(route('admin.report')); ?>" class="mb-8 flex flex-wrap items-center space-x-4 space-y-2">
        <label class="flex flex-col text-sm font-medium text-gray-700">
            From:
            <input type="date" name="from" value="<?php echo e(request('from')); ?>" class="border border-gray-300 rounded px-3 py-1" />
        </label>
        <label class="flex flex-col text-sm font-medium text-gray-700">
            To:
            <input type="date" name="to" value="<?php echo e(request('to')); ?>" class="border border-gray-300 rounded px-3 py-1" />
        </label>
        <button type="submit" class="bg-primary-500 hover:bg-primary-700 text-white px-4 py-2 rounded">
            Filter
        </button>
    </form>

    <div class="mb-10 text-center space-y-2">
        <h3 class="text-xl font-semibold text-gray-700">Total Sales: <span class="text-primary-700">RM
                <?php echo e(number_format($totalSales, 2)); ?></span></h3>
        <h3 class="text-xl font-semibold text-gray-700">Total Bookings: <span
                class="text-primary-700"><?php echo e($totalBookings); ?></span></h3>
    </div>

    <div class="space-y-12 max-w-5xl mx-auto">

        <section>
            <h2 class="text-2xl font-semibold text-primary-700 mb-4">Daily Sales</h2>
            <div class="bg-white rounded shadow p-6">
                <canvas id="barSalesChart" class="w-full h-96"></canvas>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-primary-700 mb-4">Services Booked Proportion</h2>
            <div class="bg-white rounded shadow p-6 max-w-md mx-auto">
                <canvas id="pieServicesChart" class="w-full h-96"></canvas>
            </div>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyLabels = <?php echo json_encode($dailySalesLabels); ?>;
        const dailySalesData = <?php echo json_encode($dailySalesData); ?>;

        const serviceLabels = <?php echo json_encode($serviceNames); ?>;
        const serviceCounts = <?php echo json_encode($serviceCounts); ?>;

        new Chart(document.getElementById('barSalesChart'), {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Sales (RM)',
                    data: dailySalesData,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)', // Tailwind primary-500
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'RM ' + value
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('pieServicesChart'), {
            type: 'pie',
            data: {
                labels: serviceLabels,
                datasets: [{
                    data: serviceCounts,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#E7E9ED'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.parsed} bookings`
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/reports.blade.php ENDPATH**/ ?>