<?php

use Bitrix\Main\Config\Option;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$currency_pair = Option::get("currency.exchange", "currency_pair");
$api_key = Option::get("currency.exchange", "api_key");

$params = array(
  'get'  => 'rates',
  'pairs' => $currency_pair,
  'key' => $api_key
);
$url = "https://currate.ru/api/";
$requestUrl = $url."?".http_build_query($params);

$request = curl_init();
curl_setopt($request, CURLOPT_URL, $requestUrl);
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($request);
curl_close($request);
$response_arr = json_decode($response, true);
$currency_rate = $response_arr['data'];

$arResult['CURRENCY_PAIR'] = $currency_pair;
$arResult['CURRENCY_RATE'] = $currency_rate[$currency_pair];
$this->IncludeComponentTemplate();

?>
