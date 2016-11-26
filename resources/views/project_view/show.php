<section id="main">
    <?= $this->projectHeader->render($project, 'ProjectViewController', 'show') ?>
    <?= $this->render('project_view/columns', ['project' => $project]) ?>
    <?= $this->render('project_view/description', ['project' => $project]) ?>
    <?= $this->render('project_view/information', ['project' => $project, 'users' => $users, 'roles' => $roles]) ?>
    <?= $this->render('project_view/attachments', ['project' => $project, 'images' => $images, 'files' => $files]) ?>
    <?= $this->render('project_view/activity', ['project' => $project, 'events' => $events]) ?>
</section>
