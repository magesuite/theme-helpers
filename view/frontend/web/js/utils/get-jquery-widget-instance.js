define(['jquery'], function ($) {
    /**
     * Returns promise with jQuery UI widget instance.
     * @param {Object} widgetData
     * @param {HTMLElement} widgetData.element - Widget's HTML element
     * @param {string} widgetData.widgetName - Widget's name (this.widgetFullName)
     *      e.g. "$.widget('mage.priceBox', {..." => magePriceBox
     * @param {string=} widgetData.widgetCreateEventName - Widget's event name triggered after initialization,
     *      e.g. "$.widget('mage.priceBox', {..." => priceboxcreate (usually widget name + "create", all lower case)
     * @param {boolean=} wait - if true, waits for widget initialization.
     */
    return function ({element, widgetName, widgetCreateEventName}, wait = false) {
        return new Promise(resolve => {
            const $element = $(element);

            if (!element || !$element.length) {
                throw new Error('Please specify HTML element.')
            }

            const $widget = $element.data(widgetName);

            if ($widget) {
                resolve($widget);
            } else if (!wait) {
                resolve(null)
            } else {
                if (!widgetCreateEventName) {
                    throw new Error('Please specify widgetCreateEventName')
                }

                $element.one(widgetCreateEventName, () => {
                    resolve($element.data(widgetName));
                });
            }
        })
    }
});
