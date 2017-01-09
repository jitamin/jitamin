<div class="subside subside-icons">
    <?= $this->form->select(
            'user_id',
            $users,
            $filter,
            [],
            ['data-redirect-url="'.$this->url->href('Manage/ProjectUserOverviewController', $this->app->getRouterAction(), ['user_id' => 'USER_ID']).'"', 'data-redirect-regex="USER_ID"'],
            'chosen-select select-auto-redirect'
        ) ?>
    <ul>
        <li <?= $this->app->setActive('Manage/ProjectUserOverviewController', 'managers') ?>>
            <i class="fa fa-user-circle"></i><?= $this->url->link(t('Project managers'), 'Manage/ProjectUserOverviewController', 'managers', $filter) ?>
        </li>
        <li <?= $this->app->setActive('Manage/ProjectUserOverviewController', 'members') ?>>
            <i class="fa fa-user"></i><?= $this->url->link(t('Project members'), 'Manage/ProjectUserOverviewController', 'members', $filter) ?>
        </li>
        <li <?= $this->app->setActive('Manage/ProjectUserOverviewController', 'opens') ?>>
            <i class="fa fa-eye"></i><?= $this->url->link(t('Open tasks'), 'Manage/ProjectUserOverviewController', 'opens', $filter) ?>
        </li>
        <li <?= $this->app->setActive('Manage/ProjectUserOverviewController', 'closed') ?>>
            <i class="fa fa-eye-slash"></i><?= $this->url->link(t('Closed tasks'), 'Manage/ProjectUserOverviewController', 'closed', $filter) ?>
        </li>

        <?= $this->hook->render('template:project-user:subside') ?>
    </ul>
</div>
