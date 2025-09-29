<?php

namespace EDU\HelloWorld\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // This will be handled by db_schema.xml in Magento 2.3+
        // But we keep this for backward compatibility

        $installer->endSetup();
    }
}
