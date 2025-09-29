<?php

namespace EDU\HelloWorld\Controller\Answer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use EDU\HelloWorld\Api\Data\AnswerInterfaceFactory;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;

class Submit extends Action implements HttpPostActionInterface
{
    protected $jsonFactory;
    protected $answerFactory;
    protected $answerRepository;
    protected $customerSession;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        AnswerInterfaceFactory $answerFactory,
        AnswerRepositoryInterface $answerRepository,
        CustomerSession $customerSession
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->answerFactory = $answerFactory;
        $this->answerRepository = $answerRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        
        try {
            // Check if customer is logged in
            if (!$this->customerSession->isLoggedIn()) {
                return $result->setData([
                    'success' => false,
                    'message' => 'Please log in to answer questions.'
                ]);
            }

            // Get form data
            $questionId = $this->getRequest()->getParam('question_id');
            $answerText = $this->getRequest()->getParam('answer_text');
            $customerName = $this->getRequest()->getParam('customer_name');

            // Validate required fields
            if (empty($questionId) || empty($answerText)) {
                return $result->setData([
                    'success' => false,
                    'message' => 'Question ID and answer text are required.'
                ]);
            }

            // Create answer
            $answer = $this->answerFactory->create();
            $answer->setQuestionId($questionId);
            $answer->setAnswerText($answerText);
            $answer->setCustomerName($customerName ?: $this->customerSession->getCustomer()->getName());
            $answer->setIsAdminAnswer(false);

            // Save answer
            $this->answerRepository->save($answer);

            return $result->setData([
                'success' => true,
                'message' => 'Your answer has been submitted successfully.',
                'answer_id' => $answer->getAnswerId()
            ]);

        } catch (LocalizedException $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => 'An error occurred while submitting your answer.'
            ]);
        }
    }
}
