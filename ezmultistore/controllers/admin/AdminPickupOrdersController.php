<?php


class AdminPickupOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->lang = false;
        parent::__construct();
    }

    /**
    * Method Translation Override For PS 1.7
    */
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


