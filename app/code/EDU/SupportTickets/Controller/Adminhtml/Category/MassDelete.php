<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use EDU\SupportTickets\Model\ResourceModel\Category\CollectionFactory;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::categories';
    
    protected $filter;
    protected $collectionFactory;
    protected $categoryRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $categoryDeleted = 0;
            
            foreach ($collection->getItems() as $category) {
                $this->categoryRepository->delete($category);
                $categoryDeleted++;
            }

            if ($categoryDeleted) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', $categoryDeleted)
                );
            }
            
            return $resultRedirect->setPath('supporttickets/category/index');
            
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting categories.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        }
    }
}
