<?php $__env->startSection('content'); ?>
    <h1>勤怠詳細</h1>
    <p><strong>日付:</strong> <?php echo e($attendance->created_at->format('Y-m-d')); ?></p>
    <p><strong>出勤時刻:</strong> <?php echo e($attendance->clock_in ?? 'ー'); ?></p>
    <p><strong>退勤時刻:</strong> <?php echo e($attendance->clock_out ?? 'ー'); ?></p>
    <p><strong>休憩開始:</strong> <?php echo e($attendance->break_start ?? 'ー'); ?></p>
    <p><strong>休憩終了:</strong> <?php echo e($attendance->break_end ?? 'ー'); ?></p>

    <a href="<?php echo e(route('attendance.index')); ?>">一覧に戻る</a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/detail.blade.php ENDPATH**/ ?>