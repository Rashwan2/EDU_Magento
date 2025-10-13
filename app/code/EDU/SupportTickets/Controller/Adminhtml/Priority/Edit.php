<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Priority;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::priorities';
    
    protected $pageFactory;
    protected $priorityRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $priorityId = $this->getRequest()->getParam('id');
        
        try {
            if ($priorityId) {
                $priority = $this->priorityRepository->getById($priorityId);
                $title = __('Edit Priority: %1', $priority->getName());
            } else {
                $priority = null;
                $title = __('New Priority');
            }
            
            $resultPage = $this->pageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend($title);
            
            return $resultPage;
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Priority not found.'));
            return $this->_redirect('supporttickets/priority/index');
        }
    }
}
