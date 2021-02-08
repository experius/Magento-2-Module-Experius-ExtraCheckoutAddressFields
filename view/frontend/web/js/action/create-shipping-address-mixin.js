define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction, messageContainer) {

            if (messageContainer['extension_attributes'] === undefined) {
                messageContainer['extension_attributes'] = {};
            }
            if (messageContainer.custom_attributes != undefined) {
                $.each(messageContainer.custom_attributes , function( key, value ) {
                    if($.isPlainObject(value)) {
                        key = value['attribute_code'];
                    }
                    if($.isPlainObject(value)){
                        value = value['value'];
                    }

                    messageContainer['extension_attributes'][key] = value;
                });
            }

            return originalAction(messageContainer);
        });
    };
});