<section id="task-summary">
    <h2><?= $this->text->e($task['title']) ?></h2>

    <?= $this->hook->render('template:task:details:top', ['task' => $task]) ?>

    <div class="task-summary-container color-<?= $task['color_id'] ?>">
        <div class="task-summary-columns">
            <div class="task-summary-column">
                <ul class="no-bullet">
                    <li>
                        <?= t('Status:') ?>
                        <span>
                        <?php if ($task['is_active'] == 1): ?>
                            <?= t('open') ?>
                        <?php else: ?>
                            <?= t('closed') ?>
                        <?php endif ?>
                        </span>
                    </li>
                    <li>
                        <?= t('Progress:') ?></strong> <span><?= $this->text->e($task['progress']) ?>%
                    </li>
                    <li>
                        <?= t('Priority:') ?></strong> <span><?= t('P'.$task['priority']) ?>
                    </li>
                    <?php if (!empty($task['reference'])): ?>
                        <li>
                            <?= t('Reference:') ?> <span><?= $this->text->e($task['reference']) ?></span>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($task['score'])): ?>
                        <li>
                            <?= t('Complexity:') ?> <span><?= $this->text->e($task['score']) ?></span>
                        </li>
                    <?php endif ?>
                    <?php if ($project['is_public']): ?>
                    <li>
                        <small>
                            <i class="fa fa-external-link"></i>
                            <?= $this->url->link(t('Public link'), 'Task/TaskController', 'readonly', ['task_id' => $task['id'], 'token' => $project['token']], false, '', '', true) ?>
                        </small>
                    </li>
                    <?php endif ?>
                    <?php if ($project['is_public'] && !$editable): ?>
                    <li>
                        <small>
                            <i class="fa fa-columns"></i>
                            <?= $this->url->link(t('Back to the board'), 'Project/Board/BoardController', 'readonly', ['token' => $project['token']]) ?>
                        </small>
                    </li>
                    <?php endif ?>

                    <?= $this->hook->render('template:task:details:first-column', ['task' => $task]) ?>
                </ul>
            </div>
            <div class="task-summary-column">
                <ul class="no-bullet">
                    <?php if (!empty($task['category_name'])): ?>
                        <li>
                            <?= t('Category:') ?>
                            <span><?= $this->text->e($task['category_name']) ?></span>
                        </li>
                    <?php endif ?>
                    <?php if (!empty($task['swimlane_name'])): ?>
                        <li>
                            <?= t('Swimlane:') ?>
                            <span><?= $this->text->e($task['swimlane_name']) ?></span>
                        </li>
                    <?php endif ?>
                    <li>
                        <?= t('Column:') ?>
                        <span><?= $this->text->e($task['column_title']) ?></span>
                    </li>
                    <li>
                        <?= t('Position:') ?>
                        <span><?= $task['position'] ?></span>
                    </li>

                    <?= $this->hook->render('template:task:details:second-column', ['task' => $task]) ?>
                </ul>
            </div>
            <div class="task-summary-column">
                <ul class="no-bullet">
                    <li>
                        <?= t('Assignee:') ?>
                        <span>
                        <?php if ($task['assignee_username']): ?>
                            <?= $this->text->e($task['assignee_name'] ?: $task['assignee_username']) ?>
                        <?php else: ?>
                            <?= t('not assigned') ?>
                        <?php endif ?>
                        </span>
                    </li>
                    <?php if ($task['creator_username']): ?>
                        <li>
                            <?= t('Creator:') ?>
                            <span><?= $this->text->e($task['creator_name'] ?: $task['creator_username']) ?></span>
                        </li>
                    <?php endif ?>
                    <?php if ($task['date_due']): ?>
                    <li>
                        <?= t('Due date:') ?>
                        <span><?= $this->dt->date($task['date_due']) ?></span>
                    </li>
                    <?php endif ?>
                    <?php if ($task['time_estimated']): ?>
                    <li>
                        <?= t('Time estimated:') ?>
                        <span><?= t('%s hours', $task['time_estimated']) ?></span>
                    </li>
                    <?php endif ?>
                    <?php if ($task['time_spent']): ?>
                    <li>
                        <?= t('Time spent:') ?>
                        <span><?= t('%s hours', $task['time_spent']) ?></span>
                    </li>
                    <?php endif ?>

                    <?= $this->hook->render('template:task:details:third-column', ['task' => $task]) ?>
                </ul>
            </div>
            <div class="task-summary-column">
                <ul class="no-bullet">
                    <li>
                        <?= t('Created:') ?>
                        <span><?= $this->dt->datetime($task['date_creation']) ?></span>
                    </li>
                    <li>
                        <?= t('Modified:') ?>
                        <span><?= $this->dt->datetime($task['date_modification']) ?></span>
                    </li>
                    <?php if ($task['date_completed']): ?>
                    <li>
                        <?= t('Completed:') ?>
                        <span><?= $this->dt->datetime($task['date_completed']) ?></span>
                    </li>
                    <?php endif ?>
                    <?php if ($task['date_started']): ?>
                    <li>
                        <?= t('Started:') ?>
                        <span><?= $this->dt->datetime($task['date_started']) ?></span>
                    </li>
                    <?php endif ?>
                    <?php if ($task['date_moved']): ?>
                    <li>
                        <?= t('Moved:') ?>
                        <span><?= $this->dt->datetime($task['date_moved']) ?></span>
                    </li>
                    <?php endif ?>

                    <?= $this->hook->render('template:task:details:fourth-column', ['task' => $task]) ?>
                </ul>
            </div>
        </div>
        <?php if (!empty($tags)): ?>
            <div class="task-tags">
                <ul>
                    <?php foreach ($tags as $tag): ?>
                        <li><?= $this->text->e($tag) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>
    </div>

    <?php if ($editable && empty($task['date_started'])): ?>
        <?= $this->url->button('fa-play', t('Set start date'), 'Task/TaskController', 'start', ['task_id' => $task['id'], 'project_id' => $task['project_id']], 'btn-default btn-header') ?>
    <?php endif ?>

    <?= $this->hook->render('template:task:details:bottom', ['task' => $task]) ?>
</section>
