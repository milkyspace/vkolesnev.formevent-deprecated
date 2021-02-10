window.FormEventMID = window.FormEventMID || 'vkolesnev.formevent';
window.FormEvent    = (function() {
    'use strict';

    return {
        ajax(action, fields) {
            fields = fields || {};

            fields.ACTION = action;
            fields.MODULE = window.FormEventMID;

            return new Promise(function(resolve, reject) {
                jQuery.ajax({
                    type:     'post',
                    dataType: 'json',
                    data:     fields,

                    success: function(json) {
                        if (json.SUCCESS === false) {
                            reject(json.ERRORS);
                        } else {
                            resolve(json.DATA);
                        }
                    },

                    error: function(e) {
                        reject(['Unexpected error']);
                    }
                });
            });
        },

        getList() {
            return this.ajax('GETLIST', {});
        },

        getBitrixEvents() {
            return this.ajax('GETBITRIXEVENTS', {});
        },

        getUserId() {
            return this.ajax('GETUSERID', {});
        }

    };
})();
