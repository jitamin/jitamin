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
            $('.sidecontent').addClass('active').html(content);
            $('.sidebar .slider-menu').parent().addClass('active');

            $('.content-panel').on('click', function(){
                self.close();
            });
            self.executeOnOpenedListeners();
        });
    }
};

Jitamin.Slider.prototype.close = function(e) {
    if (this.isOpen()) {
        if (e) {
            e.preventDefault();
        }

        $(".sidecontent").removeClass('active').html();
        $('.sidebar .slider-menu').parent().removeClass('active');
        this.executeOnClosedListeners();
    }
};

Jitamin.Slider.prototype.executeOnOpenedListeners = function() {
    for (var className in this.app.controllers) {
        var controller = this.app.get(className);

        if (typeof controller.onSliderOpened === "function") {
            controller.onSliderOpened();
        }
    }
};

Jitamin.Slider.prototype.executeOnClosedListeners = function() {
    for (var className in this.app.controllers) {
        var controller = this.app.get(className);

        if (typeof controller.onSliderClosed === "function") {
            controller.onSliderClosed();
        }
    }
};