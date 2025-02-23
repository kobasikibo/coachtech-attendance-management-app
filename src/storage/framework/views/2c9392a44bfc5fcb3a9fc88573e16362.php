<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance-index.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>勤怠一覧</h1>

<div class="month-navigation">
    <div class="navigation-item">
        <img src="<?php echo e(asset('images/icons/selector-left.png')); ?>" alt="前月へ移動" class="selector-icon">
        <a class="navigation-link" href="<?php echo e(route('attendance.index', ['month' => $previousMonth])); ?>">前月</a>
    </div>

    <div class="navigation-item">
        <img src="<?php echo e(asset('images/icons/calendar-icon.png')); ?>" alt="カレンダー" class="calendar-icon">
        <span class="navigation-month"><?php echo e(\Carbon\Carbon::parse($currentMonth)->format('Y/m')); ?></span>
    </div>

    <div class="navigation-item <?php echo e($currentMonth->format('Y-m') ==  now()->format('Y-m') ? 'invisible' : ''); ?>">
        <a class="navigation-link" href="<?php echo e(route('attendance.index', ['month' => $nextMonth])); ?>">翌月</a>
        <img src="<?php echo e(asset('images/icons/selector-right.png')); ?>" alt="翌月へ移動" class="selector-icon">
    </div>
</div>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">日付</th>
            <th class="table-header">出勤</th>
            <th class="table-header">退勤</th>
            <th class="table-header">休憩</th>
            <th class="table-header">合計</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="table-cell"><?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('m/d(D)')); ?></td>

            <?php
            $attendanceForDate = $attendances->firstWhere('date', $date);
            ?>
            <td class="table-cell"><?php echo e($attendanceForDate ? $attendanceService->formatClockIn($attendanceForDate) : ''); ?></td>
            <td class="table-cell"><?php echo e($attendanceForDate ? $attendanceService->formatClockOut($attendanceForDate) : ''); ?></td>
            <td class="table-cell"><?php echo e($attendanceForDate ? $breakService->formatBreakTime($attendanceForDate) : ''); ?></td>
            <td class="table-cell"><?php echo e($attendanceForDate ? $attendanceService->formatWorkTime($attendanceForDate) : ''); ?></td>
            <td class="table-cell">
                <?php if($attendanceForDate): ?>
                <a class="table-link" href="<?php echo e(route('attendance.detail', $attendanceForDate->id)); ?>">詳細</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/index.blade.php ENDPATH**/ ?>