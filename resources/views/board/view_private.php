<section id="main">

    <?= $this->projectHeader->render($project, 'BoardController', 'show', true) ?>

    <?= $this->render('board/table_container', [
        'project'                        => $project,
        'swimlanes'                      => $swimlanes,
        'board_private_refresh_interval' => $board_private_refresh_interval,
        'board_highlight_period'         => $board_highlight_period,
    ]) ?>

</section>
