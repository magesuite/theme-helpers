var config = {
    paths: {
        mgsGetJqueryWidgetInstance: 'MageSuite_ThemeHelpers/js/utils/get-jquery-widget-instance',
        mgsWaitForElement: 'MageSuite_ThemeHelpers/js/utils/wait-for-element'
    },
    config: {
        mixins: {
            'Magento_Checkout/js/model/step-navigator': {
                'MageSuite_ThemeHelpers/js/checkout/model/step-navigator-ext': true
            }
        }
    }
}
