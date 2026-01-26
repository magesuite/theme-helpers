<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Test\Unit\Block;

class SpeculationRulesTest extends \PHPUnit\Framework\TestCase
{
    public function testToHtmlRendersSpeculationRulesScript(): void
    {
        $context = $this->createMock(\Magento\Framework\View\Element\Template\Context::class);
        $configurationHelper = $this->createMock(\MageSuite\ThemeHelpers\Helper\Configuration::class);
        $secureRenderer = $this->createMock(\Magento\Framework\View\Helper\SecureHtmlRenderer::class);
        $storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $store = $this->createMock(\Magento\Store\Api\Data\StoreInterface::class);

        $context->method('getStoreManager')->willReturn($storeManager);

        $configuration = $this->getSpeculationRulesJson();
        $storeId = 2;
        $renderedScript = '<script type="speculationrules">' . $configuration . '</script>';

        $store
            ->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);
        $storeManager
            ->expects($this->once())
            ->method('getStore')
            ->willReturn($store);

        $configurationHelper
            ->expects($this->once())
            ->method('getSpeculationRulesConfiguration')
            ->with($storeId)
            ->willReturn($configuration);

        $secureRenderer
            ->expects($this->once())
            ->method('renderTag')
            ->with(
                'script',
                ['type' => 'speculationrules'],
                $configuration,
                false
            )
            ->willReturn($renderedScript);

        $block = new \MageSuite\ThemeHelpers\Block\SpeculationRules($context, $configurationHelper, $secureRenderer);
        $reflection = new \ReflectionClass($block);
        $method = $reflection->getMethod('_toHtml');
        $method->setAccessible(true);

        $result = $method->invoke($block);

        $this->assertSame($renderedScript, $result);
    }


    public function testToHtmlReturnsEmptyStringWhenConfigurationIsEmpty(): void
    {
        $context = $this->createMock(\Magento\Framework\View\Element\Template\Context::class);
        $configurationHelper = $this->createMock(\MageSuite\ThemeHelpers\Helper\Configuration::class);
        $secureRenderer = $this->createMock(\Magento\Framework\View\Helper\SecureHtmlRenderer::class);
        $storeManager = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $store = $this->createMock(\Magento\Store\Api\Data\StoreInterface::class);

        $context->method('getStoreManager')->willReturn($storeManager);
        $storeId = 4;
        $configuration = '';

        $store
            ->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $storeManager
            ->expects($this->once())
            ->method('getStore')
            ->willReturn($store);

        $configurationHelper
            ->expects($this->once())
            ->method('getSpeculationRulesConfiguration')
            ->with($storeId)
            ->willReturn($configuration);

        $secureRenderer
            ->expects($this->never())
            ->method('renderTag');

        $block = new \MageSuite\ThemeHelpers\Block\SpeculationRules($context, $configurationHelper, $secureRenderer);
        $reflection = new \ReflectionClass($block);
        $method = $reflection->getMethod('_toHtml');
        $method->setAccessible(true);

        $result = $method->invoke($block);

        $this->assertSame('', $result);
    }

    private function getSpeculationRulesJson(): string
    {
        return <<<JSON
            {
                "prefetch": [
                    {
                        "where": {
                            "and": [
                                { "not": { "selector_matches": "a[href*='/customer/account']" }},
                                { "not": { "selector_matches": "a[href*='/checkout']" }},
                                { "or": [
                                    { "selector_matches": ".cs-image-teaser__link" },
                                    { "selector_matches": ".cs-product-tile__name-link" },
                                    { "selector_matches": ".cs-product-tile__thumbnail-link" },
                                    { "selector_matches": ".cs-product-tile__container" },
                                    { "selector_matches": ".cs-offcanvas-navigation__link" },
                                    { "selector_matches": ".cs-navigation__link" },
                                    { "selector_matches": ".cs-footer-links__item a" },
                                    { "selector_matches": ".cs-brand-carousel__slide-link" }
                                ]}
                            ]
                        },
                      "eagerness": "moderate"
                    }
                ]
            }
        JSON;
    }
}
