<li class="dropdown">
    <a href="#" class="dropdown-menu"><?= $this->avatar->currentUserSmall('avatar-inline') ?><?= $this->text->e($this->user->getFullname()) ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <li>
            <i class="fa fa-dashboard"></i>
            <?= $this->url->link(t('My dashboard'), 'DashboardController', 'show', array('user_id' => $this->user->getId())) ?>
        </li>
        <li>
            <i class="fa fa-vcard"></i>
            <?= $this->url->link(t('My profile'), 'UserViewController', 'show', array('user_id' => $this->user->getId())) ?>
        </li>
        <li>
            <i class="fa fa-cubes"></i>
            <?= $this->url->link(t('Projects management'), 'ProjectListController', 'show') ?>
        </li>
        <?php if ($this->user->hasAccess('UserListController', 'show')): ?>
            <li>
                <i class="fa fa-user"></i>
                <?= $this->url->link(t('Users management'), 'UserListController', 'show') ?>
            </li>
            <li>
                <i class="fa fa-group"></i>
                <?= $this->url->link(t('Groups management'), 'GroupListController', 'index') ?>
            </li>
            <li>
                <i class="fa fa-plug"></i>
                <?= $this->url->link(t('Plugins management'), 'PluginController', 'show') ?>
            </li>
            <li>
                <i class="fa fa-gear"></i>
                <?= $this->url->link(t('Application settings'), 'ConfigController', 'index') ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:header:dropdown') ?>

        <li>
            <i class="fa fa-life-ring"></i>
            <?= $this->url->link(t('Documentation'), 'DocumentationController', 'show') ?>
        </li>
        <?php if (! DISABLE_LOGOUT): ?>
            <li>
                <i class="fa fa-sign-out"></i>
                <?= $this->url->link(t('Logout'), 'AuthController', 'logout') ?>
            </li>
        <?php endif ?>
    </ul>
</li>
