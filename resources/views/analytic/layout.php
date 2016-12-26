<section id="main">
    <?= $this->projectHeader->render($project, 'TaskController', 'index') ?>
    <section class="page-container">
        <?= $this->render($subside_template, ['project' => $project]) ?>

        <div class="page-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
