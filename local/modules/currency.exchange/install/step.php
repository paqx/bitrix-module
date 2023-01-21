<?php
  if(!check_bitrix_sessid())
    return;
  IncludeModuleLangFile(__FILE__);

  if ($ex = $APPLICATION->GetException())
  	echo CAdminMessage::ShowMessage(Array(
  		"TYPE" => "ERROR",
  		"MESSAGE" => GetMessage("MOD_INST_ERR"),
  		"DETAILS" => $ex->GetString(),
  		"HTML" => true,
  	));
  else
  	echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
?>
