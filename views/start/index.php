<main>
    <div class="landing-container">
        <?= $this->render('_landing-top'); ?>
        <?= $this->render('_landing-center'); ?>
    </div>
</main>

<?= $this->render('_login-modal', compact('loginForm')) ?>

<div class="overlay"></div>

