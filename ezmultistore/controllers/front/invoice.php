<?php


class EzmultistoreInvoiceModuleFrontController extends ModuleFrontController
{

    public function setMedia()
    {
        parent::setMedia();


        // SA MARCHE PAS!
        $this->context->controller->registerJavascript(
            'jspdf',
            'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js',
            [
                'server' => 'remote',
                'position' => 'bottom',
                'priority' => 150]
        );

    }

    public function initContent() {
        parent::initContent();


        $this->context->smarty->assign([
           'id_order' => Tools::getValue('id_order'),
        ]);

        $this->setTemplate('module:ezmultistore/views/templates/front/invoice.tpl');
    }




}