Jitamin.Popover = function(app) {
    this.app = app;
};

Jitamin.Popover.prototype.listen = function() {
    var self = this;

    $(document).on("click", ".popover", function(e) {
        self.onClick(e);
    });

    $(document).on("click", ".close-popover", function(e) {
        self.close(e);
    });

    $(document).on("click", "#popover-content", function(e) {
        e.stopPropagation();
    });
};

Jitamin.Popover.prototype.onClick = function(e) {
    e.preventDefault();
    e.stopPropagation();

    var target = e.currentTarget || e.target;
    var link = target.getAttribute("href");
    var size;

    if (! link) {
        link = target.getAttribute("data-href");
    }

    if($(target).hasClass('small')) {
        size = 'small';
    } else if($(target).hasClass('large')) {
        size = 'large';
    } else {
        size = 'medium';
    }

    if (link) {
        this.open(link, size);
    }
};

Jitamin.Popover.prototype.isOpen = function() {
    return $('#popover-container').size() > 0;
};

Jitamin.Popover.prototype.open = function(link, size) {
    var self = this;

    if (typeof size === 'undefined') {
        size = 'medium';
    }

    if (!self.isOpen()) {
        $.get(link, function(content) {
            $("body").prepend('<div id="popover-container"><div id="popover-content" class="'+size+'">' + content + '</div></div>');
            $('#popover-content h2').eq(0).append('<a href="#" class="pull-right close-popover"><i class="fa fa-close"></i></a>');
             /*
             var screenHeight = $(window).height(); //当前浏览器窗口的高 
             var scrolltop = $(document).scrollTop();//获取当前窗口距离页面顶部高度 
             var obj = $('#popover-content');
             var objTop = (screenHeight - obj.height())/2 + scrolltop; 
             obj.css({top: objTop + 'px'});
             */
            self.executeOnOpenedListeners();
        });
    }
};

Jitamin.Popover.prototype.close = function(e) {
    if (this.isOpen()) {
        if (e) {
            e.preventDefault();
        }

        $("#popover-container").remove();
        this.executeOnClosedListeners();
    }
};

Jitamin.Popover.prototype.ajaxReload = function(data, request, self) {
    var redirect = request.getResponseHeader("X-Ajax-Redirect");

    if (redirect === 'self') {
        window.location.reload();
    } else if (redirect && redirect.indexOf('#') > -1) {
        window.location = redirect.split('#')[0];
    } else if (redirect) {
        window.location = redirect;
    } else {
        $("#popover-content").html(data);
        $("#popover-content input[autofocus]").focus();
        self.executeOnOpenedListeners();
    }
};

Jitamin.Popover.prototype.executeOnOpenedListeners = function() {
    for (var className in this.app.controllers) {
        var controller = this.app.get(className);

        if (typeof controller.onPopoverOpened === "function") {
            controller.onPopoverOpened();
        }
    }

    this.afterOpen();
};

Jitamin.Popover.prototype.executeOnClosedListeners = function() {
    for (var className in this.app.controllers) {
        var controller = this.app.get(className);

        if (typeof controller.onPopoverClosed === "function") {
            controller.onPopoverClosed();
        }
    }
};

Jitamin.Popover.prototype.afterOpen = function() {
    var self = this;
    var popoverForm = $("#popover-content .popover-form");

    // Submit forms with Ajax request
    if (popoverForm) {
        popoverForm.on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: popoverForm.attr("action"),
                data: popoverForm.serialize(),
                success: function(data, textStatus, request) {
                    self.ajaxReload(data, request, self);
                },
                beforeSend: function() {
                    var button = $('.popover-form button[type="submit"]');
                    button.html('<i class="fa fa-spinner fa-pulse"></i> ' + button.html());
                    button.attr("disabled", true);
                }
            });
        });
    }

    // Submit link with Ajax request
    $(document).on("click", ".popover-link", function(e) {
        e.preventDefault();

        $.ajax({
            type: "GET",
            url: $(this).attr("href"),
            success: function(data, textStatus, request) {
                self.ajaxReload(data, request, self);
            }
        });
    });

    // Autofocus fields (html5 autofocus works only with page onload)
    $("[autofocus]").each(function() {
        $(this).focus();
    });

    this.app.datePicker();
    this.app.autoComplete();
    this.app.tagAutoComplete();

    new Vue({
        el: '#popover-container'
    });

    HJ.render();
};
