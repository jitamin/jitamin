<div class="page-header">
    <h2><?= t('Link labels') ?></h2>
</div>
<?php if (!empty($links)): ?>
<table class="table-striped table-scrolling">
    <tr>
        <th class="column-70"><?= t('Link labels') ?></th>
        <th><?= t('Actions') ?></th>
    </tr>
    <?php foreach ($links as $link): ?>
    <tr>
        <td>
            <strong><?= t($link['label']) ?></strong>

            <?php if (!empty($link['opposite_label'])): ?>
                | <?= t($link['opposite_label']) ?>
            <?php endif ?>
        </td>
        <td>
            <ul>
                <?= $this->url->link(t('Edit'), 'Admin/LinkController', 'edit', ['link_id' => $link['id']], false ,'popover') ?>
                <?= t('or') ?>
                <?= $this->url->link(t('Remove'), 'Admin/LinkController', 'confirm', ['link_id' => $link['id']], false, 'popover') ?>
            </ul>
        </td>
    </tr>
    <?php endforeach ?>
</table>
<?php else: ?>
    <?= t('There is no link.') ?>
<?php endif ?>
