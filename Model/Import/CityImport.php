<?php

namespace Yu\NovaPoshta\Model\Import;

/**
 * Import cities from novaposhta.ua
 */
class CityImport
{
    /**
     * @var \Yu\NovaPoshta\Service\Curl
     */
    private $curl;

    /**
     * @var \Yu\NovaPoshta\Model\CityFactory
     */
    private $cityFactory;

    /**
     * @var \Yu\NovaPoshta\Model\City\ResourceModel\City
     */
    private $cityResource;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @param \Yu\NovaPoshta\Service\Curl                               $curl
     * @param \Yu\NovaPoshta\Model\CityFactory                          $cityFactory
     * @param \Yu\NovaPoshta\Model\ResourceModel\City                   $cityResource
     * @param \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     */
    public function __construct(
        \Yu\NovaPoshta\Service\Curl $curl,
        \Yu\NovaPoshta\Model\CityFactory $cityFactory,
        \Yu\NovaPoshta\Model\ResourceModel\City $cityResource,
        \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
    ) {
        $this->curl = $curl;
        $this->cityFactory = $cityFactory;
        $this->cityResource = $cityResource;
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    /**
     * @param \Closure $cl
     * @return void
     */
    public function execute(\Closure $cl = null)
    {
        $citiesFromNovaPoshta = $this->importCities();
        if ($citiesFromNovaPoshta === null && is_callable($cl)) {
            $cl('Ошибка импорта городов. Проверьте ключ API.');
            return;
        }
        
        $cities = $this->getCitiesFromDb();

        foreach ($citiesFromNovaPoshta as $cityFromNovaPoshta) {
            $key = array_search($cityFromNovaPoshta['ref'], array_column($cities, 'ref'), true);

            if ($key === false || ($key !== 0 && empty($key))) {
                $this->saveCity($cityFromNovaPoshta);
            } elseif (isset($cities[$key]['city_id'])) {
                if (($cities[$key]['ref'] !== $cityFromNovaPoshta['ref']) ||
                    ($cities[$key]['name_ua'] !== $cityFromNovaPoshta['name_ua']) ||
                    ($cities[$key]['name_ru'] !== $cityFromNovaPoshta['name_ru']) ||
                    ($cities[$key]['area'] !== $cityFromNovaPoshta['area']) ||
                    ($cities[$key]['type_ua'] !== $cityFromNovaPoshta['type_ua']) ||
                    ($cities[$key]['type_ru'] !== $cityFromNovaPoshta['type_ru'])
                ) {
                    $cityId = $cities[$key]['city_id'];
                    $this->saveCity($cityFromNovaPoshta, $cityId);
                }
            }

            if (is_callable($cl)) {
                $cl($cityFromNovaPoshta['ref'] . ' ' . $cityFromNovaPoshta['name_ru']);
            }
        }
    }

    /**
     * @param array $data
     * @param int|null $cityId
     */
    private function saveCity(array $data, $cityId = null)
    {
        $cityModel = $this->cityFactory->create();
        $cityModel->setCityId($cityId);
        $cityModel->setRef($data['ref']);
        $cityModel->setNameUa(($data['name_ua'] ?: $data['name_ru']));
        $cityModel->setNameRu(($data['name_ru'] ?: $data['name_ua']));
        $cityModel->setArea($data['area']);
        $cityModel->setTypeUa($data['type_ua']);
        $cityModel->setTypeRu($data['type_ru']);
        $this->cityResource->save($cityModel);
    }

    /**
     * Return cities array
     *
     * @return array
     */
    private function getCitiesFromDb()
    {
        $cityCollection = $this->cityCollectionFactory->create();

        $data = $cityCollection->load()->toArray();
        return $data['items'];
    }

    /**
     * @return array | null
     */
    private function importCities()
    {
        $params = ['modelName' => 'Address', 'calledMethod' => 'getCities'];

        $data = $this->curl->getDataImport($params);

        if ($data) {
            $cityData = [];
            foreach ($data as $_data) {
                $cityData[] = [
                    'ref'     => $_data['Ref'],
                    'name_ua' => $_data['Description'],
                    'name_ru' => $_data['DescriptionRu'],
                    'area'    => $_data['Area'] ?? '',
                    'type_ua' => $_data['SettlementTypeDescription'] ?? '',
                    'type_ru' => $_data['SettlementTypeDescriptionRu'] ?? '',
                ];
            }
            return $cityData;
        } else {
            return null;
        }
    }
}
