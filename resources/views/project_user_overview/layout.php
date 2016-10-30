<section id="main">
    <div class="page-header">
        <?= $this->render('project_header/nav') ?>
    </div>
    <section class="sidebar-container">

        <?= $this->render($sidebar_template, array('users' => $users, 'filter' => $filter)) ?>

        <div class="sidebar-content">
            <div class="page-header">
                <h2><?= $this->text->e($title) ?></h2>
            </div>
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
