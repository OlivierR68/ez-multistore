<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class EzMultiStore extends Module
{
    public function __construct()
    {
        $this->name = 'ezmultistore';
        $this->tab = 'shipping_logistics';
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
            || !$this->_installTab('AdminParentOrders', 'AdminPickupOrders', $this->l('Pickup Orders'))
            || !$this->registerHook('displayCarrierExtraContent')
            || !$this->registerHook('displayAfterCarrier')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->_uninstallTab('AdminPickupOrders')

        ) {
            return false;
        }
        return true;
    }

    private function _installTab($parent, $class_name, $name)
    {
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->class_name = $class_name;
        $tab->module = $this->name;

        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        return $tab->save();
    }


    private function _uninstallTab($class_name)
    {
        $id_tab = Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);

        return $tab->delete();

    }


    public function hookDisplayCarrierExtraContent($params) {

        return $this->display(__FILE__, 'displayAfterCarrier.tpl');
    }

    public function hookDisplayAfterCarrier($params) {

        return $this->display(__FILE__, 'displayAfterCarrier.tpl');
    }


    // Création d'une fonction qui créé des tables non active pour l'instant
    private function _installSql() {

        // On récupère notre install.php
        include(dirname(__FILE__).'/sql/install.php');

        // On créé une variable $result à true
        $result = true;
        foreach($sql_requests as $request) {
            if (!empty($request)) {
                $result &= Db::getInstance()->execute($request);
            }
        }
        return true;

    }

    private function _newCarrier()
    {

        if (Configuration::get('EZMULTISTORE_CARRIER_INSTALLED') == null) {

            $id_lang = (int)$this->context->language->id;

            $carrier = new Carrier();
            $carrier->name = $this->l('Pick up in store');
            $carrier->delay[$id_lang] = $this->l('Order ready in 2 hours.');
            $carrier->is_free = true;

            /*
            $carrier->is_module = false;
            $carrier->shipping_external = true;
            $carrier->external_module_name = $this->name;
            */

            if ($carrier->add()) {

                // Ajout des groupes d'utilisateur
                $groups_list = [];
                $groups = Group::getGroups($id_lang);
                foreach ($groups as $group) {
                    $groups_list[] = $group['id_group'];
                }
                $carrier->setGroups($groups_list);

                // Ajout des zones
                $zones = Zone::getZones();
                foreach ($zones as $zone) {
                    $carrier->addZone($zone['id_zone']);
                }

            }


            if (!@copy(dirname(__FILE__) . '/views/img/ezmultistore.jpg',
                _PS_SHIP_IMG_DIR_ . '/' . (int)$carrier->id . '.jpg')) {
                return false;
            }


            Configuration::updateValue('EZMULTISTORE_CARRIER_INSTALLED', 1);

        }

        return true;
    }


    public function getContent()
    {

        // Chargement fichiers JS et CSS nécessaires
        $js = [
            $this->_path . 'views/js/ezmultistore.js',
        ];

        $css = [
            $this->_path . 'views/css/firstmodule.css',
        ];

        $this->context->controller->addJS($js);
        $this->context->controller->addCSS($css);

    }
}