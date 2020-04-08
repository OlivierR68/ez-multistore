<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class EzMultiStore extends Module
{
    public function __construct()
    {
        $this->name = 'ezmultistore';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'webolive Studio';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('EZ MultiStore : Store management');
        $this->description = $this->l('This module enables the option of having different invoice numbers and prefixs by each multi store. In some countries this feature is totally necessary because the invoice numbers must be consecutive, never shared between different stores');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');


    }

    public function install() {

        if (!parent::install()
            || !$this->_newCarrier()
        ){
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    private function _newCarrier()
    {
        $id_lang = $id_lang = $this->context->language->id;

        // Tableau pour tester, à remplacer par une table bdd
        $stores = [
            'Colmar' => [
                'name' => 'Colmar : pick up in store',
                'delay' => '2 hours',
          ],
            'Mulhouse' => [
                'name' => 'Mulhouse : pick up in store',
                'delay' => '2 hours',
            ],
            'Strasbourg' => [
                'name' => 'Strasbourg : pick up in store',
                'delay' => '2 hours',
            ]
        ];

        foreach ($stores as $store) {
            $carrier = new Carrier();

            $carrier->name = $store['name'];
            $carrier->delay[$id_lang] = $store['delay'];
            $carrier->is_free = true;

            $carrier->save();
        }

        return true;
    }
}