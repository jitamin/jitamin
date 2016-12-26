<section id="main">
    <div class="page-header">
        <?= $this->render('project_header/nav') ?>
    </div>
    <section class="page-container">

        <?= $this->render($subside_template, ['users' => $users, 'filter' => $filter]) ?>

        <div class="page-content">
            <div class="page-header">
                <h2><?= $this->text->e($title) ?></h2>
            </div>
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
