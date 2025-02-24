<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance-index.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(asset('css/stamp_correction_request.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>申請一覧</h1>

<div class="tab-container">
    <a href="<?php echo e(url('/stamp_correction_request/list?tab=pending')); ?>" class="tab-link <?php echo e($tab === 'pending' ? 'active' : ''); ?>">承認待ち</a>
    <a href="<?php echo e(url('/stamp_correction_request/list?tab=approved')); ?>" class="tab-link <?php echo e($tab === 'approved' ? 'active' : ''); ?>">承認済み</a>
</div>

<table class="attendance-table">
    <thead>
        <tr>
            <th class="table-header">状態</th>
            <th class="table-header">名前</th>
            <th class="table-header">対象日時</th>
            <th class="table-header">申請理由</th>
            <th class="table-header">申請日時</th>
            <th class="table-header">詳細</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="table-cell"><?php echo e($attendance->approval_status); ?></td>
            <td class="table-cell"><?php echo e($attendance->user->name); ?></td>
            <td class="table-cell"><?php echo e(\Carbon\Carbon::parse($attendance->date)->translatedFormat('Y/m/d')); ?></td>
            <td class="table-cell"><?php echo e($attendance->remarks); ?></td>
            <td class="table-cell"><?php echo e(\Carbon\Carbon::parse($attendance->updated_at)->translatedFormat('Y/m/d')); ?></td>
            <td class="table-cell">
                <a class="table-link" href="<?php echo e(route('attendance.detail', $attendance->id)); ?>">詳細</a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/tab-selector.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/stamp_correction_request/index.blade.php ENDPATH**/ ?>