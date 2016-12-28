Jitamin.Skin = function(app) {
    this.app = app;
};

Jitamin.Skin.prototype.listen = function() {
    this.changeSkin();
    this.changeLayout();
};

Jitamin.Skin.prototype.changeSkin = function() {
    var self = this;

    $('#form-skin, #form-application_skin').on("change", function(){
        $('body').removeClass();
        $('body').addClass('skin-' + $(this).find(':selected').val());
    });
};

Jitamin.Skin.prototype.changeLayout = function() {
    var self = this;

    $('#form-layout, #form-application_layout').on("change", function(){
        $('body').removeClass('fluid fixed');
        $('body').addClass($(this).find(':selected').val());
    });
};