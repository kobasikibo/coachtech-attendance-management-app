<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance-detail.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>勤怠詳細</h1>

<form method="POST" action="<?php echo e(route('attendance.update', $attendance->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="form-group">
        <div class="form-name">
            <div class="form-label-container">
                <label class="form-label">名前</label>
            </div>
            <div class="form-input-container">
                <p class="name"><?php echo e($attendance->user->name ?? ''); ?></p>
            </div>
        </div>

        <div class="form-date">
            <div class="form-label-container">
                <label class="form-label">日付</label>
            </div>
            <div class="form-input-container">
                <p class="attendance-year"><?php echo e($attendanceService->getYearFromClockIn($attendance)); ?></p>
                <p class="attendance-date"><?php echo e($attendanceService->getMonthDayFromClockIn($attendance)); ?></p>
            </div>
        </div>

        <div class="form-clock">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">出勤・退勤</label>
                </div>
                <div class="form-input-container">
                    <input type="time" name="clock_in" value="<?php echo e($attendanceService->formatClockIn($attendance)); ?>"
                        class="form-control-left" <?php echo e($attendance->approval_status === 'pending' ? 'disabled' : ''); ?>>
                    〜
                    <input type="time" name="clock_out" value="<?php echo e($attendanceService->formatClockOut($attendance)); ?>" class="form-control-right" <?php echo e($attendance->approval_status === 'pending' ? 'disabled' : ''); ?>>
                </div>
            </div>

            <?php $__errorArgs = ['clock_in'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $formattedBreaks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $break): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩 <?php echo e($index + 1); ?></label>
                </div>
                <div class="form-input-container">
                    <input type="hidden" name="break_id[<?php echo e($index); ?>]" value="<?php echo e($break['id']); ?>">

                    <input type="time" name="breaks[<?php echo e($break['id']); ?>][break_start]" value="<?php echo e($break['break_start']); ?>" class="form-control-left" <?php echo e($attendance->approval_status === 'pending' ? 'disabled' : ''); ?>>
                    〜
                    <input type="time" name="breaks[<?php echo e($break['id']); ?>][break_end]" value="<?php echo e($break['break_end']); ?>" class="form-control-right" <?php echo e($attendance->approval_status === 'pending' ? 'disabled' : ''); ?>>
                </div>
            </div>
            <?php $__currentLoopData = $errors->get("breaks.$index.break_start"); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="error"><?php echo e($message); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <!-- 休憩情報が一つもない場合 -->
        <div class="form-break">
            <div class="form-row">
                <div class="form-label-container">
                    <label class="form-label">休憩</label>
                </div>
                <div class="form-input-container">
                    <input type="time" name="breaks[0][break_start]" class="form-control-left">
                    〜
                    <input type="time" name="breaks[0][break_end]" class="form-control-right">
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-remarks">
            <div class="form-row-large">
                <div class="form-label-container">
                    <label class="form-label">備考</label>
                </div>
                <div class="form-input-container">
                    <textarea name="remarks" class="form-control-large" <?php echo e($attendance->approval_status === 'pending' ? 'disabled' : ''); ?>><?php echo e($attendance->remarks); ?></textarea>
                </div>
            </div>

            <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <?php if($attendance->approval_status === 'pending'): ?>
    <div class="alert">
        *承認待ちのため修正はできません。
    </div>
    <?php endif; ?>

    <button type="submit" class="btn-submit <?php echo e($attendance->approval_status === 'pending' ? 'invisible' : ''); ?>">修正</button>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/attendance/detail.blade.php ENDPATH**/ ?>