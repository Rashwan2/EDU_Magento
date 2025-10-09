<?php

namespace EDU\QuestionHub\Controller\Answer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\QuestionHub\Model\AnswerFactory;
use EDU\QuestionHub\Api\AnswerRepositoryInterface;
use EDU\QuestionHub\Api\QuestionRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;

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
     * @var AnswerFactory
     */
    protected $answerFactory;
    /**
     * @var AnswerRepositoryInterface
     */
    protected $answerRepository;
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
        AnswerFactory $answerFactory,
        AnswerRepositoryInterface $answerRepository,
        QuestionRepositoryInterface $questionRepository,
        CustomerSession $customerSession,
        RequestInterface $request
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->answerFactory = $answerFactory;
        $this->answerRepository = $answerRepository;
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
                $this->messageManager->addErrorMessage('Please log in to answer questions.');
                return $redirect->setPath('customer/account/login');
            }

            // Get form data
            $questionId = $this->request->getParam('question_id');
            $answerText = $this->request->getParam('answer_text');
            $customerName = $this->request->getParam('customer_name');

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
