<?php
namespace Myv\StoreLocator\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Myv\StoreLocator\Model\StoreLocatorFactory;
use Myv\StoreLocator\Model\StoreLocatorRepository;

class CreateTestData implements DataPatchInterface {
    protected $storeLocatorModelFactory;
    protected $storeLocatorRepository;

    public function __construct(
        StoreLocatorFactory $storeLocatorFactory,
        StoreLocatorRepository $storeLocatorRepository
    ) {
        $this->storeLocatorModelFactory = $storeLocatorFactory;
        $this->storeLocatorRepository = $storeLocatorRepository;
    }

    public function apply() {
        $testStore1 = $this->storeLocatorModelFactory->create();
        $testStore1->setName("MyStore");
        $testStore1->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing.");
        $testStore1->setImage("https://i.cbc.ca/1.3013607.1427655800!/cpImage/httpImage/future-shop-closed-20150328.jpg");
        $testStore1->setOpenTime("7:30");
        $testStore1->setCloseTime("22:30");
        $testStore1->setLatitude(49.5504);
        $testStore1->setLongitude(25.5891);
        $this->storeLocatorRepository->save($testStore1);

        $testStore2 = $this->storeLocatorModelFactory->create();
        $testStore2->setName("TheStore");
        $testStore2->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.");
        $testStore2->setImage("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSo56jPdiqXhXrNfGd0xHqrjR_m95tRN3bK_g&usqp=CAU");
        $testStore2->setOpenTime("9:00");
        $testStore2->setCloseTime("22:30");
        $testStore2->setLatitude(49.5627);
        $testStore2->setLongitude(25.6039);
        $this->storeLocatorRepository->save($testStore2);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies() {
        return [];
    }
 
    /**
     * {@inheritdoc}
     */
    public function getAliases() {
        return [];
    }
}