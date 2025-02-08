<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'coachtech 勤怠管理アプリ'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>">
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-container">
                <a class="header-logo" href="/attendance">
                    <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtechのロゴ">
                </a>
            </div>

            <?php if(!in_array(request()->route()->getName(), ['login', 'register'])): ?>
            <div class="header-container">
                <div class="header-links">
                    <a href="<?php echo e(route('attendance.show')); ?>" class="link-attendance">勤怠</a>
                    <a href="<?php echo e(route('attendance.show')); ?>" class="link-attendance-list">申請一覧</a>
                    <a href="<?php echo e(route('attendance.show')); ?>" class="link-request">申請</a>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="link-style-button">ログアウト</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <div class="container">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>