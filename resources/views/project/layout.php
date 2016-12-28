<section id="main">
    <?= $this->projectHeader->render($project, 'TaskController', 'index') ?>
    <section class="page-container">

        <div class="page-content">
            <?= $content_for_sublayout ?>
        </div>
        <?= $this->render($subside_template, ['project' => $project]) ?>
    </section>
</section>
