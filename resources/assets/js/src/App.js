Jitamin.App = function() {
    this.controllers = {};
};

Jitamin.App.prototype.get = function(controller) {
    return this.controllers[controller];
};

Jitamin.App.prototype.execute = function() {
    for (var className in Jitamin) {
        if (className !== "App") {
            var controller = new Jitamin[className](this);
            this.controllers[className] = controller;

            if (typeof controller.execute === "function") {
                controller.execute();
            }

            if (typeof controller.listen === "function") {
                controller.listen();
            }

            if (typeof controller.focus === "function") {
                controller.focus();
            }

            if (typeof controller.keyboardShortcuts === "function") {
                controller.keyboardShortcuts();
            }
        }
    }

    this.focus();
    this.sidebarToggle();
    this.chosen();
    this.keyboardShortcuts();
    this.datePicker();
    this.autoComplete();
    this.tagAutoComplete();

    new Vue({
        el: 'body'
    });
};

Jitamin.App.prototype.keyboardShortcuts = function() {
    var self = this;

    // Submit form
    Mousetrap.bindGlobal("mod+enter", function() {
        var forms = $("form");

        if (forms.length == 1) {
            forms.submit();
        } else if (forms.length > 1) {
            if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
                $(document.activeElement).parents("form").submit();
            } else if (self.get("Popover").isOpen()) {
                $("#popover-container form").submit();
            }
        }
    });

    // Close popover and dropdown
    Mousetrap.bindGlobal("esc", function() {
        self.get("Popover").close();
        self.get("Dropdown").close();
    });

    // Show keyboard shortcut
    Mousetrap.bind("?", function() {
        self.get("Popover").open($("body").data("keyboard-shortcut-url"));
    });
};

Jitamin.App.prototype.focus = function() {
    // Auto-select input fields
    $(document).on('focus', '.auto-select', function() {
        $(this).select();
    });

    // Workaround for chrome
    $(document).on('mouseup', '.auto-select', function(e) {
        e.preventDefault();
    });
};

Jitamin.App.prototype.sidebarToggle = function() {
    $(document).on("click", ".sidebar-toggle", function(e) {
        var wrapper = $(this).parents(".wrapper");
        e.preventDefault();

        if (wrapper.hasClass("wrapper-collapsed")) {
            wrapper.find(".sidebar").show("slow");
            wrapper.removeClass("wrapper-collapsed");
        } else {
            wrapper.find(".sidebar").hide("slow");
            wrapper.addClass("wrapper-collapsed");
        }
    });
};

Jitamin.App.prototype.chosen = function() {
    $(".chosen-select").each(function() {
        var searchThreshold = $(this).data("search-threshold");

        if (searchThreshold === undefined) {
            searchThreshold = 10;
        }

        $(this).chosen({
            width: "180px",
            no_results_text: $(this).data("notfound"),
            disable_search_threshold: searchThreshold
        });
    });

    $(".select-auto-redirect").change(function() {
        var regex = new RegExp($(this).data('redirect-regex'), 'g');
        window.location = $(this).data('redirect-url').replace(regex, $(this).val());
    });
};

Jitamin.App.prototype.datePicker = function() {
    var bodyElement = $("body");
    var dateFormat = bodyElement.data("js-date-format");
    var timeFormat = bodyElement.data("js-time-format");
    var lang = bodyElement.data("js-lang");

    $.datepicker.setDefaults($.datepicker.regional[lang]);
    $.timepicker.setDefaults($.timepicker.regional[lang]);

    // Datepicker
    $(".form-date").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: dateFormat,
        constrainInput: false
    });

    // Datetime picker
    $(".form-datetime").datetimepicker({
        dateFormat: dateFormat,
        timeFormat: timeFormat,
        constrainInput: false
    });
};

Jitamin.App.prototype.tagAutoComplete = function() {
    $(".tag-autocomplete").select2({
        tags: true
    })
};

Jitamin.App.prototype.autoComplete = function() {
    $(".autocomplete").each(function() {
        var input = $(this);
        var field = input.data("dst-field");
        var extraField = input.data("dst-extra-field");

        if ($('#form-' + field).val() == '') {
            input.parent().find("button[type=submit]").attr('disabled','disabled');
        }

        input.autocomplete({
            source: input.data("search-url"),
            minLength: 1,
            select: function(event, ui) {
                $("input[name=" + field + "]").val(ui.item.id);

                if (extraField) {
                    $("input[name=" + extraField + "]").val(ui.item[extraField]);
                }

                input.parent().find("button[type=submit]").removeAttr('disabled');
            }
        });
    });
};

Jitamin.App.prototype.hasId = function(id) {
    return !!document.getElementById(id);
};

Jitamin.App.prototype.showLoadingIcon = function() {
    $("body").append('<span id="app-loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span>');
};

Jitamin.App.prototype.hideLoadingIcon = function() {
    $("#app-loading-icon").remove();
};

Jitamin.App.prototype.formatDuration = function(d) {
    if (d >= 86400) {
        return Math.round(d/86400) + "d";
    }
    else if (d >= 3600) {
        return Math.round(d/3600) + "h";
    }
    else if (d >= 60) {
        return Math.round(d/60) + "m";
    }

    return d + "s";
};

Jitamin.App.prototype.isVisible = function() {
    var property = "";

    if (typeof document.hidden !== "undefined") {
        property = "visibilityState";
    } else if (typeof document.mozHidden !== "undefined") {
        property = "mozVisibilityState";
    } else if (typeof document.msHidden !== "undefined") {
        property = "msVisibilityState";
    } else if (typeof document.webkitHidden !== "undefined") {
        property = "webkitVisibilityState";
    }

    if (property != "") {
        return document[property] == "visible";
    }

    return true;
};
