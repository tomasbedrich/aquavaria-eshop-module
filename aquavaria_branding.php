<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Aquavaria_Branding extends Module
{

    const REMOTE_URL = 'http://localhost/aquavaria.cz/web/www/api';

    public function __construct()
    {
        $this->name = 'aquavaria_branding';
        $this->tab = 'front_office_features';
        $this->version = '0.0.1';
        $this->author = 'Tomáš Bedřich';

        $this->push_filename = _PS_CACHE_DIR_.'push/trends';
        $this->allow_push = true;

        parent::__construct();
        $this->displayName = $this->l('Aquavaria branding header and footer');
        $this->description = $this->l('Adds a header and footer from Aquavaria.cz website. DEVELOPERS USE ONLY - needs in-code config.');
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('brandHeader')
            && $this->registerHook('brandFooter')
        ;
    }

    public function hookBrandHeader()
    {
        return file_get_contents(self::REMOTE_URL . '/header');
    }

    public function hookBrandFooter()
    {
        return file_get_contents(self::REMOTE_URL . '/footer');
    }
}
