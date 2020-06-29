Jitamin.TwoQrcode = function (app) {
    this.app = app;
};

Jitamin.TwoQrcode.prototype.execute = function () {
    if (this.app.hasId("two-qrcode")) {
        this.show();
    }
};

Jitamin.TwoQrcode.prototype.show = function () {
    var qrcodeDiv = document.getElementById('two-qrcode');
    var urlData = qrcodeDiv.getAttribute("data-url");
    var qrcode = new QRCode("two-qrcode", {
        text: urlData,
        width: 200,
        height: 200,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
};
