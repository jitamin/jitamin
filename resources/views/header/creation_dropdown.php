<?php $has_project_creation_access = $this->user->hasAccess('ProjectController', 'create'); ?>
<?php $is_private_project_enabled = $this->app->config('disable_private_project', 0) == 0; ?>

<?php if ($has_project_creation_access || (!$has_project_creation_access && $is_private_project_enabled)): ?>
    <li class="dropdown">
        <a href="#" class="dropdown-menu"><i class="fa fa-plus"></i> <i class="fa fa-caret-down"></i></a>
        <ul>
            <?php if ($has_project_creation_access): ?>
                <li><i class="fa fa-cube"></i>
                    <?= $this->url->link(t('New project'), 'ProjectController', 'create', [], false, 'popover') ?>
                </li>
            <?php endif ?>
            <?php if ($is_private_project_enabled): ?>
                <li>
                    <i class="fa fa-lock"></i>
                    <?= $this->url->link(t('New private project'), 'ProjectController', 'createPrivate', [], false, 'popover') ?>
                </li>
            <?php endif ?>
            <?= $this->hook->render('template:header:creation-dropdown') ?>
        </ul>
    </li>
<?php endif ?>
