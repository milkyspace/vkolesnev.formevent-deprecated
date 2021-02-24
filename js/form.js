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
                        console.log(`event ${event} init`);
                        form.attr('form-event', event);
                    });
                    form.submit(function () {
                        console.log('form submit');
                        let $thisForm = jQuery(this);
                        FormEvent.getBitrixEvents().then(function (bitrixEvents) {
                            console.log(bitrixEvents);
                            let formEvent = $thisForm.attr('form-event');

                            let sent = false;
                            jQuery.each(bitrixEvents.LIST, function (i, bitrixEvent) {
                                if(bitrixEvent.EVENT_TYPE === eventName &&
                                    bitrixEvent.USER_ID === userId ){
                                    sent = true;
                                }
                            });

                            if(sent){
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
    // FormReBuild.getUserId();
    FormReBuild.getList();
});
