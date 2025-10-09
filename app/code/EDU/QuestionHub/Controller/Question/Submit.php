<?php

namespace EDU\QuestionHub\Controller\Question;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\QuestionHub\Model\QuestionFactory;
use EDU\QuestionHub\Api\QuestionRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\RequestInterface;

class Submit implements HttpPostActionInterface
{
    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var QuestionFactory
     */
    protected $questionFactory;
    /**
     * @var QuestionRepositoryInterface
     */
    protected $questionRepository;
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        QuestionFactory $questionFactory,
        QuestionRepositoryInterface $questionRepository,
        CustomerSession $customerSession,
        RequestInterface $request
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->questionFactory = $questionFactory;
        $this->questionRepository = $questionRepository;
        $this->customerSession = $customerSession;
        $this->request = $request;
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create();

        try {
            // Check if customer is logged in
            if (!$this->customerSession->isLoggedIn()) {
                $this->messageManager->addErrorMessage('Please log in to ask a question.');
                return $redirect->setPath('customer/account/login');
            }

            // Get form data
            $productId = $this->request->getParam('product_id');
            $questionText = $this->request->getParam('question_text');
            $customerName = $this->request->getParam('customer_name');
            $customerEmail = $this->request->getParam('customer_email');

            // Validate required fields
            if (empty($productId) || empty($questionText)) {
                $this->messageManager->addErrorMessage('Product ID and question text are required.');
                return $redirect->setPath('catalog/product/view', ['id' => $productId]);
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

            $this->messageManager->addSuccessMessage('Your question has been submitted and is pending approval.');
            return $redirect->setPath('catalog/product/view', ['id' => $productId]);

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $redirect->setPath('catalog/product/view', ['id' => $productId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('An error occurred while submitting your question.');
            return $redirect->setPath('catalog/product/view', ['id' => $productId]);
        }
    }
}
