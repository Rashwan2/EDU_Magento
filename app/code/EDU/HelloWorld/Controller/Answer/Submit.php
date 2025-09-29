<?php

namespace EDU\HelloWorld\Controller\Answer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\HelloWorld\Model\AnswerFactory;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;

class Submit extends Action implements HttpPostActionInterface
{
    protected $redirectFactory;
    protected $messageManager;
    protected $answerFactory;
    protected $answerRepository;
    protected $questionRepository;
    protected $customerSession;

    public function __construct(
        Context $context,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        AnswerFactory $answerFactory,
        AnswerRepositoryInterface $answerRepository,
        QuestionRepositoryInterface $questionRepository,
        CustomerSession $customerSession
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->answerFactory = $answerFactory;
        $this->answerRepository = $answerRepository;
        $this->questionRepository = $questionRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        
        try {
            // Check if customer is logged in
            if (!$this->customerSession->isLoggedIn()) {
                $this->messageManager->addErrorMessage('Please log in to answer questions.');
                return $redirect->setPath('customer/account/login');
            }

            // Get form data
            $questionId = $this->getRequest()->getParam('question_id');
            $answerText = $this->getRequest()->getParam('answer_text');
            $customerName = $this->getRequest()->getParam('customer_name');

            // Validate required fields
            if (empty($questionId) || empty($answerText)) {
                $this->messageManager->addErrorMessage('Question ID and answer text are required.');
                return $redirect->setRefererUrl();
            }

            // Get question to find product ID
            $question = $this->questionRepository->getById($questionId);
            $productId = $question->getProductId();

            // Create answer
            $answer = $this->answerFactory->create();
            $answer->setQuestionId($questionId);
            $answer->setAnswerText($answerText);
            $answer->setCustomerName($customerName ?: $this->customerSession->getCustomer()->getName());
            $answer->setIsAdminAnswer(false);

            // Save answer
            $this->answerRepository->save($answer);

            $this->messageManager->addSuccessMessage('Your answer has been submitted successfully.');
            return $redirect->setPath('catalog/product/view', ['id' => $productId]);

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $redirect->setRefererUrl();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('An error occurred while submitting your answer.');
            return $redirect->setRefererUrl();
        }
    }
}
