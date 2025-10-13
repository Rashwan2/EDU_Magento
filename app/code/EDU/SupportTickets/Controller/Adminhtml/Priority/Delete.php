<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Priority;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::priorities';
    
    protected $priorityRepository;

    public function __construct(
        Context $context,
        PriorityRepositoryInterface $priorityRepository
    ) {
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $priorityId = $this->getRequest()->getParam('id');
        
        if (!$priorityId) {
            $this->messageManager->addErrorMessage(__('Priority ID is required.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        }

        try {
            $priority = $this->priorityRepository->getById($priorityId);
            $this->priorityRepository->delete($priority);
            
            $this->messageManager->addSuccessMessage(__('Priority has been deleted successfully.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Priority not found.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('Unable to delete priority. Please try again.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting the priority.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        }
    }
}
