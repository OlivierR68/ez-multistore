<?php

/**
 * @property Order $object
 */
class AdminPickupOrdersController extends ModuleAdminController
{
    public $toolbar_title;
    protected $statuses_array = array();

    public function __construct()
    {

        $this->bootstrap = true;
        $this->table = 'order';
        $this->className = 'Order';
        $this->lang = false;
        $this->addRowAction('view');
        $this->explicitSelect = true;
        $this->allow_export = true;
        $this->deleted = false;

        parent::__construct();

        $this->_select = '
		a.id_currency,
		a.id_order AS id_pdf,
		CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
		osl.`name` AS `osname`,
		os.`color`,
		IF((SELECT so.id_order FROM `' . _DB_PREFIX_ . 'orders` so WHERE so.id_customer = a.id_customer AND so.id_order < a.id_order LIMIT 1) > 0, 0, 1) as new,
		country_lang.name as cname,
		IF(a.valid, 1, 0) badge_success,
		store_lang.name AS store_name,
		CONCAT(store.`postcode`, \' \',store.`city`) AS `location`
		';

        $this->_join = '
		LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.`id_customer` = a.`id_customer`)
		INNER JOIN `' . _DB_PREFIX_ . 'address` address ON address.id_address = a.id_address_delivery
		INNER JOIN `' . _DB_PREFIX_ . 'country` country ON address.id_country = country.id_country
		INNER JOIN `' . _DB_PREFIX_ . 'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = ' . (int) $this->context->language->id . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'order_state` os ON (os.`id_order_state` = a.`current_state`)
		LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = ' . (int) $this->context->language->id . ')
		INNER JOIN `' . _DB_PREFIX_ . 'ezmultistore_order` ez_mo ON ez_mo.order_id = a.id_order
		INNER JOIN `' . _DB_PREFIX_ . 'store` store ON store.id_store = ez_mo.store_id
		INNER JOIN `' . _DB_PREFIX_ . 'store_lang` store_lang ON (store.`id_store` = store_lang.`id_store` AND store_lang.`id_lang` = ' . (int) $this->context->language->id . ')
		';

        $sql = new DbQuery();
        $sql->select('*')->from('ezmultistore_employees_stores');
        $employees_stores = EzMultiStore::generateEmployeesStoresList(Db::getInstance()->executeS($sql));

        $query_options = '';
        $index = 0;
        foreach ($employees_stores[$this->context->employee->id] as $shop_id => $value) {
            $query_options .= $shop_id;
            $index++;

            if ($index < count($employees_stores[$this->context->employee->id]))
                $query_options .= ',';
        }
        $this->_where = '
        AND store.id_store IN ('.$query_options.')
        ';


        $this->_orderBy = 'id_order';
        $this->_orderWay = 'DESC';
        $this->_use_found_rows = true;

        $this->fields_list = [
            'id_order' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
            ),
            'reference' => array(
                'title' => $this->l('Reference'),
            ),
            'store_name' => array(
                'title' => $this->l('Store'),
                'align' => 'text-left',
            ),
            'location' => array(
                'title' => $this->l('Location'),
            ),
            'customer' => array(
                'title' => $this->l('Customer'),
                'havingFilter' => true,
            ),
            'total_paid_tax_incl' => [
                'title' => $this->l('Total'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true,
            ],
            'payment' => array(
                'title' => $this->l('Payment'),
            ),
            'osname' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'color' => 'color',
                'list' => $this->statuses_array,
                'filter_key' => 'os!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'osname',
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'align' => 'text-right',
                'type' => 'datetime',
                'filter_key' => 'a!date_add',
            ),

        ];
    }

    public static function setOrderCurrency($echo, $tr)
    {
        if (!empty($tr['id_currency'])) {
            $idCurrency = (int) $tr['id_currency'];
        } else {
            $order = new Order($tr['id_order']);
            $idCurrency = (int) $order->id_currency;
        }

        return Tools::displayPrice($echo, $idCurrency);
    }

    public function initToolbar()
    {
        if ($this->display == 'view') {
            $id_order = Tools::getValue('id_order');
            $order = new Order($id_order);
            if (Validate::isLoadedObject($order)) {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders') . '&vieworder&id_order=' . (int)$id_order);
            }
        }
        return parent::initToolbar();
    }

    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if (method_exists('Context', 'getTranslator')) {
            $this->translator = Context::getContext()->getTranslator();
            $translated = $this->translator->trans($string);

            if ($translated !== $string) {
                return $translated;
            }
        }
        if ($class === null || $class == 'AdminTab') {
            $class = Tools::substr(get_class($this), 0, -10);
        } elseif (Tools::strtolower(Tools::substr($class, -10)) == 'controller') {
            $class = Tools::substr($class, 0, -10);
        }
        return Translate::getAdminTranslation($string, $class, $addslashes, $htmlentities);
    }


}