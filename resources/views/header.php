<?php $_title = $this->render('header/title', array(
    'project' => isset($project) ? $project : null,
    'task' => isset($task) ? $task : null,
    'description' => isset($description) ? $description : null,
    'title' => $title,
)) ?>

<?php $_top_right_corner = implode('', array(
        $this->render('header/admin_dropdown'),
        $this->render('header/user_notifications'),
        $this->render('header/creation_dropdown'),
        $this->render('header/user_dropdown')
    )) ?>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <?= $_title ?>
        </div>

        <div class="collapse navbar-collapse" id="nb-collapse">

            <ul class="nav navbar-nav">
                <li <?= $this->app->checkMenuSelection('DashboardController', 'show') ?>>
                    <a href="/dashboard"><?= t('My projects') ?></a>
                </li>
                <li <?= $this->app->checkMenuSelection('ProjectListController', 'show') ?>><?= $this->url->link(t('Project management'), 'ProjectListController', 'show') ?></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li <?= $this->app->checkMenuSelection('SearchController', 'index') ?>><?= $this->url->link('<i class="fa fa-search"></i> '.t('Search'), 'SearchController', 'index') ?></li>
                <?= $_top_right_corner ?>
            </ul>

            <?php if (! empty($board_selector)): ?>
            <?= $this->render('header/board_selector', array('board_selector' => $board_selector)) ?>
            <?php endif ?>

        </div>
    </div>
</div>
