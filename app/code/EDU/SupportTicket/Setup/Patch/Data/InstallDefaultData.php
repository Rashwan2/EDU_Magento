<?php
namespace EDU\SupportTicket\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallDefaultData implements DataPatchInterface, PatchVersionInterface
{
    private $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        
        // Insert default categories
        $categories = [
            ['name' => 'General Inquiry', 'description' => 'General questions and inquiries', 'is_active' => 1, 'sort_order' => 1],
            ['name' => 'Technical Support', 'description' => 'Technical issues and problems', 'is_active' => 1, 'sort_order' => 2],
            ['name' => 'Billing', 'description' => 'Billing and payment related issues', 'is_active' => 1, 'sort_order' => 3],
            ['name' => 'Order Support', 'description' => 'Order related questions and issues', 'is_active' => 1, 'sort_order' => 4],
            ['name' => 'Feature Request', 'description' => 'Requests for new features', 'is_active' => 1, 'sort_order' => 5],
        ];

        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('edu_support_ticket_category'),
            $categories
        );

        // Insert default priorities
        $priorities = [
            ['name' => 'Low', 'color' => '#28a745', 'sort_order' => 1, 'is_active' => 1],
            ['name' => 'Normal', 'color' => '#007bff', 'sort_order' => 2, 'is_active' => 1],
            ['name' => 'High', 'color' => '#ffc107', 'sort_order' => 3, 'is_active' => 1],
            ['name' => 'Urgent', 'color' => '#dc3545', 'sort_order' => 4, 'is_active' => 1],
        ];

        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('edu_support_ticket_priority'),
            $priorities
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public static function getVersion()
    {
        return '1.0.0';
    }

    public function getAliases()
    {
        return [];
    }
}

