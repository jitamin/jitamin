Jitamin.Slider = function(app) {
    this.app = app;
};

Jitamin.Slider.prototype.isOpen = function() {
    return $('.sidecontent').width() > 0;
};

Jitamin.Slider.prototype.open = function(link) {
    var self = this;

    if (!self.isOpen()) {
        $.get(link, function(content) {
            var sidecontent = $('.sidecontent');
            sidecontent.addClass('active').html(content);
            $('.sidebar .slider').parent().addClass('active');
            $('.content-panel').on('click', function(){
                self.close();
            });
        });
    }
};

Jitamin.Slider.prototype.close = function(e) {
    if (this.isOpen()) {
        if (e) {
            e.preventDefault();
        }

        $(".sidecontent").removeClass('active').html();
        $('.sidebar .slider').parent().removeClass('active');
        this.executeOnClosedListeners();
    }
};

Jitamin.Slider.prototype.executeOnClosedListeners = function() {
    for (var className in this.app.controllers) {
        var controller = this.app.get(className);

        if (typeof controller.onPopoverClosed === "function") {
            controller.onPopoverClosed();
        }
    }
};