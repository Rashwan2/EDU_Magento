<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::categories';
    
    protected $pageFactory;
    protected $categoryRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('id');
        
        try {
            if ($categoryId) {
                $category = $this->categoryRepository->getById($categoryId);
                $title = __('Edit Category: %1', $category->getName());
            } else {
                $category = null;
                $title = __('New Category');
            }
            
            $resultPage = $this->pageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend($title);
            
            return $resultPage;
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Category not found.'));
            return $this->_redirect('supporttickets/category/index');
        }
    }
}
