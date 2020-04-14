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
}