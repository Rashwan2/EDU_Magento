<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Priority;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use EDU\SupportTickets\Api\Data\PriorityInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::priorities';
    
    protected $priorityRepository;
    protected $priorityFactory;

    public function __construct(
        Context $context,
        PriorityRepositoryInterface $priorityRepository,
        PriorityInterfaceFactory $priorityFactory
    ) {
        $this->priorityRepository = $priorityRepository;
        $this->priorityFactory = $priorityFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if (!$this->getRequest()->isPost()) {
            return $resultRedirect->setPath('supporttickets/priority/index');
        }

        try {
            $data = $this->getRequest()->getPostValue();
            $priorityId = $data['priority_id'] ?? null;
            
            if ($priorityId) {
                $priority = $this->priorityRepository->getById($priorityId);
            } else {
                $priority = $this->priorityFactory->create();
            }
            
            $priority->setName($data['name']);
            $priority->setColor($data['color']);
            $priority->setSortOrder($data['sort_order']);
            $priority->setIsActive($data['is_active']);
            
            $this->priorityRepository->save($priority);
            
            $this->messageManager->addSuccessMessage(__('Priority has been saved successfully.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Priority not found.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Unable to save priority. Please try again.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the priority.'));
            return $resultRedirect->setPath('supporttickets/priority/index');
        }
    }
}
