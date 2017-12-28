<div class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nb-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <h3>
            <span class="sidebar-toggle"><i class="fa fa-navicon"></i></span>
            <a href="/"><?= t('Dashboard') ?></a> &raquo; 
            <?php if (isset($page_title)): ?>
                <?= $this->text->e($page_title) ?>
            <?php elseif (isset($title)): ?>
                <?= $this->text->e($title) ?>
            <?php else: ?>
                Jitamin
            <?php endif ?>
        </h3>
    </div>
    <div class="collapse navbar-collapse" id="nb-collapse">

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-menu"><?= $this->avatar->currentUserSmall('avatar-inline') ?><?= $this->text->e($this->user->getFullname()) ?> <i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <i class="fa fa-vcard"></i>
                        <?= $this->url->link(t('My profile'), 'Profile/ProfileController', 'show', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li>
                        <i class="fa fa-history"></i>
                        <?= $this->url->link(t('My history'), 'Profile/HistoryController', 'timesheet', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li>
                        <i class="fa fa-life-ring"></i>
                        <?= $this->url->link(t('Documentation'), 'DocumentationController', 'show') ?>
                    </li>
                    <?= $this->hook->render('template:header:dropdown') ?>
                    <div class="divider"></div>
                    <li>
                        <i class="fa fa-edit"></i>
                        <?= $this->url->link(t('Edit profile'), 'Profile/ProfileController', 'edit', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <?php if (!DISABLE_LOGOUT): ?>
                        <li>
                            <i class="fa fa-sign-out"></i>
                            <?= $this->url->link(t('Logout'), 'Auth/AuthController', 'logout') ?>
                        </li>
                    <?php endif ?>
                </ul>
            </li>
        </ul>
        <?= $this->navbarSearch->render(isset($project) ? $project : []) ?>
    </div>
</div>
