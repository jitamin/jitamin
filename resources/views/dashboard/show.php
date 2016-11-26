<div class="filter-box">
    <form method="get" action="<?= $this->url->dir() ?>" class="search">
        <?= $this->form->hidden('controller', ['controller' => 'SearchController']) ?>
        <?= $this->form->hidden('action', ['action' => 'index']) ?>

        <div class="input-addon">
            <?= $this->form->text('q', [], [], ['placeholder="'.t('Search').'"'], 'input-addon-field') ?>
            <div class="input-addon-item">
                <?= $this->render('app/filters_helper') ?>
            </div>
        </div>
    </form>
</div>

<?= $this->render('dashboard/projects', ['paginator' => $project_paginator, 'user' => $user]) ?>

<?= $this->hook->render('template:dashboard:show', ['user' => $user]) ?>
