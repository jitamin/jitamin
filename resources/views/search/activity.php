<div class="page-header">
    <?= $this->render('search/_partials/nav') ?>
</div>

<div class="filter-box">
    <form method="get" action="<?= $this->url->dir() ?>" class="search">
        <?= $this->form->hidden('controller', $values) ?>
        <?= $this->form->hidden('action', $values) ?>

        <div class="input-addon">
            <?= $this->form->text('q', $values, [], [empty($values['q']) ? 'autofocus' : '', 'placeholder="'.t('Search').'"'], 'input-addon-field') ?>
            <div class="input-addon-item">
                <?= $this->render('_partials/filters_helper') ?>
            </div>
        </div>
    </form>
</div>

<?php if (empty($values['q'])): ?>
    <div class="listing">
        <h3><?= t('Advanced search') ?></h3>
        <p><?= t('Example of query: ') ?><strong>project:"My project" creator:me</strong></p>
        <ul>
            <li><?= t('Search by project: ') ?><strong>project:"My project"</strong></li>
            <li><?= t('Search by creator: ') ?><strong>creator:admin</strong></li>
            <li><?= t('Search by creation date: ') ?><strong>created:today</strong></li>
            <li><?= t('Search by task status: ') ?><strong>status:open</strong></li>
            <li><?= t('Search by task title: ') ?><strong>title:"My task"</strong></li>
        </ul>
        <p><i class="fa fa-external-link fa-fw"></i><?= $this->url->doc(t('View advanced search syntax'), 'search') ?></p>
    </div>
<?php elseif (!empty($values['q']) && $nb_events === 0): ?>
    <p class="alert"><?= t('Nothing found.') ?></p>
<?php else: ?>
    <?= $this->render('event/events', ['events' => $events]) ?>
<?php endif ?>
