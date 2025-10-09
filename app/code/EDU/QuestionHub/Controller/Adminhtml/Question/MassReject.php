<?php

namespace EDU\QuestionHub\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\QuestionHub\Api\QuestionRepositoryInterface;

class MassReject extends Action
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
            $this->messageManager->addErrorMessage(__('Please select questions to reject.'));
            return $redirect->setPath('*/*/');
        }

        $rejectedCount = 0;
        $errorCount = 0;

        foreach ($questionIds as $questionId) {
            try {
                $question = $this->questionRepository->getById($questionId);
                $question->setStatus('rejected');
                $this->questionRepository->save($question);
                $rejectedCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        if ($rejectedCount > 0) {
            $this->messageManager->addSuccessMessage(__('%1 question(s) have been rejected successfully.', $rejectedCount));
        }

        if ($errorCount > 0) {
            $this->messageManager->addErrorMessage(__('%1 question(s) could not be rejected.', $errorCount));
        }

        return $redirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EDU_QuestionHub::question_manage');
    }
}
