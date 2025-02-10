<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance-detail.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>勤怠詳細</h1>

<form method="POST" action="<?php echo e(route('attendance.update', $attendance->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="form-group">
        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">名前</label>
            </div>
            <div class="form-row-right">
                <p class="name"><?php echo e($attendance->user->name ?? 'ー'); ?></p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">日付</label>
            </div>
            <div class="form-row-right">
                <input type="text" name="year" value="<?php echo e($attendance->created_at->format('Y')); ?>" readonly class="form-control">
                <input type="text" name="month_day" value="<?php echo e($attendance->created_at->format('m-d')); ?>" readonly class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">出勤・退勤</label>
            </div>
            <div class="form-row-right">
                <input type="text" name="clock_out" value="<?php echo e($attendance->getFormattedClockOut()); ?>" class="form-control">
                <input type="text" name="clock_in" value="<?php echo e($attendance->getFormattedClockIn()); ?>" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-row-left">
                <label class="form-label">休憩</label>
            </div>
            <div class="form-row-right">
                <input type="text" name="break_start" value="<?php echo e($attendance->getFormattedBreakStart()); ?>" class="form-control">
                <input type="text" name="break_end" value="<?php echo e($attendance->getFormattedBreakEnd()); ?>" class="form-control">
            </div>
        </div>

        <div class="form-row-large">
            <div class="form-row-left">
                <label class="form-label">備考</label>
            </div>
            <div class="form-row-right">
                <textarea name="remarks" rows="3" class="form-control-large"><?php echo e($attendance->remarks); ?></textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-submit">修正</button>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/detail.blade.php ENDPATH**/ ?>