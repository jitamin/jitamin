<li class="dropdown">
    <a href="#" class="dropdown-menu"><?= $this->avatar->currentUserSmall('avatar-inline') ?><?= $this->text->e($this->user->getFullname()) ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <li>
            <i class="fa fa-vcard"></i>
            <?= $this->url->link(t('My profile'), 'ProfileController', 'show', ['user_id' => $this->user->getId()]) ?>
        </li>
        <li>
            <i class="fa fa-edit"></i>
            <?= $this->url->link(t('Edit profile'), 'ProfileController', 'edit', ['user_id' => $this->user->getId()]) ?>
        </li>
        <li>
            <i class="fa fa-cubes"></i>
            <?= $this->url->link(t('My projects'), 'DashboardController', 'index', ['user_id' => $this->user->getId()]) ?>
        </li>
        <li>
            <i class="fa fa-life-ring"></i>
            <?= $this->url->link(t('Documentation'), 'DocumentationController', 'show') ?>
        </li>
        <?= $this->hook->render('template:header:dropdown') ?>

        <?php if (!DISABLE_LOGOUT): ?>
            <li>
                <i class="fa fa-sign-out"></i>
                <?= $this->url->link(t('Logout'), 'AuthController', 'logout') ?>
            </li>
        <?php endif ?>
    </ul>
</li>
