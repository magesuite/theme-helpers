define(['underscore'], function (_) {
    /**
     * Using MutationObserver, waits for the HTML element to be present and returns Promise resolved with the found element.
     * @param {string} selector CSS selector
     * @param {HTMLElement} scope Scope of the mutation observer, default window.document
     * @param {number} querySelectorSearchThrottle throttle time for element search in, by default 300ms
     * @returns {Promise<HTMLElement>}
     * @example
     *
     * waitForElement('.some-class').then(elem => {});
     * const element = await waitForElement('.some-class');
     */
    return function (selector, scope = document, querySelectorSearchThrottle = 300) {
        const throttledQuerySelector = _.throttle(selector => {
            try {
                return document.querySelector(selector);
            } catch (error) {
                console.error('Wrong query selecor provided');
            }
        }, querySelectorSearchThrottle);

        return new Promise(resolve => {
            const elem = throttledQuerySelector(selector);

            if (elem) {
                return resolve(elem);
            }

            const observer = new MutationObserver(mutations => {
                const elem = throttledQuerySelector(selector);

                if (elem) {
                    observer.disconnect();
                    resolve(elem);
                }
            });

            observer.observe(scope, {
                childList: true,
                subtree: true
            });
        });
    }
});
