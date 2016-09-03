<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Aquavaria_Branding extends Module
{
    const REMOTE_URL = 'https://aquavaria.cz/api';
    const CACHE_LIFETIME = 60 * 60 * 12;  // 12 hours (in seconds)

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

    public function getCachedAPI($endpoint)
    {
        $url = self::REMOTE_URL.$endpoint;
        $cache_file = __DIR__.'/cache/'.$endpoint;

        // source: http://stackoverflow.com/a/5263017/570503
        if (file_exists($cache_file) && (filemtime($cache_file) > (time() - self::CACHE_LIFETIME))) {
            // Cache file is less than CACHE_LIFETIME old.
            // Don't bother refreshing, just use the file as-is.
            $response = file_get_contents($cache_file);
        } else {
            // Our cache is out-of-date, so load the data from our remote server,
            // and also save it over our cache for next time.
            $response = file_get_contents($url);
            file_put_contents($cache_file, $response, LOCK_EX);
        }

        return $response;
    }

    public function hookBrandHeader()
    {
        return $this->getCachedAPI('/header');
    }

    public function hookBrandFooter()
    {
        return $this->getCachedAPI('/footer');
    }
}
