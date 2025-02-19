<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance-index.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(asset('css/admin-attendance-index.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1><?php echo e(\Carbon\Carbon::parse($currentDay)->format('Y年n月j日')); ?>の勤怠</h1>

<div class="date-navigation">
    <div class="navigation-item">
        <img src="<?php echo e(asset('images/icons/selector-left.png')); ?>" alt="前日へ移動" class="selector-icon">
        <a class="navigation-link" href="<?php echo e(route('admin.attendance.index', ['clock_in' => \Carbon\Carbon::parse($currentDay)->subDay()->format('Y-m-d')])); ?>">前日</a>
    </div>

    <div class="navigation-item">
        <img src="<?php echo e(asset('images/icons/calendar-icon.png')); ?>" alt="カレンダー" class="calendar-icon">
        <span class="current-date"><?php echo e(\Carbon\Carbon::parse($currentDay)->format('Y/m/d')); ?></span>
    </div>

    <div class="navigation-item">
        <?php if($currentDay < now()->format('Y-m-d')): ?>
            <a class="navigation-link" href="<?php echo e(route('admin.attendance.index', ['clock_in' => \Carbon\Carbon::parse($currentDay)->addDay()->format('Y-m-d')])); ?>">翌日</a>
            <img src="<?php echo e(asset('images/icons/selector-right.png')); ?>" alt="翌日へ移動" class="selector-icon">
            <?php else: ?>
            <span class="placeholder">翌日</span>
            <?php endif; ?>
    </div>
</div>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">名前</th>
            <th class="table-header">出勤</th>
            <th class="table-header">退勤</th>
            <th class="table-header">休憩</th>
            <th class="table-header">合計</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="table-cell"><?php echo e($attendance->user->name); ?></td>
            <td class="table-cell"><?php echo e($attendance->getFormattedClockIn()); ?></td>
            <td class="table-cell"><?php echo e($attendance->getFormattedClockOut()); ?></td>
            <td class="table-cell"><?php echo e($attendance->getFormattedBreakTime()); ?></td>
            <td class="table-cell"><?php echo e($attendance->getWorkTime()); ?></td>
            <td class="table-cell">
                <a class="table-link" href="<?php echo e(route('admin.attendance.detail', $attendance->id)); ?>">詳細</a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/attendance-index.blade.php ENDPATH**/ ?>