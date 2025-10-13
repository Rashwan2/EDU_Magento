<?php
namespace EDU\SupportTickets\Block\Adminhtml\Category\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Form extends Template
{
    protected $categoryRepository;
    protected $category;

    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }

    public function getCategory()
    {
        if (!$this->category) {
            $categoryId = $this->getRequest()->getParam('id');
            if ($categoryId) {
                try {
                    $this->category = $this->categoryRepository->getById($categoryId);
                } catch (NoSuchEntityException $e) {
                    $this->category = null;
                }
            } else {
                $this->category = null;
            }
        }
        return $this->category;
    }

    public function getFormAction()
    {
        return $this->getUrl('supporttickets/category/save');
    }

    public function getBackUrl()
    {
        return $this->getUrl('supporttickets/category/index');
    }
}
