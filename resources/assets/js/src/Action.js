Jitamin.Action = function(app) {
    this.app = app;
};

Jitamin.Action.prototype.listen = function() {
    this.dragAndDrop();
};

Jitamin.Action.prototype.dragAndDrop = function() {
    var self = this;

    $(".draggable-row-handle").mouseenter(function() {
        $(this).parent().parent().addClass("draggable-item-hover");
    }).mouseleave(function() {
        $(this).parent().parent().removeClass("draggable-item-hover");
    });

    $(".actions-table tbody").sortable({
        forcePlaceholderSize: true,
        handle: "td:first i",
        helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });

            return ui;
        },
        stop: function(event, ui) {
            var action = ui.item;
            action.removeClass("draggable-item-selected");
            self.savePosition(action.data("action-id"), action.index() + 1);
        },
        start: function(event, ui) {
            ui.item.addClass("draggable-item-selected");
        }
    }).disableSelection();
};

Jitamin.Action.prototype.savePosition = function(actionId, position) {
    var url = $(".actions-table").data("save-position-url");
    var self = this;

    this.app.showLoadingIcon();

    $.ajax({
        cache: false,
        url: url,
        contentType: "application/json",
        type: "POST",
        processData: false,
        data: JSON.stringify({
            "action_id": actionId,
            "position": position
        }),
        complete: function() {
            self.app.hideLoadingIcon();
        }
    });
};
