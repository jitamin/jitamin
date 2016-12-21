Jitamin.Session = function(app) {
    this.app = app;
};

Jitamin.Session.prototype.execute = function() {
    window.setInterval(this.checkSession, 60000);
};

Jitamin.Session.prototype.checkSession = function() {
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
