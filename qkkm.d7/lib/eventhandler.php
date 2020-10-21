<?php

namespace qkkm\d7;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

\CModule::IncludeModule('main');
\CModule::IncludeModule('sale');

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale;
use Bitrix\Sale\Order;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Delivery;
use Bitrix\Sale\PaySystem;
use Bitrix\Main\Entity;

use Bitrix\Main\Main\Event;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Config\Option;

class EventHandler
{

    public function OnSaleStatusOrder(\Bitrix\Main\Event $event)
    {
        $module_id = "qkkm.d7";
        \CModule::IncludeModule($module_id);

        $qkkm_code = Option::get($module_id, "order_code");
        $parameters = $event->getParameters();
        $order = $parameters['ENTITY'];
        $qkkm_data = [];

        if (! $order instanceof \Bitrix\Sale\Order) {
            return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::ERROR, new \Bitrix\Sale\ResultError('Неверный объект заказа', 'SALE_EVENT_WRONG_ORDER'), 'sale');
        }

        $qkkm_data['ORDER_ID'] = $order->getId();
        $qkkm_data['STATUS_ID'] = $order->getField('STATUS_ID');

        if($qkkm_data['STATUS_ID'] == $qkkm_code) {
            Debug::writeToFile($qkkm_data, "", "upload/qkkm.txt");
        }
        
    }
}