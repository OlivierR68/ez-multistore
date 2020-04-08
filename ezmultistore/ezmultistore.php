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
        $this->description = $this->l('This module enables the option of having different invoices for each stores.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall ?');


    }

    public function install()
    {
        if (!parent::install()
            || !$this->_newCarrier()
            || !$this->_installTab('AdminParentOrders','AdminPickupOrders', $this->l('Pickup Orders'))
        ) {
            return false;
        }
        return true;
    }

    public function uninstall() {
        if (!parent::uninstall()
            || !$this->_uninstallTab('AdminPickupOrders')

        ){
            return false;
        }
        return true;
    }

    private function _installTab($parent, $class_name, $name) {
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->class_name = $class_name;
        $tab->module = $this->name;

        $tab->name = [];
        foreach(Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        return $tab->save();
    }


    private function _uninstallTab($class_name) {
        $id_tab = Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);

        return $tab->delete();

    }

    private function _newCarrier()
    {
        $id_lang = $id_lang = $this->context->language->id;

        if (Configuration::get('EZ_MULTISTORE_CARRIER_INSTALLED') !== 1) {

            $carrier = new Carrier();
            $carrier->name = $this->l('Pick up in store');
            $carrier->delay[$id_lang] = $this->l('2 Hours');
            $carrier->is_free = true;

            $carrier->save();
            Configuration::updateValue('EZ_MULTISTORE_CARRIER_INSTALLED', 1);

        }

        return true;
    }


    public function getContent()
    {

        // Chargement fichiers JS et CSS nécessaires
        $js = [
            $this->_path . 'views/js/ezmultistore.js'
        ];

        $css = [
            $this->_path . 'views/css/firstmodule.css'
        ];

        $this->context->controller->addJS($js);
        $this->context->controller->addCSS($css);

    }
}