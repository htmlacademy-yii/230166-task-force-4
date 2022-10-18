<main>
    <div class="landing-container">
        <?= $this->render('_landing-top'); ?>
        <?= $this->render('_landing-center'); ?>
    </div>
</main>
<div class="overlay"></div>

<?= $this->render('_login-modal', compact('loginForm')) ?>


