<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/request-show.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>勤怠詳細</h1>

<form method="POST" action="<?php echo e(route('admin.stamp_correction_request.approve', $correctionRequest->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="attendance-detail">
        <div class="attendance-detail__name">
            <div class="attendance-detail__label-wrapper">
                <label class="attendance-detail__label">名前</label>
            </div>
            <div class="attendance-detail__content">
                <p class="name"><?php echo e($attendance->user->name ?? ''); ?></p>
            </div>
        </div>

        <div class="attendance-detail__date">
            <div class="attendance-detail__label-wrapper">
                <label class="attendance-detail__label">日付</label>
            </div>
            <div class="attendance-detail__content">
                <p class="year"><?php echo e($attendanceService->getYearFromDate($attendance)); ?></p>
                <p class="date"><?php echo e($attendanceService->getMonthDayFromDate($attendance)); ?></p>
            </div>
        </div>

        <div class="attendance-detail__clock">
            <div class="attendance-detail__row">
                <div class="attendance-detail__label-wrapper">
                    <label class="attendance-detail__label">出勤・退勤</label>
                </div>
                <div class="attendance-detail__content">
                    <p class="attendance-detail__left"><?php echo e($correctionRequest->formatClockIn()); ?></p>
                    〜
                    <p class="attendance-detail__right"><?php echo e($correctionRequest->formatClockOut()); ?></p>
                </div>
            </div>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $formattedBreaks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $break): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="attendance-detail__break">
            <div class="attendance-detail__row">
                <div class="attendance-detail__label-wrapper">
                    <label class="attendance-detail__label"><?php echo e($index === 0 ? '休憩' : '休憩' . ($index + 1)); ?></label>
                </div>
                <div class="attendance-detail__content">
                    <p class="attendance-detail__left"><?php echo e($break['break_start'] ?? '-'); ?></p>
                    〜
                    <p class="attendance-detail__right"><?php echo e($break['break_end'] ?? '-'); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="attendance-detail__break">
            <div class="attendance-detail__row">
                <div class="attendance-detail__label-wrapper">
                    <label class="attendance-detail__label">休憩</label>
                </div>
                <div class="attendance-detail__content">
                    <p class="attendance-detail__left">-</p>
                    〜
                    <p class="attendance-detail__right">-</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="attendance-detail__remarks">
            <div class="attendance-detail__row">
                <div class="attendance-detail__label-wrapper">
                    <label class="attendance-detail__label">備考</label>
                </div>
                <div class="attendance-detail__content">
                    <p class="attendance-detail__large"><?php echo e($correctionRequest->remarks); ?></p>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-submit <?php echo e($attendance->approval_status === '承認済み' ? 'approved' : ''); ?>"
        <?php echo e($attendance->approval_status === '承認済み' ? 'disabled' : ''); ?>>
        <?php echo e($attendance->approval_status === '承認済み' ? '承認済み' : '承認'); ?>

    </button>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/stamp_correction_request/show.blade.php ENDPATH**/ ?>