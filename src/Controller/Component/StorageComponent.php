<?php

namespace MakvilleStorage\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Locator\TableLocator;

/**
 * Storage component
 */
class StorageComponent extends Component {

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    /**
     *
     * @var Cake\ORM\Locator\TableLocator
     */
    private $locator;
    private $bucketsTable;
    private $itemsTable;
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->locator = new TableLocator();
        $config = $this->locator->exists('MakvilleStorage.Items') ? [] : ['className' => 'MakvilleStorage\Model\Table\ItemsTable'];
        $this->itemsTable = $this->locator->get('MakvilleStorage.Items', $config);
        $config = $this->locator->exists('MakvilleStorage.Buckets') ? [] : ['className' => 'MakvilleStorage\Model\Table\BucketsTable'];
        $this->bucketsTable = $this->locator->get('MakvilleStorage.Buckets', $config);
    }

    public function getItem($itemId) {
        return $this->itemsTable->get($itemId);
    }
    
    public function getBucketContent ($bucketId = null) {
        return $this->bucketsTable->getBucketContent($bucketId);
    }
    
    public function deleteItem($itemId) {
        $item = $this->itemsTable->get($itemId);
        return $this->itemsTable->deleteItem($item);
    }
    
    public function deleteBucket($bucketId) {
        return $this->bucketsTable->deleteBucket($bucketId);
    }
}
