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

        $this->assertEquals('http://localhost/media/catalog/product/thumbnail/26eaa807ec8b64a575b4edfae60a7ba998dd4555a5440d34f8aa686f/small_image/13353/240x300/000/0/m/a/magento_image.jpg', $imageUrl);
    }
}
