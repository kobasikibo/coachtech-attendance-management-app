<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin-staff-list.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h1>スタッフ一覧</h1>

<table class="staff-table">
    <thead>
        <tr>
            <th class="table-header">名前</th>
            <th class="table-header">メールアドレス</th>
            <th class="table-header">月次勤怠</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="table-cell"><?php echo e($user->name); ?></td>
            <td class="table-cell"><?php echo e($user->email); ?></td>
            <td class="table-cell">
                <a href="<?php echo e(route('admin.attendance.staff', ['id' => $user->id, 'month' => now()->format('Y-m')])); ?>" class="link-attendance-monthly">詳細</a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/staff/index.blade.php ENDPATH**/ ?>