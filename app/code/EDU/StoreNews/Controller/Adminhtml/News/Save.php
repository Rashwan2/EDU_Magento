<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace EDU\StoreNews\Controller\Adminhtml\News;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EDU\StoreNews\Api\Data\NewsInterfaceFactory;
use EDU\StoreNews\Api\NewsRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * News Save Controller
 */
class Save extends Action
{
    /**
     * @var NewsInterfaceFactory
     */
    protected $newsFactory;

    /**
     * @var NewsRepositoryInterface
     */
    protected $newsRepository;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param NewsInterfaceFactory $newsFactory
     * @param NewsRepositoryInterface $newsRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        NewsInterfaceFactory $newsFactory,
        NewsRepositoryInterface $newsRepository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
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
            $id = $this->getRequest()->getParam('news_id');
            
            // Filter out form_key and other non-news fields
            $newsData = array_intersect_key($data, array_flip([
                'title', 'content', 'status', 'store_id'
            ]));

            if ($id) {
                try {
                    $news = $this->newsRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This news article no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $news = $this->newsFactory->create();
            }

            // Set the data
            $news->setTitle($newsData['title'] ?? '');
            $news->setContent($newsData['content'] ?? '');
            $news->setStatus($newsData['status'] ?? 'draft');
            $news->setStoreId($newsData['store_id'] ?? 0);

            // Set published_at if status is published and not already set
            if ($news->getStatus() === 'published' && !$news->getPublishedAt()) {
                $news->setPublishedAt(date('Y-m-d H:i:s'));
            }

            try {
                $savedNews = $this->newsRepository->save($news);
                $this->messageManager->addSuccessMessage(__('You saved the news article.'));
                $this->dataPersistor->clear('storenews_news');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['news_id' => $savedNews->getNewsId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the news article.'));
            }

            $this->dataPersistor->set('storenews_news', $data);
            return $resultRedirect->setPath('*/*/edit', ['news_id' => $id]);
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
        return $this->_authorization->isAllowed('EDU_StoreNews::news');
    }
}
