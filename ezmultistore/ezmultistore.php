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
            || !$this->_installTab('AdminParentOrders', 'AdminPickupOrders', $this->l('Pickup Store'))
            || !$this->registerHook('displayAfterCarrier')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayOrderConfirmation')
            || !$this->registerHook('displayAdminOrder')
            || !$this->registerHook('actionPDFInvoiceRender')
            || !$this->_installSql()
        ) return false;

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->_uninstallTab('AdminPickupOrders')
            || !$this->_deleteCarrier()
            || !Configuration::deleteByName('EZMULTISTORE_CARRIER_ID')
            || !$this->_uninstallSql()
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

    private function _installSql()
    {
        include(dirname(__FILE__) . '/sql/install.php');
        $result = true;
        foreach ($sql_requests as $request) {
            if (!empty($request)) {
                $result &= Db::getInstance()->execute($request);
            }
        }
        return true;
    }

    private function _uninstallSql()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');
        $result = true;
        foreach ($sql_requests as $request) {
            if (!empty($request)) {
                $result &= Db::getInstance()->execute($request);
            }
        }
        return true;
    }

    private function _newCarrier()
    {
        $id_lang = (int)$this->context->language->id;

        $carrier = new Carrier();
        $carrier->name = $this->l('Pick up in store');
        $carrier->delay[$id_lang] = $this->l('Order ready in 2 hours.');
        $carrier->is_free = true;

        $carrier->is_module = false;
        $carrier->shipping_external = true;
        $carrier->external_module_name = $this->name;

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

        Configuration::updateValue('EZMULTISTORE_CARRIER_ID', $carrier->id);

        return true;
    }

    private function _deleteCarrier()
    {
        $carrier = new Carrier(Configuration::get('EZMULTISTORE_CARRIER_ID'));
        $carrier->delete();

        return true;
    }


    private function _newPickupAddress($store, $customer)
    {
        $address = new Address();
        $id_lang = $this->context->language->id;

        $address->alias = $this->l('PickUp : ') . $store->name[$id_lang];
        $address->company = $store->name[$id_lang];
        $address->lastname = $customer->lastname;
        $address->firstname = $customer->firstname;

        $address->address1 = $store->address1[$id_lang];
        $address->address2 = $store->address2[$id_lang];

        foreach (['id_country', 'postcode', 'city', 'phone'] as $attr)
            $address->$attr = $store->$attr;

        $address->add();

        return $address;
    }

    public function hookDisplayHeader($params)
    {

        $js = [
            $this->_path . 'views/js/front.checkout.js',
        ];

        $css = [
            $this->_path . 'views/css/ezmultistore.css',
        ];

        $this->context->controller->addJS($js);
        $this->context->controller->addCSS($css);

        Media::addJsDef([
            'carrierId' => Configuration::get('EZMULTISTORE_CARRIER_ID'),
            'moduleCheckOutUrl' => $this->context->link->getModuleLink('ezmultistore', 'checkout'),
            'baseUrl' => $this->context->link->getBaseLink(),
            'userId' => $this->context->customer->id
        ]);

    }


    public function hookDisplayAfterCarrier($params)
    {

        $carrier = new Carrier(Configuration::get('EZMULTISTORE_CARRIER_ID'));
        $id_lang = (int)$this->context->language->id;

        if ($carrier->active) {

            $stores = Store::getStores($id_lang);
            $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);

            foreach ($stores as $index => &$store) {
                if ($store['active'] == false) {
                    unset($stores[$index]);
                } else {
                    $store['image'] = $imageRetriever->getImage(new Store($store['id_store']), $store['id_store']);
                    if (is_array($store['image'])) {
                        $store['image']['legend'] = $store['image']['legend'][$this->context->language->id];
                    }
                }
            }

            $this->context->smarty->assign([
                'stores' => $stores,
                'ez_title' => $this->l('Select your store'),
            ]);

            return $this->display(__FILE__, 'displayAfterCarrier.tpl');

        }
    }

    public function hookDisplayOrderConfirmation($params)
    {
        $order = $params['order'];

        if ($order->id_carrier == Configuration::get('EZMULTISTORE_CARRIER_ID')) {

            $sql = 'SELECT `store_id` FROM ' . _DB_PREFIX_ . 'ezmultistore_checkout WHERE `customer_id` = ' . $order->id_customer;
            $store_id = Db::getInstance()->getValue($sql);
            $store = new Store($store_id);

            $pickupAddress = $this->_newPickupAddress($store, new Customer($order->id_customer));
            $order->id_address_delivery = $pickupAddress->id;
            $order->save();


            Db::getInstance()->insert('ezmultistore_order', [
                'order_id' => $order->id,
                'store_id' => $store->id,
                'customer_id' => $order->id_customer,
                'address_id' => $pickupAddress->id,
            ]);

        }
    }

    public function hookDisplayAdminOrder($params)
    {
        $order = new Order($params['id_order']);

        $sql = 'SELECT `store_id` FROM ' . _DB_PREFIX_ . 'ezmultistore_order WHERE `order_id` = ' . $order->id;
        $store_id = Db::getInstance()->getValue($sql);
        $store = new Store($store_id);

        $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);
        $store_image = $imageRetriever->getImage(new Store($store_id), $store_id);


        $this->context->smarty->assign([
            'panel_title' => $this->name . ' V' . $this->version,
            'store_image' => $store_image,
            'store' => $store,
            'state' => new State($store->id_state),
            'country' => new Country($store->id_country),
            'id_lang' => $this->context->language->id,
        ]);
        return $this->display(__FILE__, 'displayAdminOrder2.tpl');
    }


    public function getContent()
    {


    }
}