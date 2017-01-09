<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('Project/AnalyticController', 'taskDistribution') ?>>
            <?= $this->url->link(t('Task distribution'), 'Project/AnalyticController', 'taskDistribution', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/AnalyticController', 'userDistribution') ?>>
            <?= $this->url->link(t('User repartition'), 'Project/AnalyticController', 'userDistribution', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/AnalyticController', 'cfd') ?>>
            <?= $this->url->link(t('Cumulative flow diagram'), 'Project/AnalyticController', 'cfd', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/AnalyticController', 'burndown') ?>>
            <?= $this->url->link(t('Burndown chart'), 'Project/AnalyticController', 'burndown', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/AnalyticController', 'averageTimeByColumn') ?>>
            <?= $this->url->link(t('Average time into each column'), 'Project/AnalyticController', 'averageTimeByColumn', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/AnalyticController', 'leadAndCycleTime') ?>>
            <?= $this->url->link(t('Lead and cycle time'), 'Project/AnalyticController', 'leadAndCycleTime', ['project_id' => $project['id']]) ?>
        </li>
        <li <?= $this->app->setActive('Project/AnalyticController', 'timeComparison') ?>>
            <?= $this->url->link(t('Estimated vs actual time'), 'Project/AnalyticController', 'timeComparison', ['project_id' => $project['id']]) ?>
        </li>

        <?= $this->hook->render('template:analytic:subside', ['project' => $project]) ?>
    </ul>
</div>