<?php

/**
 * @property Order $object
 */
class AdminPickupOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'order';
        $this->className = 'Order';
        $this->lang = false;
        $this->bootstrap = true;
        $this->deleted = false;
        $this->explicitSelect = true;
        $this->context = Context::getContext();

        parent::__construct();

    }

    public function initToolbar()
    {
        if ($this->display == 'view') {
            $id_order = Tools::getValue('id_order');
            $order = new Order($id_order);
            if (Validate::isLoadedObject($order)) {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders').'&vieworder&id_order='.(int)$id_order);
            }
        }
        return parent::initToolbar();
    }
}