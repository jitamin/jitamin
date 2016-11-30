<div class="project-header">
    <?= $this->hook->render('template:project:header:before', ['project' => $project]) ?>
    <div class="dropdown-component">
        <?= $this->render('project_header/dropdown', ['project' => $project, 'board_view' => $board_view]) ?>
    </div>
    <div class="views-switcher-component">
        <?= $this->render('project_header/views', ['project' => $project, 'filters' => $filters]) ?>
    </div>
    <div class="filter-box-component">
        <?= $this->render('project_header/search', [
            'project'             => $project,
            'filters'             => $filters,
            'custom_filters_list' => isset($custom_filters_list) ? $custom_filters_list : [],
            'users_list'          => isset($users_list) ? $users_list : [],
            'categories_list'     => isset($categories_list) ? $categories_list : [],
        ]) ?>
    </div>

    <?= $this->hook->render('template:project:header:after', ['project' => $project]) ?>
</div>
