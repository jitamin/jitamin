Hiject.Notification = function(app) {
    this.app = app;
};

Hiject.Notification.prototype.execute = function() {
    $(".alert-fade-out").delay(4000).fadeOut(800, function() {
        $(this).remove();
    });
};
