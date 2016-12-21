Jitamin.Category = function(app) {
    this.app = app;
};

Jitamin.Category.prototype.listen = function() {
    this.dragAndDrop();
};

Jitamin.Category.prototype.dragAndDrop = function() {
    var self = this;

    $(".draggable-row-handle").mouseenter(function() {
        $(this).parent().parent().addClass("draggable-item-hover");
    }).mouseleave(function() {
        $(this).parent().parent().removeClass("draggable-item-hover");
    });

    $(".categories-table tbody").sortable({
        forcePlaceholderSize: true,
        handle: "td:first i",
        helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });

            return ui;
        },
        stop: function(event, ui) {
            var category = ui.item;
            category.removeClass("draggable-item-selected");
            self.savePosition(category.data("category-id"), category.index() + 1);
        },
        start: function(event, ui) {
            ui.item.addClass("draggable-item-selected");
        }
    }).disableSelection();
};

Jitamin.Category.prototype.savePosition = function(categoryId, position) {
    var url = $(".categories-table").data("save-position-url");
    var self = this;

    this.app.showLoadingIcon();

    $.ajax({
        cache: false,
        url: url,
        contentType: "application/json",
        type: "POST",
        processData: false,
        data: JSON.stringify({
            "category_id": categoryId,
            "position": position
        }),
        complete: function() {
            self.app.hideLoadingIcon();
        }
    });
};
