<?php

namespace EDU\HelloWorld\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use EDU\HelloWorld\Api\QuestionRepositoryInterface;
use EDU\HelloWorld\Api\AnswerRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\DataObject\IdentityInterface;

class QuestionAnswer extends Template implements IdentityInterface
{
    protected $questionRepository;
    protected $answerRepository;
    protected $registry;
    protected $customerSession;

    public function __construct(
        Context $context,
        QuestionRepositoryInterface $questionRepository,
        AnswerRepositoryInterface $answerRepository,
        Registry $registry,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getIdentities()
    {
        $identities = [];
        $product = $this->getCurrentProduct();
        if ($product) {
            $identities[] = 'product_question_' . $product->getId();
        }
        return $identities;
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function getProductId()
    {
        $product = $this->getCurrentProduct();
        return $product ? $product->getId() : null;
    }

    public function getQuestions()
    {
        $productId = $this->getProductId();
        if (!$productId) {
            return [];
        }

        try {
            return $this->questionRepository->getByProductId($productId, 'approved');
        } catch (\Exception $e) {
            $this->_logger->error('Error loading questions: ' . $e->getMessage());
            return [];
        }
    }

    public function getAnswersByQuestionId($questionId)
    {
        try {
            return $this->answerRepository->getByQuestionId($questionId);
        } catch (\Exception $e) {
            $this->_logger->error('Error loading answers: ' . $e->getMessage());
            return [];
        }
    }

    public function isCustomerLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomerName()
    {
        if ($this->isCustomerLoggedIn()) {
            return $this->customerSession->getCustomer()->getName();
        }
        return '';
    }

    public function getCustomerEmail()
    {
        if ($this->isCustomerLoggedIn()) {
            return $this->customerSession->getCustomer()->getEmail();
        }
        return '';
    }

    public function getQuestionSubmitUrl()
    {
        return $this->getUrl('helloworld/question/submit');
    }

    public function getAnswerSubmitUrl()
    {
        return $this->getUrl('helloworld/answer/submit');
    }

    public function getVoteUrl()
    {
        return $this->getUrl('helloworld/vote/submit');
    }

    public function formatQuestionDate($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::MEDIUM);
    }

    public function getQuestionCount()
    {
        return count($this->getQuestions());
    }

    public function hasQuestions()
    {
        return $this->getQuestionCount() > 0;
    }
}
