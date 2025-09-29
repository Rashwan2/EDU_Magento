<?php

namespace EDU\HelloWorld\Controller\Vote;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use EDU\HelloWorld\Model\VoteFactory;
use EDU\HelloWorld\Model\ResourceModel\Vote as VoteResource;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;

class Submit extends Action implements HttpPostActionInterface
{
    protected $jsonFactory;
    protected $voteFactory;
    protected $voteResource;
    protected $answerRepository;
    protected $customerSession;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        VoteFactory $voteFactory,
        VoteResource $voteResource,
        AnswerRepositoryInterface $answerRepository,
        CustomerSession $customerSession
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->voteFactory = $voteFactory;
        $this->voteResource = $voteResource;
        $this->answerRepository = $answerRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        
        try {
            // Get form data
            $answerId = $this->getRequest()->getParam('answer_id');
            $voteType = $this->getRequest()->getParam('vote_type');
            $customerEmail = $this->customerSession->isLoggedIn() 
                ? $this->customerSession->getCustomer()->getEmail() 
                : null;

            // Validate required fields
            if (empty($answerId) || empty($voteType)) {
                return $result->setData([
                    'success' => false,
                    'message' => 'Answer ID and vote type are required.'
                ]);
            }

            // Check if user already voted
            if ($this->voteResource->hasVoted($answerId, 'answer', $customerEmail, $this->getRequest()->getClientIp())) {
                return $result->setData([
                    'success' => false,
                    'message' => 'You have already voted on this answer.'
                ]);
            }

            // Create vote
            $vote = $this->voteFactory->create();
            $vote->setVoteableId($answerId);
            $vote->setVoteableType('answer');
            $vote->setCustomerEmail($customerEmail);
            $vote->setIpAddress($this->getRequest()->getClientIp());
            $vote->setVoteType($voteType);

            // Save vote
            $this->voteResource->save($vote);

            // Update helpful count
            if ($voteType === 'helpful') {
                $this->answerRepository->incrementHelpfulCount($answerId);
            }

            return $result->setData([
                'success' => true,
                'message' => 'Your vote has been recorded.',
                'vote_type' => $voteType
            ]);

        } catch (LocalizedException $e) {
            return $result->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'message' => 'An error occurred while recording your vote.'
            ]);
        }
    }
}
