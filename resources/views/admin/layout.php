<?= $this->render('admin/_partials/subnav') ?>
<section class="page-container" id="config-section">
    <div class="page-content">
        <?= $content_for_sublayout ?>
    </div>
    <?php if ($subside_template): ?>
        <?= $this->render($subside_template) ?>
    <?php endif ?>
</section>

