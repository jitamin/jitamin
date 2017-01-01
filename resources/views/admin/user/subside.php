<div class="subside">
    <ul>
         <li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New local user'), 'Admin/UserController', 'create', [], false, 'popover') ?></li>
        <li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New remote user'), 'Admin/UserController', 'create', ['remote' => 1], false, 'popover') ?></li>
        <li><i class="fa fa-upload fa-fw"></i><?= $this->url->link(t('Import'), 'Admin/UserImportController', 'show', [], false, 'popover') ?></li>
    </ul>
</div>
