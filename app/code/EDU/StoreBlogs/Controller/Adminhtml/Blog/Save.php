<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreBlogs\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\StoreBlogs\Api\Data\BlogInterfaceFactory;
use EDU\StoreBlogs\Api\BlogRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Blog Save Controller
 */
class Save extends Action
{
    /**
     * @var BlogInterfaceFactory
     */
    protected $blogFactory;

    /**
     * @var BlogRepositoryInterface
     */
    protected $blogRepository;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param BlogInterfaceFactory $blogFactory
     * @param BlogRepositoryInterface $blogRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        BlogInterfaceFactory $blogFactory,
        BlogRepositoryInterface $blogRepository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->blogFactory = $blogFactory;
        $this->blogRepository = $blogRepository;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('blog_id');
            
            // Filter out form_key and other non-blog fields
            $blogData = array_intersect_key($data, array_flip([
                'title', 'content', 'status', 'store_id'
            ]));

            if ($id) {
                try {
                    $blog = $this->blogRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog article no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $blog = $this->blogFactory->create();
            }

            // Set the data
            $blog->setTitle($blogData['title'] ?? '');
            $blog->setContent($blogData['content'] ?? '');
            $blog->setStatus($blogData['status'] ?? 'draft');
            $blog->setStoreId($blogData['store_id'] ?? 0);

            // Set published_at if status is published and not already set
            if ($blog->getStatus() === 'published' && !$blog->getPublishedAt()) {
                $blog->setPublishedAt(date('Y-m-d H:i:s'));
            }

            try {
                $savedBlog = $this->blogRepository->save($blog);
                $this->messageManager->addSuccessMessage(__('You saved the blog article.'));
                $this->dataPersistor->clear('storeblogs_blog');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['blog_id' => $savedBlog->getBlogId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blog article.'));
            }

            $this->dataPersistor->set('storeblogs_blog', $data);
            return $resultRedirect->setPath('*/*/edit', ['blog_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EDU_StoreBlogs::blogs');
    }
}
