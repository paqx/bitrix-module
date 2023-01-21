<?php
  if(!check_bitrix_sessid())
    return;
  IncludeModuleLangFile(__FILE__);

  if ($ex = $APPLICATION->GetException())
  	echo CAdminMessage::ShowMessage(Array(
  		"TYPE" => "ERROR",
  		"MESSAGE" => GetMessage("MOD_UNINST_ERR"),
  		"DETAILS" => $ex->GetString(),
  		"HTML" => true,
  	));
  else
  	echo CAdminMessage::ShowNote(GetMessage("MOD_UNINST_OK"));
?>
