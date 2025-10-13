<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::categories';
    
    protected $categoryRepository;

    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $categoryId = $this->getRequest()->getParam('id');
        
        if (!$categoryId) {
            $this->messageManager->addErrorMessage(__('Category ID is required.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        }

        try {
            $category = $this->categoryRepository->getById($categoryId);
            $this->categoryRepository->delete($category);
            
            $this->messageManager->addSuccessMessage(__('Category has been deleted successfully.'));
            return $resultRedirect->setPath('supporttickets/category/index');
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Category not found.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('Unable to delete category. Please try again.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting the category.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        }
    }
}
