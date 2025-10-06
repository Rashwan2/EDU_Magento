<?php

namespace EDU\HelloWorld\Controller\Vote;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use EDU\HelloWorld\Model\VoteFactory;
use EDU\HelloWorld\Model\ResourceModel\Vote as VoteResource;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;
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
     * @var VoteFactory
     */
    protected $voteFactory;
    /**
     * @var VoteResource
     */
    protected $voteResource;
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
        VoteFactory $voteFactory,
        VoteResource $voteResource,
        AnswerRepositoryInterface $answerRepository,
        QuestionRepositoryInterface $questionRepository,
        CustomerSession $customerSession,
        RequestInterface $request
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->voteFactory = $voteFactory;
        $this->voteResource = $voteResource;
        $this->answerRepository = $answerRepository;
        $this->questionRepository = $questionRepository;
        $this->customerSession = $customerSession;
        $this->request = $request;
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create();

        try {
            // Get form data
            $answerId = $this->request->getParam('answer_id');
            $voteType = $this->request->getParam('vote_type');
            $customerEmail = $this->customerSession->isLoggedIn()
                ? $this->customerSession->getCustomer()->getEmail()
                : null;

            // Validate required fields
            if (empty($answerId) || empty($voteType)) {
                $this->messageManager->addErrorMessage('Answer ID and vote type are required.');
                return $redirect->setRefererUrl();
            }

            // Check if user already voted
            if ($this->voteResource->hasVoted($answerId, 'answer', $customerEmail, $this->request->getClientIp())) {
                $this->messageManager->addErrorMessage('You have already voted on this answer.');
                return $redirect->setRefererUrl();
            }

            // Get answer to find product ID
            $answer = $this->answerRepository->getById($answerId);
            $question = $this->questionRepository->getById($answer->getQuestionId());
            $productId = $question->getProductId();

            // Create vote
            $vote = $this->voteFactory->create();
            $vote->setVoteableId($answerId);
            $vote->setVoteableType('answer');
            $vote->setCustomerEmail($customerEmail);
            $vote->setIpAddress($this->request->getClientIp());
            $vote->setVoteType($voteType);

            // Save vote
            $this->voteResource->save($vote);

            // Update helpful count
            if ($voteType === 'helpful') {
                $this->answerRepository->incrementHelpfulCount($answerId);
            }

            $this->messageManager->addSuccessMessage('Your vote has been recorded.');
            return $redirect->setPath('catalog/product/view', ['id' => $productId]);

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $redirect->setRefererUrl();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('An error occurred while recording your vote.');
            return $redirect->setRefererUrl();
        }
    }
}
