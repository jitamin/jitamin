Hiject.Skin = function(app) {
    this.app = app;
};

Hiject.Skin.prototype.listen = function() {
    this.changeSkin();
};

Hiject.Skin.prototype.changeSkin = function() {
    var self = this;

    $('#form-skin, #form-application_skin').on("change", function(){
        $('body').removeClass();
        $('body').addClass('skin-' + $(this).find(':selected').val());
    });
};