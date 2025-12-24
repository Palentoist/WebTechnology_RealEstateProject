<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Real Estate System'); ?></title>
    <style>
        :root {
            --bg: #050816;
            --bg-soft: #0b1020;
            --bg-card: #0f172a;
            --accent: #00d4ff;
            --accent-soft: rgba(0, 212, 255, 0.12);
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --danger: #f87171;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            background: radial-gradient(circle at top left, #1e293b 0, var(--bg) 45%, #020617 100%);
            color: var(--text-main);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        a {
            color: var(--accent);
            text-decoration: none;
            transition: color 0.2s ease, opacity 0.2s ease;
        }

        a:hover {
            opacity: 0.85;
        }

        /* Shell layout */
        .shell {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            width: 100%;
        }

        .shell-sidebar {
            background: linear-gradient(to bottom, rgba(15,23,42,0.98), rgba(15,23,42,0.98));
            border-right: 1px solid rgba(148, 163, 184, 0.18);
            padding: 20px 18px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: radial-gradient(circle at 20% 0%, #22d3ee, #1d4ed8);
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.9);
        }

        .brand-text-main {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .brand-text-sub {
            font-size: 11px;
            color: var(--text-muted);
        }

        .nav-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item > a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .nav-item > a:hover {
            background: rgba(15, 23, 42, 0.9);
            color: var(--text-main);
        }

        .nav-item-icon {
            width: 20px;
            text-align: center;
            font-size: 13px;
            color: var(--accent);
        }

        .nav-item--primary > a {
            background: linear-gradient(90deg, rgba(56, 189, 248, 0.14), transparent);
            color: var(--text-main);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid rgba(31, 41, 55, 0.9);
            font-size: 12px;
            color: var(--text-muted);
        }

        .sidebar-user {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .sidebar-logout-button {
            margin-top: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: rgba(15, 23, 42, 0.8);
            color: var(--text-main);
            font-size: 11px;
            cursor: pointer;
        }

        .sidebar-logout-button:hover {
            border-color: rgba(248, 113, 113, 0.9);
            color: #fecaca;
        }

        /* Main area */
        .shell-main {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .shell-main-header {
            padding: 16px 30px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(24px);
            background: linear-gradient(to right, rgba(15,23,42,0.82), rgba(15,23,42,0.7));
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .shell-main-header-title {
            font-size: 18px;
            font-weight: 500;
        }

        .shell-main-header-subtitle {
            font-size: 12px;
            color: var(--text-muted);
        }

        .shell-main-header-right {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .pill {
            padding: 4px 9px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .notif-button {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            border: 1px solid rgba(148,163,184,0.5);
            background: radial-gradient(circle at 30% 0%, rgba(56,189,248,0.9), rgba(15,23,42,0.95));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e5faff;
            cursor: pointer;
            box-shadow: 0 0 18px rgba(56,189,248,0.8);
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 10px rgba(34,197,94,0.9);
        }

        .notif-panel {
            position: fixed;
            top: 0;
            right: -320px;
            width: 300px;
            height: 100vh;
            background: radial-gradient(circle at top, rgba(56,189,248,0.25), transparent 60%), #020617;
            border-left: 1px solid rgba(148,163,184,0.25);
            box-shadow: -24px 0 60px rgba(15,23,42,0.95);
            padding: 16px 14px 14px;
            transition: right 0.25s ease-in-out;
            z-index: 40;
            display: flex;
            flex-direction: column;
        }

        .notif-panel--open {
            right: 0;
        }

        .notif-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .notif-panel-title {
            font-size: 14px;
            font-weight: 500;
        }

        .notif-panel-close {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
        }

        .notif-list {
            margin: 0;
            padding: 0;
            list-style: none;
            overflow-y: auto;
            flex: 1;
        }

        .notif-item {
            padding: 8px 6px 8px;
            border-bottom: 1px dashed rgba(31,41,55,0.8);
        }

        .notif-item-title {
            font-size: 13px;
        }

        .notif-item-meta {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .shell-main-body {
            padding: 22px 30px 28px;
        }

        /* Generic components used by pages */
        .card {
            background: radial-gradient(circle at top left, rgba(56,189,248,0.18), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(30,64,175,0.4), transparent 60%),
                        var(--bg-card);
            border-radius: 16px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            padding: 18px 18px 16px;
            margin-bottom: 18px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.9);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 4px;
            font-size: 18px;
        }

        .card-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 14px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
        }

        .metric-card {
            padding: 12px 12px 10px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: radial-gradient(circle at top, rgba(56,189,248,0.14), transparent 60%);
        }

        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: var(--text-muted);
        }

        .metric-value {
            margin-top: 8px;
            font-size: 22px;
            font-weight: 500;
        }

        .metric-trend {
            margin-top: 4px;
            font-size: 11px;
            color: #6ee7b7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 13px;
        }

        th, td {
            padding: 7px 9px;
            border-bottom: 1px solid rgba(31, 41, 55, 0.9);
        }

        th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--text-muted);
            background: rgba(15,23,42,0.9);
        }

        tr:hover td {
            background: rgba(15, 23, 42, 0.8);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.5);
            background: rgba(15, 23, 42, 0.95);
            color: var(--text-main);
            font-size: 12px;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, transform 0.08s ease;
        }

        .btn-primary {
            border-color: rgba(56, 189, 248, 0.9);
            background: linear-gradient(90deg, #22d3ee, #0ea5e9);
            color: #020617;
            box-shadow: 0 12px 30px rgba(8, 47, 73, 0.8);
        }

        .btn + .btn {
            margin-left: 6px;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-row {
            margin-bottom: 12px;
        }

        .form-row label {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .form-row input,
        .form-row select,
        .form-row textarea {
            width: 100%;
            padding: 8px 9px;
            border-radius: 8px;
            border: 1px solid rgba(30, 64, 175, 0.7);
            background: rgba(15, 23, 42, 0.95);
            color: var(--text-main);
            font-size: 13px;
            outline: none;
        }

        .form-row input:focus,
        .form-row select:focus,
        .form-row textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.4);
        }

        .error {
            color: var(--danger);
            font-size: 11px;
            margin-top: 3px;
        }

        .status {
            margin-bottom: 12px;
            font-size: 13px;
            padding: 7px 9px;
            border-radius: 8px;
            background: var(--accent-soft);
            border: 1px solid rgba(56, 189, 248, 0.6);
        }

        @media (max-width: 900px) {
            .shell {
                grid-template-columns: 72px minmax(0, 1fr);
            }

            .shell-sidebar {
                padding-inline: 10px;
                align-items: center;
            }

            .brand-text-main,
            .brand-text-sub,
            .nav-section-title,
            .sidebar-footer {
                display: none;
            }

            .nav-item > a {
                justify-content: center;
            }

            .shell-main-header {
                padding-inline: 16px;
            }

            .shell-main-body {
                padding-inline: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <aside class="shell-sidebar">
            <div class="brand">
                <div class="brand-logo"></div>
                <div>
                    <div class="brand-text-main">Real Estate</div>
                    <div class="brand-text-sub">Admin Console</div>
                </div>
            </div>

            <div>
                <div class="nav-section-title">Overview</div>
                <ul class="nav-list">
                    <li class="nav-item nav-item--primary">
                        <a href="<?php echo e(route('dashboard')); ?>">
                            <span class="nav-item-icon">🏠</span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <div class="nav-section-title">Management</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="<?php echo e(route('projects.index')); ?>">
                            <span class="nav-item-icon">📁</span>
                            <span>Projects</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('unit-categories.index')); ?>">
                            <span class="nav-item-icon">🏷️</span>
                            <span>Unit Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('units.index')); ?>">
                            <span class="nav-item-icon">🏢</span>
                            <span>Units</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('installment-plans.index')); ?>">
                            <span class="nav-item-icon">📊</span>
                            <span>Installment Plans</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('bookings.index')); ?>">
                            <span class="nav-item-icon">📝</span>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('payments.index')); ?>">
                            <span class="nav-item-icon">💳</span>
                            <span>Payments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('reminders.index')); ?>">
                            <span class="nav-item-icon">⏰</span>
                            <span>Reminders</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <?php if(auth()->guard()->check()): ?>
                    <div class="sidebar-user">Signed in as<br><strong><?php echo e(Auth::user()->name); ?></strong></div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="sidebar-logout-button">
                            <span>⇦</span> <span>Logout</span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </aside>

        <div class="shell-main">
            <header class="shell-main-header">
                <div>
                    <div class="shell-main-header-title"><?php echo e($title ?? 'Dashboard'); ?></div>
                    <div class="shell-main-header-subtitle">Control panel for projects, bookings, payments and schedules.</div>
                </div>
                <div class="shell-main-header-right">
                    <div style="position: relative;">
                        <button type="button" id="notifToggle" class="notif-button" aria-label="Notifications">
                            🔔
                        </button>
                        <?php if(\App\Models\ActivityLog::count() > 0): ?>
                            <span class="notif-dot"></span>
                        <?php endif; ?>
                    </div>
                    <div class="pill">
                        Environment: <?php echo e(app()->environment()); ?>

                    </div>
                </div>
            </header>

            <main class="shell-main-body">
                <?php if(session('status')): ?>
                    <div class="status"><?php echo e(session('status')); ?></div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <?php if(auth()->guard()->check()): ?>
        <?php
            $activityLogs = \App\Models\ActivityLog::with('user')->latest()->limit(20)->get();
        ?>
        <aside id="notifPanel" class="notif-panel">
            <div class="notif-panel-header">
                <div>
                    <div class="notif-panel-title">Activity</div>
                    <div style="font-size:11px; color:var(--text-muted);">Latest log entries</div>
                </div>
                <button class="notif-panel-close" type="button" aria-label="Close">×</button>
            </div>
            <ul class="notif-list">
                <?php $__empty_1 = true; $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="notif-item">
                        <div class="notif-item-title"><?php echo e($log->title); ?></div>
                        <div class="notif-item-meta">
                            <?php if($log->user): ?>
                                <?php echo e($log->user->name); ?> ·
                            <?php endif; ?>
                            <?php echo e($log->created_at->diffForHumans()); ?>

                        </div>
                        <?php if($log->details): ?>
                            <div class="notif-item-meta"><?php echo e($log->details); ?></div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="notif-item">
                        <div class="notif-item-meta">No activity yet.</div>
                    </li>
                <?php endif; ?>
            </ul>
        </aside>

        <script>
            (function () {
                const toggle = document.getElementById('notifToggle');
                const panel = document.getElementById('notifPanel');
                const closeBtn = panel ? panel.querySelector('.notif-panel-close') : null;

                function openPanel() {
                    if (panel) {
                        panel.classList.add('notif-panel--open');
                    }
                }

                function closePanel() {
                    if (panel) {
                        panel.classList.remove('notif-panel--open');
                    }
                }

                if (toggle && panel) {
                    toggle.addEventListener('click', function () {
                        if (panel.classList.contains('notif-panel--open')) {
                            closePanel();
                        } else {
                            openPanel();
                        }
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', closePanel);
                }
            })();
        </script>
    <?php endif; ?>
</body>
</html>


<?php /**PATH D:\Downloads\real-estate-system\real-estate-system\resources\views/layouts/app.blade.php ENDPATH**/ ?>