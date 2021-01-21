'use strict';
var FormReBuild = (function () {
    var getList = function () {
        FormEvent.getList().then(function (result) {
            if (result.LIST) {
                let event;
                let button;
                let form;
                jQuery.each(result.LIST, function (i, rule) {
                    button = jQuery(rule.UF_BUTTON_SELECTOR);
                    form = jQuery(rule.UF_FORM_SELECTOR);
                    event = rule.UF_EVENT;

                    button.click(function () {
                        form.attr('form-event', event);
                    });
                    form.submit(function () {
                        let formEvent = jQuery(this).attr('form-event');
                        console.log(`add event ${formEvent}`);
                        dataLayer.push({'event': formEvent});
                    });
                });
            }
        });
    };

    return {
        getList: getList
    };
})();

jQuery(function () {
    FormReBuild.getList();
});
