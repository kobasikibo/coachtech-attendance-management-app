<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="status"><?php echo e($attendance->status ?? '勤務外'); ?></div>

<div class="date"><?php echo e(now()->translatedFormat('Y年n月j日(D)')); ?></div>

<div class="current-time"></div>

<div class="btn-container">
    <?php if(!$attendance || $attendance->status === '勤務外'): ?>
    <form action="<?php echo e(route('attendance.clockIn')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-submit">出勤</button>
    </form>
    <?php elseif($attendance->status === '出勤中'): ?>
    <form action="<?php echo e(route('attendance.clockOut')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-submit">退勤</button>
    </form>
    <form action="<?php echo e(route('attendance.startBreak')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-submit-brake">休憩入</button>
    </form>
    <?php elseif($attendance->status === '休憩中'): ?>
    <form action="<?php echo e(route('attendance.endBreak')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-submit-brake">休憩戻</button>
    </form>
    <?php endif; ?>
</div>

<?php if(session('success')): ?>
<div class="success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="error"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<script src="<?php echo e(asset('js/attendance-show.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/show.blade.php ENDPATH**/ ?>