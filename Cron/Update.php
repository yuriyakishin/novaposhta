<?php

namespace Yu\NovaPoshta\Cron;

/**
 * Синхронизация (обновление) городов и отделений сохраненных в базе с novaposhta.ua
 * Рекомендуется выполнять синхронизацию 1 раз в сутки
 */
class Update
{
    /**
     * @var \Yu\NovaPoshta\Model\Import\CityImport
     */
    private $cityImport;

    /**
     * @var \Yu\NovaPoshta\Model\Import\WarehouseImport
     */
    private $warehouseImport;

    /**
     * @param \Yu\NovaPoshta\Model\Import\CityImport      $cityImport
     * @param \Yu\NovaPoshta\Model\Import\WarehouseImport $warehouseImport
     */
    public function __construct(
        \Yu\NovaPoshta\Model\Import\CityImport $cityImport,
        \Yu\NovaPoshta\Model\Import\WarehouseImport $warehouseImport
    ) {
        $this->cityImport = $cityImport;
        $this->warehouseImport = $warehouseImport;
    }

    /**
     *
     */
    public function execute()
    {
        $this->cityImport->execute();
        $this->warehouseImport->execute();
    }
}
