<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
Loader::includeModule($module_id);

function getCurrencyList(String $api_key)
{
  $params = array(
    'get'  => 'currency_list',
    'key' => $api_key
  );
  $url = "https://currate.ru/api/";
  $requestUrl = $url."?".http_build_query($params);

  $request = curl_init();
  curl_setopt($request, CURLOPT_URL, $requestUrl);
  curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($request);
  curl_close($request);

  return json_decode($response, true);
}

$api_key = Option::get("currency.exchange", "api_key");
$currencyList = getCurrencyList($api_key);
$currencyArr = array_combine($currencyList["data"], $currencyList["data"]);

$aTabs = array(
  array(
    "DIV" => "edit",
    "TAB" => Loc::getMessage("CUREX_MODULE_OPTIONS_TAB_NAME"),
    "TITLE" => Loc::getMessage("CUREX_MODULE_OPTIONS_TAB_NAME"),
    "OPTIONS" => array(
      Loc::getMessage("CUREX_MODULE_OPTIONS_TAB_NAME"),
      array(
        "currency_pair",
        Loc::getMessage("CUREX_MODULE_OPTIONS_CURRENCY_PAIR"),
        "USDRUB",
        array("selectbox", $currencyArr)
      ),
      array(
        "api_key",
        Loc::getMessage("CUREX_MODULE_OPTIONS_API_KEY"),
        "fdcba72a8e1f388594c2c500c16dda9d",
        array("text", 30)
      )
    )
  )
);

$tabControl = new CAdminTabControl(
  "tabControl",
  $aTabs
);
$tabControl->Begin();

?>
<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
  <?
   foreach($aTabs as $aTab)
   {
     if($aTab["OPTIONS"])
     {
       $tabControl->BeginNextTab();
       __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
      }
   }
   $tabControl->Buttons();
  ?>
  <input type="submit" name="apply" value="<? echo(Loc::GetMessage("CUREX_MODULE_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save">
  <? echo(bitrix_sessid_post()); ?>
</form>

<?php $tabControl->End(); ?>

<?php
if ($request->isPost() && check_bitrix_sessid())
{
  foreach($aTabs as $aTab)
  {
    foreach($aTab["OPTIONS"] as $arOption)
    {
      if (!is_array($arOption))
      {
        continue;
      }
      if ($request["apply"])
      {
        $optionValue = $request->getPost($arOption[0]);
        Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
      }
    }
  }
  LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG);
}
?>
