

<main>
    <div class="landing-container">
        <?= $this->render('_landing-top'); ?>
        <?= $this->render('_landing-center'); ?>
    </div>
</main>

<?= $this->render('_login-modal', compact('loginForm')) ?>

<?php if ($authClient && $authClient === 'vk') : ?>
    <?= $this->render('_vk-modal', compact('authClientForm', 'cities')) ?>
<? endif; ?>

<div class="overlay"></div>

