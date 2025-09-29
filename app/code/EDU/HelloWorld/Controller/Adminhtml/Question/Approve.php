<?php

namespace EDU\HelloWorld\Controller\Adminhtml\Question;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class Approve extends Action
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
        $questionId = $this->getRequest()->getParam('question_id');

        if (!$questionId) {
            $this->messageManager->addErrorMessage(__('Question ID is required.'));
            return $redirect->setPath('*/*/');
        }

        try {
            $question = $this->questionRepository->getById($questionId);
            $question->setStatus('approved');
            $this->questionRepository->save($question);

            $this->messageManager->addSuccessMessage(__('Question has been approved successfully.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while approving the question.'));
        }

        return $redirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EDU_HelloWorld::question_manage');
    }
}
