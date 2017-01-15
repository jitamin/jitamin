<div class="project-header">
    <?= $this->hook->render('template:project:header:before', ['project' => $project]) ?>
    <div class="views-switcher-component">
        <?= $this->render('project/_header/views', ['project' => $project, 'q' => $q]) ?>
    </div>
    <div class="filter-box-component">
        <?= $this->render('project/_header/actions', [
            'project'             => $project,
        ]) ?>
    </div>
    <div class="dropdown-component text-right">
        <?= $this->render('project/_header/dropdown', ['project' => $project, 'board_view' => $board_view]) ?>
    </div>
    <?= $this->hook->render('template:project:header:after', ['project' => $project]) ?>
</div>
