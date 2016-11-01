<section id="main">
    <?= $this->projectHeader->render($project, 'ProjectViewController', 'show') ?>
    <?= $this->render('project_view/columns', array('project' => $project)) ?>
    <?= $this->render('project_view/description', array('project' => $project)) ?>
    <?= $this->render('project_view/attachments', array('project' => $project, 'images' => $images, 'files' => $files)) ?>
    <?= $this->render('project_view/information', array('project' => $project, 'users' => $users, 'roles' => $roles)) ?>
    <?= $this->render('project_view/activity', array('project' => $project, 'events' => $events)) ?>
</section>
