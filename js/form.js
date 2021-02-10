'use strict';
var FormReBuild = (function () {
    let birixEvents = [];
    let userId;

    var getUserId = function () {
        FormEvent.getUserId().then(function (result) {
            userId = result.USER_ID;
        });
    };

    var getList = function () {
        FormEvent.getList().then(function (result) {
            if (result.LIST) {
                let event;
                let button;
                let form;
                let eventName;
                let userId = result.USER_ID;
                jQuery.each(result.LIST, function (i, rule) {
                    button = jQuery(rule.UF_BUTTON_SELECTOR);
                    form = jQuery(rule.UF_FORM_SELECTOR);
                    event = rule.UF_EVENT;
                    eventName = rule.UF_EVENT_NAME;

                    button.click(function () {
                        form.attr('form-event', event);
                    });
                    form.submit(function () {
                        let $thisForm = jQuery(this);
                        FormEvent.getBitrixEvents().then(function (bitrixEvents) {
                            let formEvent = $thisForm.attr('form-event');

                            if(bitrixEvents.LIST.EVENT_TYPE === eventName &&
                                parseInt(bitrixEvents.LIST.USER_ID) === parseInt(userId) ){
                                console.log(`add event ${formEvent}`);
                                dataLayer.push({'event': formEvent});
                            } else{
                                console.log(`event ${formEvent} didn't sent`);
                            }

                        });
                    });
                });
            }
        });
    };

    return {
        getUserId: getUserId,
        getList: getList
    };
})();

jQuery(function () {
    FormReBuild.getUserId();
    FormReBuild.getList();
});
