<?php
namespace EDU\SupportTickets\Block\Adminhtml\Priority\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use EDU\SupportTickets\Api\PriorityRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Form extends Template
{
    protected $priorityRepository;
    protected $priority;

    public function __construct(
        Context $context,
        PriorityRepositoryInterface $priorityRepository,
        array $data = []
    ) {
        $this->priorityRepository = $priorityRepository;
        parent::__construct($context, $data);
    }

    public function getPriority()
    {
        if (!$this->priority) {
            $priorityId = $this->getRequest()->getParam('id');
            if ($priorityId) {
                try {
                    $this->priority = $this->priorityRepository->getById($priorityId);
                } catch (NoSuchEntityException $e) {
                    $this->priority = null;
                }
            } else {
                $this->priority = null;
            }
        }
        return $this->priority;
    }

    public function getFormAction()
    {
        return $this->getUrl('supporttickets/priority/save');
    }

    public function getBackUrl()
    {
        return $this->getUrl('supporttickets/priority/index');
    }
}
