<?= $this->projectHeader->render($project) ?>
<section class="page-container">

    <div class="page-content">
        <?= $content_for_sublayout ?>
    </div>
    <?= $this->render($subside_template, ['project' => $project]) ?>
</section>
