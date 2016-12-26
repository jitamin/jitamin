<div class="sidebar sidebar-icons">
    <?= $this->form->select(
            'user_id',
            $users,
            $filter,
            [],
            ['data-redirect-url="'.$this->url->href('ProjectUserOverviewController', $this->app->getRouterAction(), ['user_id' => 'USER_ID']).'"', 'data-redirect-regex="USER_ID"'],
            'chosen-select select-auto-redirect'
        ) ?>
    <ul>
        <li <?= $this->app->setActive('ProjectUserOverviewController', 'managers') ?>>
            <i class="fa fa-user-circle"></i><?= $this->url->link(t('Project managers'), 'ProjectUserOverviewController', 'managers', $filter) ?>
        </li>
        <li <?= $this->app->setActive('ProjectUserOverviewController', 'members') ?>>
            <i class="fa fa-user"></i><?= $this->url->link(t('Project members'), 'ProjectUserOverviewController', 'members', $filter) ?>
        </li>
        <li <?= $this->app->setActive('ProjectUserOverviewController', 'opens') ?>>
            <i class="fa fa-eye"></i><?= $this->url->link(t('Open tasks'), 'ProjectUserOverviewController', 'opens', $filter) ?>
        </li>
        <li <?= $this->app->setActive('ProjectUserOverviewController', 'closed') ?>>
            <i class="fa fa-eye-slash"></i><?= $this->url->link(t('Closed tasks'), 'ProjectUserOverviewController', 'closed', $filter) ?>
        </li>

        <?= $this->hook->render('template:project-user:sidebar') ?>
    </ul>
</div>
