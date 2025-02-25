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
                <a class="header-logo" href="<?php echo e(Route::currentRouteName() === 'login' ? route('admin.login') : (Route::currentRouteName() === 'admin.login' ? route('login') : route('attendance.show'))); ?>">
                    <img src="<?php echo e(asset('images/logo.svg')); ?>" alt="coachtechのロゴ">
                </a>
            </div>

            <?php
            $routeName = request()->route()->getName();
            ?>

            <?php if(str_starts_with($routeName, 'admin.') && !in_array($routeName, ['login', 'register', 'admin.login'])): ?>
            <div class="header-container">
                <div class="header-links">
                    <a href="<?php echo e(route('admin.attendance.index')); ?>" class="link-attendance-list">勤怠一覧</a>
                    <a href="<?php echo e(route('attendance.show')); ?>" class="link-attendance">スタッフ一覧</a>
                    <a href="<?php echo e(route('admin.stamp_correction_request.index')); ?>" class="link-request">申請一覧</a>
                    <form action="<?php echo e(route('admin.logout')); ?>" method="POST" class="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="link-style-button">ログアウト</button>
                    </form>
                </div>
            </div>
            <?php elseif(!in_array($routeName, ['login', 'register', 'admin.login'])): ?>
            <div class="header-container">
                <div class="header-links">
                    <a href="<?php echo e(route('attendance.show')); ?>" class="link-attendance">勤怠</a>
                    <a href="<?php echo e(route('attendance.index')); ?>" class="link-attendance-list">勤怠一覧</a>
                    <a href="<?php echo e(route('user.stamp_correction_request.index')); ?>" class="link-request">申請</a>
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