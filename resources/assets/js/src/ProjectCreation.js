Hiject.ProjectCreation = function(app) {
    this.app = app;
};

Hiject.ProjectCreation.prototype.onPopoverOpened = function() {
    $('#project-creation-form #form-src_project_id').on('change', function() {
        var srcProjectId = $(this).val();

        if (srcProjectId == 0) {
            $(".project-creation-options").hide();
        } else {
            $(".project-creation-options").show();
        }
    });
};
