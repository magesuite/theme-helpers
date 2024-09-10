<?php
declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Plugin\Magento\Framework\Config\View;

class CacheConfigData
{
    protected \Magento\Framework\Serialize\SerializerInterface $serializer;
    protected \Magento\Framework\Config\CacheInterface $cache;
    protected array $data = [];

    public function __construct(
        \Magento\Framework\Config\CacheInterface $cache,
        \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    public function aroundRead(\Magento\Framework\Config\View $subject, callable $proceed, $scope = null)
    {
        $targetScope = $scope ?? 'global';

        if (!empty($this->data[$targetScope])) {
            return $this->data[$targetScope];
        }

        $cacheKey = '\Magento\Framework\Config\View::read_' . $targetScope;

        if ($value = $this->cache->load($cacheKey)) {
            $this->data[$targetScope] = $this->serializer->unserialize($value);
            return $this->data[$targetScope];
        }

        $result = $proceed($scope);
        $this->cache->save($this->serializer->serialize($result), $cacheKey, ['view.xml']);
        $this->data[$targetScope] = $result;

        return $result;
    }
}
