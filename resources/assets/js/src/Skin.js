Jitamin.Skin = function(app) {
    this.app = app;
};

Jitamin.Skin.prototype.listen = function() {
    this.changeSkin();
};

Jitamin.Skin.prototype.changeSkin = function() {
    var self = this;

    $('#form-skin, #form-application_skin').on("change", function(){
        $('body').removeClass();
        $('body').addClass('skin-' + $(this).find(':selected').val());
    });
};