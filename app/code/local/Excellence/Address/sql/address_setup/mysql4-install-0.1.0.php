<?php

$installer = $this;

$installer->startSetup();

$setup = $this;

$entityTypeId = $setup->getEntityTypeId('customer_address');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

//this is for creating a new attribute for customer address entity
$setup->addAttribute("customer_address", "house_number", array(
    "type" => "varchar",
    "backend" => "",
    "label" => "House Number",
    "input" => "text",
    "source" => "",
    "visible" => true,
    "required" => true,
    "default" => "",
    "frontend" => "",
    "unique" => false,
    "note" => "This Attribute Will Be Used Show House Number Field In Checkout Page"
));

$setup->addAttribute("customer_address", "po_box", array(
    "type" => "varchar",
    "backend" => "",
    "label" => "PO Box",
    "input" => "text",
    "source" => "",
    "visible" => true,
    "required" => false,
    "default" => "",
    "frontend" => "",
    "unique" => false,
    "note" => "This Attribute Will Be Used Show PO Box Field In Checkout Page"
));

$attribute1 = Mage::getSingleton("eav/config")->getAttribute("customer_address", "house_number");

$setup->addAttributeToGroup(
        $entityTypeId, $attributeSetId, $attributeGroupId, 'house_number', '998'  //sort_order
);

$used_in_forms = array();

$used_in_forms[]="adminhtml_customer";
$used_in_forms[]="checkout_register";
$used_in_forms[]="customer_account_create";
$used_in_forms[]="customer_register_address";
$used_in_forms[]="customer_address_edit"; //this form code is used in checkout billing/shipping address
$used_in_forms[]="adminhtml_checkout";
$used_in_forms[]="adminhtml_customer_address";
$attribute1->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 98)
;
$attribute1->save();

$attribute2 = Mage::getSingleton("eav/config")->getAttribute("customer_address", "po_box");

$setup->addAttributeToGroup(
        $entityTypeId, $attributeSetId, $attributeGroupId, 'po_box', '999'  //sort_order
);

$attribute2->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 99)
;
$attribute2->save();

/**
 * Adding Extra Column to sales_flat_quote_address
 * to store the delivery instruction field
 */
$sales_quote_address = $installer->getTable('sales/quote_address');
$installer->getConnection()
        ->addColumn($sales_quote_address, 'house_number', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'New House Number Field Added'
        ));
$installer->getConnection()
        ->addColumn($sales_quote_address, 'po_box', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'New PO Box Field Added'
        ));
/**
 * Adding Extra Column to sales_flat_order_address
 * to store the delivery instruction field
 */
$sales_order_address = $installer->getTable('sales/order_address');
$installer->getConnection()
        ->addColumn($sales_order_address, 'house_number', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'New House Number Field Added'
        ));
$installer->getConnection()
        ->addColumn($sales_order_address, 'po_box', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'New PO Box Field Added'
        ));


$config = Mage::getModel('core/config');

//append delivery instruction to address templates in system configuration
$html = Mage::getConfig()->getNode('default/customer/address_templates/html');
$html .= '{{depend house_number}}<br/>House Number:{{var house_number}} {{/depend}}';
$html .= '{{depend po_box}}<br/>PO Box:{{var po_box}} {{/depend}}';
$config->saveConfig('customer/address_templates/html', $html);

$text = Mage::getConfig()->getNode('default/customer/address_templates/text');
$text .= '{{depend house_number}}House Number:{{var house_number}} {{/depend}}';
$text .= '{{depend po_box}}PO Box:{{var po_box}} {{/depend}}';
$config->saveConfig('customer/address_templates/text', $text);

$oneline = Mage::getConfig()->getNode('default/customer/address_templates/oneline');
$oneline .= '{{depend house_number}}House Number:{{var house_number}} {{/depend}}';
$oneline .= '{{depend po_box}}PO Box:{{var po_box}} {{/depend}}';
$config->saveConfig('customer/address_templates/oneline', $oneline);

$pdf = Mage::getConfig()->getNode('default/customer/address_templates/pdf');
$pdf .= '{{depend house_number}}<br/>House Number:{{var house_number}} {{/depend}}';
$pdf .= '{{depend po_box}}<br/>PO Box:{{var po_box}} {{/depend}}';
$config->saveConfig('customer/address_templates/pdf', $pdf);

$js_template = Mage::getConfig()->getNode('default/customer/address_templates/js_template');
$js_template .= '{{depend house_number}}<br/>House Number:{{var house_number}} {{/depend}}';
$js_template .= '{{depend po_box}}<br/>PO Box:{{var po_box}} {{/depend}}';
$config->saveConfig('customer/address_templates/js_template', $js_template);
$installer->endSetup();
