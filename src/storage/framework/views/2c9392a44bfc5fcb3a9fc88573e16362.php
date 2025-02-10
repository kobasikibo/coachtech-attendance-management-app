<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance-index.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>勤怠一覧</h1>

<div class="month-navigation">
    <a class="navigation-link" href=" <?php echo e(route('attendance.index', ['month' => \Carbon\Carbon::parse($currentMonth)->subMonth()->format('Y-m')])); ?>">前月</a>
    <strong><?php echo e(\Carbon\Carbon::parse($currentMonth)->format('Y/m')); ?></strong>
    <a class="navigation-link" href="<?php echo e(route('attendance.index', ['month' => \Carbon\Carbon::parse($currentMonth)->addMonth()->format('Y-m')])); ?>">翌月</a>
</div>

<table>
    <thead>
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($attendance->getFormattedDate()); ?></td>
            <td><?php echo e($attendance->getFormattedClockIn()); ?></td>
            <td><?php echo e($attendance->getFormattedClockOut()); ?></td>
            <td><?php echo e($attendance->getFormattedBreakTime()); ?></td>
            <td><?php echo e($attendance->getWorkTime()); ?></td>
            <td><a href="<?php echo e(route('attendance.detail', $attendance->id)); ?>">詳細</a></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/index.blade.php ENDPATH**/ ?>