<?php


class AdminCustomPdfController extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true;
        $this->lang = false;

        parent::__construct();

    }


}