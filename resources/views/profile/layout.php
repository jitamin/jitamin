<section class="page-container" id="user-section">
    <div class="page-content">
         <?= $this->render('profile/_partials/subnav', ['user' => $user]) ?>
        <?= $content_for_sublayout ?>
    </div>
    <?php if ($subside_template): ?>
        <?= $this->render($subside_template, ['user' => $user]) ?>
    <?php endif ?>
</section>