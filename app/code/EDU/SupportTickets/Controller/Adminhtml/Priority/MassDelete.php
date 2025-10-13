<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Priority;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use EDU\SupportTickets\Model\ResourceModel\Priority\CollectionFactory;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::priorities';
    
    protected $filter;
    protected $collectionFactory;
    protected $priorityRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $priorityDeleted = 0;
            
            foreach ($collection->getItems() as $priority) {
                $this->priorityRepository->delete($priority);
                $priorityDeleted++;
            }

            if ($priorityDeleted) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', $priorityDeleted)
                );
            }
            
            return $resultRedirect->setPath('supporttickets/priority/index');
            
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting priorities.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        }
    }
}
