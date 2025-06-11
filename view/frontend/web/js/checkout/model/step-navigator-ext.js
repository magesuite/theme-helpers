/**
 * Mixin to extend the step navigator to update the skip link element's ID
 */
define([
    'mage/utils/wrapper'
], function (wrapper) {
    'use strict';

    const skipLinkElement = document.querySelector('.cs-skip-links__link');

    return function (stepNavigator) {
        if (skipLinkElement) {
            const updateSkipLinkHref = () => setTimeout(() => skipLinkElement.setAttribute('href', window.location.hash), 10);

            updateSkipLinkHref();

            stepNavigator.setHash = wrapper.wrapSuper(stepNavigator.setHash, function (hash) {
                this._super(hash);
                updateSkipLinkHref();
            });

            stepNavigator.navigateTo = wrapper.wrapSuper(stepNavigator.navigateTo, function (code, scrollToElementId) {
                this._super(code, scrollToElementId);
                updateSkipLinkHref();
            });
        }

        return stepNavigator;
    };
});
