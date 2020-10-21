<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use qkkm\d7\OptionsTable;
use qkkm\d7\EventHandler;

Loc::loadMessages(__FILE__);

class qkkm_d7 extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'qkkm.d7';
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://QKkmServer.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\\EventHandler', 'OnSaleStatusOrder');
    }

    public function doUninstall()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->UnregisterEventHandler('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\\EventHandler', 'OnSaleStatusOrder');
        $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            OptionsTable::getEntity()->createDbTable();
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(OptionsTable::getTableName());
        }
    }

    function InstallEvents()
    {
        //RegisterModuleDependences('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\RegisterModuleDependences', 'OnSaleStatusOrder');
        //$eventManager = \Bitrix\Main\EventManager::getInstance();
        //$eventManager->registerEventHandler('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\\EventHandler', 'OnSaleStatusOrder');
        //RegisterModuleDependences('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\\EventHandler', 'OnSaleStatusOrder');
    }

    function UnInstallEvents()
    {
        //$eventManager = \Bitrix\Main\EventManager::getInstance();
        //$eventManager->UnregisterEventHandler('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\\EventHandler', 'OnSaleStatusOrder');
        //UnRegisterModuleDependences('sale', 'OnSaleStatusOrderChange', $this->MODULE_ID, '\\qkkm\\d7\\EventHandler', 'OnSaleStatusOrder');
    }
}
