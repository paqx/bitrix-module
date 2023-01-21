<?php

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

Class currency_exchange extends CModule
{
  var $MODULE_ID = "currency.exchange";
  var $MODULE_VERSION;
  var $MODULE_VERSION_DATE;
  var $MODULE_NAME;
  var $MODULE_DESCRIPTION;
  var $MODULE_CSS;

  function __construct()
  {
    $arModuleVersion = array();

    include(__DIR__.'/version.php');

    if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

    $this->MODULE_NAME = Loc::getMessage("CUREX_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("CUREX_MODULE_DESCRIPTION");
  }

  function InstallDB()
	{
    RegisterModule("currency.exchange");
  }

  function UnInstallDB()
	{
    UnRegisterModule("currency.exchange");
  }

  function InstallFiles()
  {
    CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/currency.exchange/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
    return true;
  }

  function UnInstallFiles()
  {
    DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/currency.exchange/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components");
    return true;
  }

  function DoInstall()
  {
    global $DOCUMENT_ROOT, $APPLICATION;
    $this->InstallDB();
    $this->InstallFiles();
    $APPLICATION->IncludeAdminFile(Loc::getMessage("CUREX_MODULE_INSTALLING"), $DOCUMENT_ROOT."/local/modules/currency.exchange/install/step.php");
  }

  function DoUninstall()
  {
    global $DOCUMENT_ROOT, $APPLICATION;
    $this->UnInstallDB();
    $this->UnInstallFiles();
    $APPLICATION->IncludeAdminFile(Loc::getMessage("CUREX_MODULE_UNINSTALLING"), $DOCUMENT_ROOT."/local/modules/currency.exchange/install/unstep.php");
  }
}
?>
