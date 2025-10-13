<?php
namespace EDU\SupportTickets\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\SupportTickets\Api\CategoryRepositoryInterface;
use EDU\SupportTickets\Api\Data\CategoryInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    const ADMIN_RESOURCE = 'EDU_SupportTickets::categories';
    
    protected $categoryRepository;
    protected $categoryFactory;

    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository,
        CategoryInterfaceFactory $categoryFactory
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if (!$this->getRequest()->isPost()) {
            return $resultRedirect->setPath('supporttickets/category/index');
        }

        try {
            $data = $this->getRequest()->getPostValue();
            $categoryId = $data['category_id'] ?? null;
            
            if ($categoryId) {
                $category = $this->categoryRepository->getById($categoryId);
            } else {
                $category = $this->categoryFactory->create();
            }
            
            $category->setName($data['name']);
            $category->setDescription($data['description']);
            $category->setIsActive($data['is_active']);
            $category->setSortOrder($data['sort_order']);
            
            $this->categoryRepository->save($category);
            
            $this->messageManager->addSuccessMessage(__('Category has been saved successfully.'));
            return $resultRedirect->setPath('supporttickets/category/index');
            
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('Category not found.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Unable to save category. Please try again.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the category.'));
            return $resultRedirect->setPath('supporttickets/category/index');
        }
    }
}
