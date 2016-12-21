Jitamin.Task = function(app) {
    this.app = app;
};

Jitamin.Task.prototype.keyboardShortcuts = function() {
    var taskView = $("#task-view");
    var self = this;

    if (this.app.hasId("task-view")) {
        Mousetrap.bind("e", function() {
            self.app.get("Popover").open(taskView.data("edit-url"));
        });

        Mousetrap.bind("c", function() {
            self.app.get("Popover").open(taskView.data("comment-url"));
        });

        Mousetrap.bind("s", function() {
            self.app.get("Popover").open(taskView.data("subtask-url"));
        });

        Mousetrap.bind("l", function() {
            self.app.get("Popover").open(taskView.data("internal-link-url"));
        });
    }
};

Jitamin.Task.prototype.onPopoverOpened = function() {
    var self = this;
    var reloadingProjectId = 0;

    self.renderPickers();

    // Assign to me
    $(document).on("click", ".assign-me", function(e) {
        var currentId = $(this).data("current-id");
        var dropdownId = "#" + $(this).data("target-id");

        e.preventDefault();

        if ($(dropdownId + ' option[value=' + currentId + ']').length) {
            $(dropdownId).val(currentId);
        }
    });

    // Reload page when a destination project is changed
    $(document).on("change", "select.task-reload-project-destination", function() {
        if (reloadingProjectId > 0) {
            $(this).val(reloadingProjectId);
        }
        else {
            reloadingProjectId = $(this).val();
            var url = $(this).data("redirect").replace(/PROJECT_ID/g, reloadingProjectId);

            $(".loading-icon").show();

            $.ajax({
                type: "GET",
                url: url,
                success: function(data, textStatus, request) {
                    reloadingProjectId = 0;
                    $(".loading-icon").hide();
                    self.app.get("Popover").ajaxReload(data, request, self.app.get("Popover"));
                }
            });
        }
    });
};

Jitamin.Task.prototype.renderPickers = function() {

    function renderColorOption(color) {
        return $(
            '<div class="color-picker-option">' +
            '<div class="color-picker-rectangle color-' + color.id + '">' + color.text + 
            '</div>' +
            '</div>'
        );
    }

    function renderPriorityOption(priority) {
        return $(
            '<div class="priority-picker-option">' +
            '<div class="priority-picker-circle color-priority-' + priority.id + '"></div>' +
            '<div class="priority-picker-label">' + priority.text + '</div>' +
            '</div>'
        );
    }

    $(".color-picker").select2({
        minimumResultsForSearch: Infinity,
        templateResult: renderColorOption,
        templateSelection: renderColorOption
    });

    $(".priority-picker").select2({
        minimumResultsForSearch: Infinity,
        templateResult: renderPriorityOption,
        templateSelection: renderPriorityOption
    });
};
