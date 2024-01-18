<?php
declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Test\Integration\Helper;

/**
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 */
class ImageTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository;
    protected ?\MageSuite\ThemeHelpers\ViewModel\Image $imageViewModel;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->imageViewModel = $this->objectManager->create(\MageSuite\ThemeHelpers\ViewModel\Image::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture Magento/Catalog/_files/product_with_image.php
     */
    public function testItReturnsProductImageUrl()
    {
        $product = $this->productRepository->get('simple');
        $imageUrl = $this->imageViewModel->getImageUrl($product, 'category_page_grid');

        $this->assertStringContainsString('m/a/magento_image.jpg', $imageUrl);
    }
}
