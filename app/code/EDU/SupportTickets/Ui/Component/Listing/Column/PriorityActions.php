<?php
namespace EDU\SupportTickets\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class PriorityActions extends Column
{
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['priority_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl('supporttickets/priority/edit', ['id' => $item['priority_id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl('supporttickets/priority/delete', ['id' => $item['priority_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete priority'),
                            'message' => __('Are you sure you want to delete this priority?')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
