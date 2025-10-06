<?php

namespace EDU\HelloWorld\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;

class MassDelete extends Action
{
    protected $redirectFactory;
    protected $messageManager;
    protected $questionRepository;

    public function __construct(
        Context $context,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        QuestionRepositoryInterface $questionRepository
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->questionRepository = $questionRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        $questionIds = $this->getRequest()->getParam('question_ids');

        if (!$questionIds || !is_array($questionIds)) {
            $this->messageManager->addErrorMessage(__('Please select questions to delete.'));
            return $redirect->setPath('*/*/');
        }

        $deletedCount = 0;
        $errorCount = 0;

        foreach ($questionIds as $questionId) {
            try {
                $this->questionRepository->deleteById($questionId);
                $deletedCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        if ($deletedCount > 0) {
            $this->messageManager->addSuccessMessage(__('%1 question(s) have been deleted successfully.', $deletedCount));
        }

        if ($errorCount > 0) {
            $this->messageManager->addErrorMessage(__('%1 question(s) could not be deleted.', $errorCount));
        }

        return $redirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EDU_HelloWorld::question_manage');
    }
}
