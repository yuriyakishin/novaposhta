<?php
declare(strict_types = 1);

namespace Yu\NovaPoshta\Model;

class Config
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return (string)$this->scopeConfig->getValue(
            'carriers/novaposhta/lang',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getCityFast()
    {
        return $this->scopeConfig->getValue(
            'carriers/novaposhta/city_fast',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCitySender(): string
    {
        return (string) $this->scopeConfig->getValue(
            'carriers/novaposhta/city_sender',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getCarrierTitle(): string
    {
        return (string) $this->scopeConfig->getValue(
            'carriers/novaposhta/title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getNameWarehouseToWarehouse(): string
    {
        return (string) $this->scopeConfig->getValue(
            'carriers/novaposhta/name_ww',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getNameWarehouseToDoors(): string
    {
        return (string) $this->scopeConfig->getValue(
            'carriers/novaposhta/name_wd',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return (string) $this->scopeConfig->getValue(
            'carriers/novaposhta/api_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
