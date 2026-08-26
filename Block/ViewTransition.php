<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Block;

class ViewTransition extends \Magento\Framework\View\Element\Template
{
    protected const PRODUCT_IMAGE_TRANSITION_NAME = 'view-transition-to-gallery';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        protected \MageSuite\ThemeHelpers\Helper\Configuration\ViewTransition $configuration,
        protected \Magento\Framework\View\Helper\SecureHtmlRenderer $secureRenderer,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _toHtml(): string
    {
        $output = '';
        $storeId = (int)$this->_storeManager->getStore()->getId();

        if (!$this->configuration->isEnabled($storeId)) {
            return $output;
        }

        $css = $this->getCss($storeId);
        $js = $this->getJs($storeId);

        if ($css !== '') {
            $output .= $this->secureRenderer->renderTag('style', [], $css, false);
        }

        if ($js !== '') {
            $output .= $this->secureRenderer->renderTag('script', ['data-defer-ignore' => 'true'], $js, false);
        }

        return $output;
    }

    protected function getCss(int $storeId): string
    {
        $css = '@media (prefers-reduced-motion: no-preference) { @supports (view-transition-name: none) { @view-transition { navigation: auto; } } }';

        if ($this->isAnimationDisabled()) {
            return $css . '::view-transition-group(*),::view-transition-old(*),::view-transition-new(*){animation:none;}';
        }

        if ($this->configuration->isProductImageTransitionEnabled($storeId)) {
            $css .= $this->getProductImageDurationCss($storeId);
        }

        return $css;
    }

    protected function isAnimationDisabled(): bool
    {
        return (bool)$this->getData('is_animation_disabled');
    }

    protected function getProductImageDurationCss(int $storeId): string
    {
        $duration = $this->configuration->getProductImageDuration($storeId);

        if ($duration <= 0) {
            return '';
        }

        $name = self::PRODUCT_IMAGE_TRANSITION_NAME;

        return '::view-transition-group(' . $name . '),::view-transition-old(' . $name . '),::view-transition-new(' . $name . '){animation-duration:' . $duration . 'ms;}';
    }

    protected function getJs(int $storeId): string
    {
        if ($this->isAnimationDisabled() || !$this->configuration->isProductImageTransitionEnabled($storeId)) {
            return '';
        }

        $config = json_encode(
            [
                'name' => self::PRODUCT_IMAGE_TRANSITION_NAME,
                'gallerySelector' => $this->configuration->getProductImageGallerySelector($storeId),
                'gallerySelectorLeave' => $this->configuration->getProductImageGalleryLeaveSelector($storeId),
                'tileLinkSelector' => $this->configuration->getProductImageTileLinkSelector($storeId),
                'tileImageSelector' => $this->configuration->getProductImageTileImageSelector($storeId),
            ],
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        return 'const viewTransitionConfig = ' . $config . ';' . $this->getJsTemplate();
    }

    protected function getJsTemplate(): string
    {
        return <<<'JS'
let viewTransitionTarget = null;
const viewTransitionPageStorage = 'view-transition-page';

const findViewTransitionTileImage = (url) => {
    const { tileLinkSelector, tileImageSelector } = viewTransitionConfig;
    const tileLink = tileLinkSelector && url && document.querySelector(`${tileLinkSelector}[href="${url}"]`);

    return tileLink && (tileImageSelector && tileLink.querySelector(tileImageSelector) || tileLink) || null;
};

const setViewTransitionName = async (element, promise, handOffSelector) => {
    if (viewTransitionTarget) viewTransitionTarget.style.viewTransitionName = '';

    let target = element;
    viewTransitionTarget = target;
    target.style.viewTransitionName = viewTransitionConfig.name;

    const handOffToInitializedGallery = () => {
        if (viewTransitionTarget !== target) return;

        const initializedGallery = document.querySelector(handOffSelector);

        if (!initializedGallery || initializedGallery === target) {
            requestAnimationFrame(handOffToInitializedGallery);
            return;
        }

        target.style.viewTransitionName = '';
        target = initializedGallery;
        viewTransitionTarget = target;
        target.style.viewTransitionName = viewTransitionConfig.name;
    };

    if (handOffSelector) requestAnimationFrame(handOffToInitializedGallery);

    await promise.catch(() => {});

    if (viewTransitionTarget !== target) return;

    target.style.viewTransitionName = '';
    viewTransitionTarget = null;
};

window.addEventListener('pageswap', (e) => {
    if (!e.viewTransition) return;

    const { gallerySelector, gallerySelectorLeave } = viewTransitionConfig;
    const entryUrl = e.activation?.entry?.url;
    const destination = entryUrl ? new URL(entryUrl).href.split('#')[0] : '';
    const tileImage = findViewTransitionTileImage(destination);
    const isProductPage = document.body.classList.contains('catalog-product-view');
    const leaveSelector = gallerySelectorLeave || gallerySelector;
    const gallery = !tileImage && isProductPage && leaveSelector && document.querySelector(leaveSelector);
    const named = tileImage ? 'tile' : (gallery ? 'gallery' : '');

    sessionStorage.setItem(viewTransitionPageStorage, JSON.stringify({ url: location.href.split('#')[0], destination, named }));

    if (named) setViewTransitionName(tileImage || gallery, e.viewTransition.finished, '');
});

window.addEventListener('pagereveal', (e) => {
    const previousPage = JSON.parse(sessionStorage.getItem(viewTransitionPageStorage) || 'null');
    sessionStorage.removeItem(viewTransitionPageStorage);

    if (!e.viewTransition || !previousPage) return;
    if (previousPage.destination && previousPage.destination !== location.href.split('#')[0]) return;

    const { gallerySelector, gallerySelectorLeave } = viewTransitionConfig;
    const element = previousPage.named === 'tile'
        ? gallerySelector && document.querySelector(gallerySelector)
        : previousPage.named === 'gallery' && findViewTransitionTileImage(previousPage.url);

    if (!element) return;

    setViewTransitionName(element, e.viewTransition.finished, previousPage.named === 'tile' ? gallerySelectorLeave : '');
});
JS;
    }
}
