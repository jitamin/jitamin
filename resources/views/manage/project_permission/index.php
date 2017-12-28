<div class="page-header">
    <h2><?= t('Allowed Users') ?></h2>
</div>

<?php if ($project['is_everybody_allowed']): ?>
    <div class="alert"><?= t('Everybody have access to this project.') ?></div>
<?php else: ?>

    <?php if (empty($users)): ?>
        <div class="alert"><?= t('No user have been allowed specifically.') ?></div>
    <?php else: ?>
        <table class="table-scrolling">
            <tr>
                <th class="column-50"><?= t('User') ?></th>
                <th><?= t('Role') ?></th>
                <?php if ($project['is_private'] == 0): ?>
                    <th class="column-15"><?= t('Actions') ?></th>
                <?php endif ?>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->text->e($user['name'] ?: $user['username']) ?></td>
                <td>
                    <?= $this->form->select(
                        'role-'.$user['id'],
                        $roles,
                        ['role-'.$user['id'] => $user['role']],
                        [],
                        ['data-url="'.$this->url->href('Manage/ProjectPermissionController', 'changeUserRole', ['project_id' => $project['id']]).'"', 'data-id="'.$user['id'].'"'],
                        'project-change-role'
                    ) ?>
                </td>
                <td>
                    <?= $this->url->link(t('Remove'), 'Manage/ProjectPermissionController', 'removeUser', ['project_id' => $project['id'], 'user_id' => $user['id']], false, 'popover') ?>
                </td>
            </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <?php if ($project['is_private'] == 0): ?>
    <div class="listing">
        <form method="post" action="<?= $this->url->href('Manage/ProjectPermissionController', 'addUser', ['project_id' => $project['id']]) ?>" autocomplete="off" class="form-inline">
            <?= $this->form->csrf() ?>
            <?= $this->form->hidden('project_id', ['project_id' => $project['id']]) ?>
            <?= $this->form->hidden('user_id', $values) ?>

            <?= $this->form->label(t('Name'), 'name') ?>
            <?= $this->form->text('name', $values, $errors, [
                'required',
                'placeholder="'.t('Enter user name...').'"',
                'title="'.t('Enter user name...').'"',
                'data-dst-field="user_id"',
                'data-search-url="'.$this->url->href('Profile/UserAjaxController', 'autocomplete').'"',
            ],
            'autocomplete') ?>

            <?= $this->form->select('role', $roles, $values, $errors) ?>

            <button type="submit" class="btn btn-success"><?= t('Add') ?></button>
        </form>
    </div>
    <?php endif ?>

    <div class="page-header">
        <h2><?= t('Allowed Groups') ?></h2>
    </div>

    <?php if (empty($groups)): ?>
        <div class="alert"><?= t('No group have been allowed specifically.') ?></div>
    <?php else: ?>
        <table class="table-scrolling">
            <tr>
                <th class="column-50"><?= t('Group') ?></th>
                <th><?= t('Role') ?></th>
                <?php if ($project['is_private'] == 0): ?>
                    <th class="column-15"><?= t('Actions') ?></th>
                <?php endif ?>
            </tr>
            <?php foreach ($groups as $group): ?>
            <tr>
                <td><?= $this->text->e($group['name']) ?></td>
                <td>
                    <?= $this->form->select(
                        'role-'.$group['id'],
                        $roles,
                        ['role-'.$group['id'] => $group['role']],
                        [],
                        ['data-url="'.$this->url->href('Manage/ProjectPermissionController', 'changeGroupRole', ['project_id' => $project['id']]).'"', 'data-id="'.$group['id'].'"'],
                        'project-change-role'
                    ) ?>
                </td>
                <td>
                    <?= $this->url->link(t('Remove'), 'Manage/ProjectPermissionController', 'removeGroup', ['project_id' => $project['id'], 'group_id' => $group['id']], true) ?>
                </td>
            </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <?php if ($project['is_private'] == 0): ?>
    <div class="listing">
        <form method="post" action="<?= $this->url->href('Manage/ProjectPermissionController', 'addGroup', ['project_id' => $project['id']]) ?>" autocomplete="off" class="form-inline">
            <?= $this->form->csrf() ?>
            <?= $this->form->hidden('project_id', ['project_id' => $project['id']]) ?>
            <?= $this->form->hidden('group_id', $values) ?>
            <?= $this->form->hidden('external_id', $values) ?>

            <?= $this->form->label(t('Group Name'), 'name') ?>
            <?= $this->form->text('name', $values, $errors, [
                'required',
                'placeholder="'.t('Enter group name...').'"',
                'title="'.t('Enter group name...').'"',
                'data-dst-field="group_id"',
                'data-dst-extra-field="external_id"',
                'data-search-url="'.$this->url->href('Admin/GroupController', 'autocompleteAjax').'"',
            ],
            'autocomplete') ?>

            <?= $this->form->select('role', $roles, $values, $errors) ?>

            <button type="submit" class="btn btn-success"><?= t('Add') ?></button>
        </form>
    </div>
    <?php endif ?>

<?php endif ?>

<?php if ($project['is_private'] == 0): ?>
<hr/>
<form method="post" action="<?= $this->url->href('Manage/ProjectPermissionController', 'allowEverybody', ['project_id' => $project['id']]) ?>">
    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('id', ['id' => $project['id']]) ?>
    <?= $this->form->checkbox('is_everybody_allowed', t('Allow everybody to access to this project'), 1, $project['is_everybody_allowed']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-success"><?= t('Save') ?></button>
    </div>
</form>
<?php endif ?>
