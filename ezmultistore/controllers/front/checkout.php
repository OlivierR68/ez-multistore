<?php

/**
 * Class EzmultistoreCheckoutModuleFrontController
 *
 * Ajax processes:
 *  - test
 */

class EzmultistoreCheckoutModuleFrontController extends ModuleFrontController
{
    public function initContent() {
        parent::initContent();
      $this->setTemplate('module:ezmultistore/views/templates/front/checkout.tpl');
    }

    public function displayAjaxGetUserStore()
    {
        $user = Tools::getValue('user_id');

        $sql = 'SELECT `store_id` FROM '._DB_PREFIX_.'ezmultistore_checkout WHERE `customer_id` = '.$user;
        $return = Db::getInstance()->getValue($sql);

        $this->ajaxDie(json_encode($return));
    }

    public function displayAjaxSetUserStore()
    {
        $user = Tools::getValue('user_id');
        $store = Tools::getValue('store_id');

        $sql = 'REPLACE INTO '._DB_PREFIX_.'ezmultistore_checkout SET `customer_id` = '.$user.', `store_id` = '.$store;
        Db::getInstance()->execute($sql);

        $this->ajaxDie(json_encode(true));
    }
}