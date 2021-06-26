<?php

namespace Yu\NovaPoshta\Service;

/**
 * Сервисный класс для синхронизации данных с novaposhta.ua
 */
class Curl
{
    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    private $curl;

    /**
     * @var \Yu\NovaPoshta\Model\Config
     */
    private $config;

    /**
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param \Yu\NovaPoshta\Model\Config         $config
     */
    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Yu\NovaPoshta\Model\Config $config
    ) {
        $this->curl = $curl;
        $this->config = $config;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function getDataImport($params)
    {
        $apiKey = $this->config->getApiKey();

        $params['apiKey'] = $apiKey;

        $this->curl->setHeaders(["content-type: application/json"]);
        $this->curl->post("https://api.novaposhta.ua/v2.0/json/", json_encode($params));

        $json = $this->curl->getBody();

        return json_decode($json, true)['data'];
    }
}
