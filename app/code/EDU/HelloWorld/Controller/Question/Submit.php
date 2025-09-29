<?php

namespace EDU\HelloWorld\Controller\Question;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;
use EDU\HelloWorld\Api\Data\QuestionInterfaceFactory;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;

class Submit extends Action implements HttpPostActionInterface
{
    protected $jsonFactory;
    protected $questionFactory;
    protected $questionRepository;
    protected $customerSession;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        QuestionInterfaceFactory $questionFactory,
        QuestionRepositoryInterface $questionRepository,
        CustomerSession $customerSession
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->questionFactory = $questionFactory;
        $this->questionRepository = $questionRepository;
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
                    'message' => 'Please log in to ask a question.'
                ]);
            }

            // Get form data
            $productId = $this->getRequest()->getParam('product_id');
            $questionText = $this->getRequest()->getParam('question_text');
            $customerName = $this->getRequest()->getParam('customer_name');
            $customerEmail = $this->getRequest()->getParam('customer_email');

            // Validate required fields
            if (empty($productId) || empty($questionText)) {
                return $result->setData([
                    'success' => false,
                    'message' => 'Product ID and question text are required.'
                ]);
            }

            // Create question
            $question = $this->questionFactory->create();
            $question->setProductId($productId);
            $question->setQuestionText($questionText);
            $question->setCustomerName($customerName ?: $this->customerSession->getCustomer()->getName());
            $question->setCustomerEmail($customerEmail ?: $this->customerSession->getCustomer()->getEmail());
            $question->setStatus('pending');

            // Save question
            $this->questionRepository->save($question);

            return $result->setData([
                'success' => true,
                'message' => 'Your question has been submitted and is pending approval.',
                'question_id' => $question->getQuestionId()
            ]);

        } catch (LocalizedException $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => 'An error occurred while submitting your question.'
            ]);
        }
    }
}
