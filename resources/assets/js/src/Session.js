Hiject.Session = function(app) {
    this.app = app;
};

Hiject.Session.prototype.execute = function() {
    window.setInterval(this.checkSession, 60000);
};

Hiject.Session.prototype.checkSession = function() {
    if (! $(".form-login").length) {
        $.ajax({
            cache: false,
            url: $("body").data("status-url"),
            statusCode: {
                401: function() {
                    window.location = $("body").data("login-url");
                }
            }
        });
    }
};
