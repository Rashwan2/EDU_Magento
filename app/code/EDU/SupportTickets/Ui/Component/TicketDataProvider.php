<?php

namespace EDU\SupportTickets\Ui\Component;

use EDU\SupportTickets\Model\ResourceModel\Ticket\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class TicketDataProvider extends DataProvider
{
//    protected $collection;
//    protected $searchCriteriaBuilder;
//    protected $request;
//    protected $filterBuilder;
//
////    public function __construct(
////        CollectionFactory $collectionFactory,
////        SearchCriteriaBuilder $searchCriteriaBuilder,
////        RequestInterface $request,
////        FilterBuilder $filterBuilder
////    ) {
////        parent::__construct();
////        $this->collection = $collectionFactory->create();
////        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
////        $this->request = $request;
////        $this->filterBuilder = $filterBuilder;
////    }
//    public function __construct(
//        $name,
//        $primaryFieldName,
//        $requestFieldName,
//        CollectionFactory $collectionFactory,
//        array $meta = [],
//        array $data = []
//    ) {
//        // Create the collection and assign it immediately
//        $this->collection = $collectionFactory->create();
//
//        // Call the parent constructor - it will now have access to $this->collection
//        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
//    }
//
//    public function getData()
//    {
//        $collection = $this->getCollection();
//        $data = $collection->toArray();
//        return $data;
//    }
//
//    public function getCollection()
//    {
//        return $this->collection;
//    }
//
//    public function getSearchCriteria()
//    {
//        return $this->searchCriteriaBuilder->create();
//    }
//
//    public function getSearchResult()
//    {
//        return $this->getData();
//    }
}
