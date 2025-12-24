<?php $__env->startSection('content'); ?>
    <div class="card">
        <h2>Welcome back, <?php echo e(Auth::user()->name); ?></h2>
        <div class="card-subtitle">
            You are logged in as <strong><?php echo e(Auth::user()->email); ?></strong>. Use the quick links and metrics below to
            manage your real estate portfolio.
        </div>

        <div class="card-grid">
            <div class="metric-card">
                <div class="metric-label">Projects</div>
                <div class="metric-value"><?php echo e(\App\Models\Project::count()); ?></div>
                <div class="metric-trend">Manage locations, unit counts, and plans.</div>
                <div style="margin-top:10px;">
                    <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-primary">View Projects</a>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-label">Units</div>
                <div class="metric-value"><?php echo e(\App\Models\Unit::count()); ?></div>
                <div class="metric-trend">Track availability and pricing.</div>
                <div style="margin-top:10px;">
                    <a href="<?php echo e(route('units.index')); ?>" class="btn btn-primary">View Units</a>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-label">Active Bookings</div>
                <div class="metric-value"><?php echo e(\App\Models\Booking::where('status', 'booked')->count()); ?></div>
                <div class="metric-trend">Current customers with installment schedules.</div>
                <div style="margin-top:10px;">
                    <a href="<?php echo e(route('bookings.index')); ?>" class="btn btn-primary">View Bookings</a>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-label">Pending Reminders</div>
                <div class="metric-value"><?php echo e(\App\Models\Reminder::where('status', 'pending')->count()); ?></div>
                <div class="metric-trend">Upcoming payment follow-ups.</div>
                <div style="margin-top:10px;">
                    <a href="<?php echo e(route('reminders.index')); ?>" class="btn btn-primary">View Reminders</a>
                </div>
            </div>
        </div>
    </div>

    <?php
        use App\Models\Booking;
        use App\Models\Payment;
        use App\Models\Unit;
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;

        // Last 6 months including current
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->startOfMonth();
        });

        $monthLabels = $months->map(fn ($m) => $m->format('M Y'));

        $bookingRaw = Booking::select(
                DB::raw('DATE_FORMAT(booking_date, "%Y-%m-01") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('booking_date', '>=', $months->first()->toDateString())
            ->groupBy('month')
            ->pluck('total', 'month');

        $bookingSeries = $months->map(function ($m) use ($bookingRaw) {
            $key = $m->format('Y-m-01');
            return (int) ($bookingRaw[$key] ?? 0);
        });

        $paymentRaw = Payment::select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m-01") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_date', '>=', $months->first()->toDateString())
            ->groupBy('month')
            ->pluck('total', 'month');

        $paymentSeries = $months->map(function ($m) use ($paymentRaw) {
            $key = $m->format('Y-m-01');
            return (float) ($paymentRaw[$key] ?? 0);
        });

        $unitStatusRaw = Unit::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $unitStatusLabels = $unitStatusRaw->keys();
        $unitStatusSeries = $unitStatusRaw->values();
    ?>

    <div class="card">
        <h2>Portfolio insights</h2>
        <div class="card-subtitle">Visual overview of bookings, cash flow, and inventory mix.</div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;margin-top:10px;">
            <div>
                <div class="card-subtitle" style="margin-bottom:6px;">Bookings (last 6 months)</div>
                <canvas id="bookingsChart" height="140"></canvas>
            </div>
            <div>
                <div class="card-subtitle" style="margin-bottom:6px;">Payments received (last 6 months)</div>
                <canvas id="paymentsChart" height="140"></canvas>
            </div>
            <div>
                <div class="card-subtitle" style="margin-bottom:6px;">Units by status</div>
                <canvas id="unitsChart" height="180"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Quick actions</h2>
        <div class="card-subtitle">Create the most common records in a couple of clicks.</div>
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-primary">New Project</a>
            <a href="<?php echo e(route('units.create')); ?>" class="btn btn-primary">New Unit</a>
            <a href="<?php echo e(route('installment-plans.create')); ?>" class="btn btn-primary">New Installment Plan</a>
            <a href="<?php echo e(route('bookings.create')); ?>" class="btn btn-primary">New Booking</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const chartColors = {
                cyan: '#22d3ee',
                cyanSoft: 'rgba(34, 211, 238, 0.25)',
                blue: '#0ea5e9',
                green: '#22c55e',
                orange: '#f97316',
                red: '#ef4444',
                bg: '#020617',
                grid: 'rgba(148, 163, 184, 0.25)',
                text: '#e5e7eb',
                muted: '#9ca3af',
            };

            const monthLabels = <?php echo json_encode($monthLabels->values()); ?>;
            const bookingSeries = <?php echo json_encode($bookingSeries->values()); ?>;
            const paymentSeries = <?php echo json_encode($paymentSeries->values()); ?>;
            const unitStatusLabels = <?php echo json_encode($unitStatusLabels->values()); ?>;
            const unitStatusSeries = <?php echo json_encode($unitStatusSeries->values()); ?>;

            function makeLineChart(ctx, label, data, color, fillColor) {
                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label,
                            data,
                            borderColor: color,
                            backgroundColor: fillColor,
                            borderWidth: 2,
                            tension: 0.35,
                            pointRadius: 3,
                            pointHoverRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#020617',
                                borderColor: chartColors.cyanSoft,
                                borderWidth: 1
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: chartColors.muted, font: { size: 11 } }
                            },
                            y: {
                                grid: { color: chartColors.grid },
                                ticks: { color: chartColors.muted, font: { size: 11 }, precision: 0 }
                            }
                        }
                    }
                });
            }

            const bookingsEl = document.getElementById('bookingsChart');
            const paymentsEl = document.getElementById('paymentsChart');
            const unitsEl = document.getElementById('unitsChart');

            if (bookingsEl) {
                makeLineChart(
                    bookingsEl.getContext('2d'),
                    'Bookings',
                    bookingSeries,
                    chartColors.cyan,
                    chartColors.cyanSoft
                );
            }

            if (paymentsEl) {
                makeLineChart(
                    paymentsEl.getContext('2d'),
                    'Payments',
                    paymentSeries,
                    chartColors.green,
                    'rgba(34, 197, 94, 0.18)'
                );
            }

            if (unitsEl) {
                new Chart(unitsEl.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: unitStatusLabels,
                        datasets: [{
                            data: unitStatusSeries,
                            backgroundColor: [
                                chartColors.green,
                                chartColors.cyan,
                                chartColors.orange,
                                chartColors.red,
                                chartColors.blue
                            ],
                            borderColor: chartColors.bg,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: chartColors.muted, font: { size: 11 } }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/dashboard.blade.php ENDPATH**/ ?>