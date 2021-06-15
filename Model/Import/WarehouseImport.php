<?php

namespace Yu\NovaPoshta\Model\Import;

/**
 * Import warehouses from novaposhta.ua
 */
class WarehouseImport
{
    /**
     * @var \Yu\NovaPoshta\Service\Curl
     */
    private $curl;

    /**
     * @var \Yu\NovaPoshta\Model\WarehouseFactory
     */
    private $warehouseFactory;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\Warehouse
     */
    private $warehouseResource;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory
     */
    private $warehouseCollectionFactory;

    /**
     * @param \Yu\NovaPoshta\Service\Curl                                    $curl
     * @param \Yu\NovaPoshta\Model\WarehouseFactory                          $warehouseFactory
     * @param \Yu\NovaPoshta\Model\ResourceModel\Warehouse                   $warehouseResource
     * @param \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory $warehouseCollectionFactory
     */
    public function __construct(
        \Yu\NovaPoshta\Service\Curl $curl,
        \Yu\NovaPoshta\Model\WarehouseFactory $warehouseFactory,
        \Yu\NovaPoshta\Model\ResourceModel\Warehouse $warehouseResource,
        \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory $warehouseCollectionFactory
    ) {
        $this->curl = $curl;
        $this->warehouseFactory = $warehouseFactory;
        $this->warehouseResource = $warehouseResource;
        $this->warehouseCollectionFactory = $warehouseCollectionFactory;
    }

    /**
     * @param \Closure $cl
     *
     * @return void
     */
    public function execute(\Closure $cl = null)
    {
        $warehousesFromNovaPoshta = $this->importWarehouses();
        if (($warehousesFromNovaPoshta == null) && is_callable($cl)) {
            $cl('Ошибка импорта отделений. Проверьте ключ API.');

            return;
        }

        $warehouses = $this->getWarehousesFromDb();

        foreach ($warehousesFromNovaPoshta as $warehouseFromNovaPoshta) {
            $key = array_search($warehouseFromNovaPoshta['ref'], array_column($warehouses, 'ref'), true);

            if ($key === false || ($key !== 0 && empty($key))) {
                $this->saveWarehouse($warehouseFromNovaPoshta);
            } elseif (isset($warehouses[$key]['warehouse_id'])) {
                if (($warehouses[$key]['ref'] !== $warehouseFromNovaPoshta['ref']) ||
                    ($warehouses[$key]['name_ua'] !== $warehouseFromNovaPoshta['name_ua']) ||
                    ($warehouses[$key]['name_ru'] !== $warehouseFromNovaPoshta['name_ru']) ||
                    ($warehouses[$key]['city_ref'] !== $warehouseFromNovaPoshta['city_ref']) ||
                    ($warehouses[$key]['number'] !== $warehouseFromNovaPoshta['number'])
                ) {
                    $warehouseId = $warehouses[$key]['warehouse_id'];
                    $this->saveWarehouse($warehouseFromNovaPoshta, $warehouseId);
                }
            }

            if ($cl !== null) {
                $cl($warehouseFromNovaPoshta['ref'] . ' ' . $warehouseFromNovaPoshta['name_ru']);
            }
        }
    }

    /**
     * @return array | null
     */
    private function importWarehouses()
    {
        $params = ['modelName' => 'AddressGeneral', 'calledMethod' => 'getWarehouses'];

        $data = $this->curl->getDataImport($params);

        if ($data) {
            $warehouseData = [];
            foreach ($data as $_data) {
                $warehouseData[] = [
                    'ref'      => $_data['Ref'],
                    'city_ref' => $_data['CityRef'],
                    'name_ua'  => $_data['Description'],
                    'name_ru'  => $_data['DescriptionRu'],
                    'number'   => $_data['Number'],
                ];
            }

            return $warehouseData;
        } else {
            return null;
        }
    }

    /**
     * Return Warehouses array
     *
     * @return array
     */
    private function getWarehousesFromDb()
    {
        $warehouseCollection = $this->warehouseCollectionFactory->create();

        $data = $warehouseCollection->load()->toArray();

        return $data['items'];
    }

    /**
     * @param array    $data
     * @param int|null $warehouseId
     */
    private function saveWarehouse(array $data, $warehouseId = null)
    {
        $warehouse = $this->warehouseFactory->create();
        $warehouse->setWarehouseId($warehouseId);
        $warehouse->setRef($data['ref']);
        $warehouse->setCityRef($data['city_ref']);
        $warehouse->setNameUa($data['name_ua']);
        $warehouse->setNameRu($data['name_ru']);
        $warehouse->setNumber($data['number']);
        $this->warehouseResource->save($warehouse);
    }
}
