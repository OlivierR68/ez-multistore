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
            || !$this->registerHook('displayAfterCarrier')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayOrderConfirmation')
            || !$this->registerHook('displayAdminOrder')
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
            || !$this->_installSql(true)
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

    private function _installSql($uninstall = false)
    {
        $file = 'install.php';
        if ($uninstall) $file = 'uninstall.php';

        include(dirname(__FILE__) . '/sql/' . $file);

        $result = true;
        foreach ($sql_requests as $request) {
            if (!empty($request)) {
                $result &= Db::getInstance()->execute($request);
            }
        }
        return true;
    }


    /**
     * Méthode pour ajout d'un transporteur
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
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

    /**
     * Méthode pour suppression d'un transporteur
     * @return bool
     */
    private function _deleteCarrier()
    {
        $carrier = new Carrier(Configuration::get('EZMULTISTORE_CARRIER_ID'));
        $carrier->delete();

        return true;
    }


    /**
     * Méthode pour ajouter une nouvelle addresse
     * @param $store
     * @param $customer
     * @return Address
     */
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


    /**
     * Hook pour le header des pages front-end
     * @param $params
     */
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


    /**
     * Hook pour ajouts des magasins dans le checkout des commandes
     * @param $params
     * @return string
     * @throws PrestaShopDatabaseException
     */
    public function hookDisplayAfterCarrier($params)
    {

        $carrier = new Carrier(Configuration::get('EZMULTISTORE_CARRIER_ID'));
        $id_lang = (int)$this->context->language->id;

        if ($carrier->active) {

            $stores = Store::getStores($id_lang);
            $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);

            foreach ($stores as $index => &$store) {
                $store['image'] = $imageRetriever->getImage(new Store($store['id_store']), $store['id_store']);
                if (is_array($store['image'])) {
                    $store['image']['legend'] = $store['image']['legend'][$this->context->language->id];
                }
            }

            $this->context->smarty->assign([
                'stores' => $stores,
                'ez_title' => $this->l('Select your store'),
            ]);

            return $this->display(__FILE__, 'displayAfterCarrier.tpl');

        }
    }


    /**
     * Hook pour la création d'adresse et l'ajout de commande spécifique
     * @param $params
     * @throws PrestaShopDatabaseException
     */
    public function hookDisplayOrderConfirmation($params)
    {
        $order = $params['order'];

        if ($order->id_carrier == Configuration::get('EZMULTISTORE_CARRIER_ID')) {

            $sql = new DbQuery();
            $sql->select('store_id')->from('ezmultistore_checkout')->where('customer_id = ' . $order->id_customer);
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
        $id_lang = $this->context->language->id;
        $order = new Order($params['id_order']);

        $sql = new DbQuery();
        $sql->select('store_id')->from('ezmultistore_order')->where('order_id = ' . $order->id);
        $store_id = Db::getInstance()->getValue($sql);

        if ($store_id) {

            $store = new Store($store_id);
            $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);
            $store_image = $imageRetriever->getImage(new Store($store_id), $store_id);
            $store->hours[$id_lang] = json_decode($store->hours[$id_lang]);

            $sql = new DbQuery();
            $sql->select('information')->from('ezmultistore_store_info')->where('store_id = ' . $store->id);
            $store_info = Db::getInstance()->getValue($sql);


            $this->context->smarty->assign([
                'panel_title' => $this->name . ' V' . $this->version,
                'store_image' => $store_image,
                'store' => $store,
                'store_info' => Tools::htmlentitiesDecodeUTF8($store_info),
                'state' => new State($store->id_state),
                'country' => new Country($store->id_country),
                'id_lang' => $id_lang,
            ]);
            return $this->display(__FILE__, 'displayAdminOrder2.tpl');
        }
    }


    public function getContent()
    {
        $id_lang = $this->context->language->id;
        $employees = Employee::getEmployees(true);
        $stores = Store::getStores($id_lang);

        // Récupération de la liste des magasins/employee dans la bdd
        $sql = new DbQuery();
        $sql->select('*')->from('ezmultistore_employees_stores');
        $employees_stores = EzMultiStore::generateEmployeesStoresList(Db::getInstance()->executeS($sql));

        // Récupération de information supplémentaire des magasins
        $sql = new DbQuery();
        $sql->select('*')->from('ezmultistore_store_info');
        $query_list = Db::getInstance()->executeS($sql);

        $stores_info_list = [];
        foreach ($query_list as $row) {
            $stores_info_list[$row['store_id']] = $row['information'];
        }


        $js = [
            $this->_path . 'views/js/admin.config.js',
        ];

        $this->context->controller->addJS($js);


        if (Tools::isSubmit('submitAuthorization')) {

            // Stockages de la liste des employees/magasin dans la bdd
            foreach ($employees as $employee) {
                $values = Tools::getValue('EMPLOYEE_' . $employee['id_employee'] . '_STORES');

                $sql = sprintf("REPLACE INTO %s(`employee_id`,`store_id_array`) VALUES(%s,'%s')",
                    _DB_PREFIX_ . 'ezmultistore_employees_stores',
                    $employee['id_employee'],
                    json_encode($values));

                Db::getInstance()->execute($sql);
            }

            header('Location: ' . $_SERVER['REQUEST_URI']);

        }

        if (Tools::isSubmit('submitRegistration')) {

            foreach ($stores as $store) {

                $store_info = Tools::getValue('INFO_STORE_' . $store['id_store']);

                $sql = sprintf("REPLACE INTO %s(`store_id`,`information`) VALUES(%s,'%s') ",
                    _DB_PREFIX_ . 'ezmultistore_store_info',
                    $store['id_store'],
                    Tools::htmlentitiesUTF8($store_info));

                Db::getInstance()->execute($sql);
            }


            header('Location: ' . $_SERVER['REQUEST_URI']);
        }

        $this->context->smarty->assign([
            'module_version' => 'V' . $this->version,
            'stores_link' => $this->context->link->getAdminLink('AdminStores'),
            'employees' => $employees,
            'employees_stores' => $employees_stores,
            'stores_info_list' => $stores_info_list,
            'stores' => $stores,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');

    }

    public static function generateEmployeesStoresList($query_list)
    {
        $result = [];
        foreach ($query_list as $row) {
            $stores_list = json_decode($row['store_id_array']);
            $stores_list = array_flip($stores_list);
            foreach ($stores_list as $key => $value) {
                $stores_list[$key] = true;
            }

            $result[$row['employee_id']] = $stores_list;
        }

        return $result;
    }


}