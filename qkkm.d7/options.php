<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\HtmlFilter;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'qkkm.d7');

if (! $USER->isAdmin()) {
    $APPLICATION->authForm('Nope');
}

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages($context->getServer()->getDocumentRoot()."/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

$tabControl = new CAdminTabControl("tabControl", [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
    ],
]);

if ((! empty($save) || ! empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    if (! empty($restore)) {
        Option::delete(ADMIN_MODULE_NAME);
        CAdminMessage::showMessage([
            "MESSAGE" => Loc::getMessage("REFERENCES_OPTIONS_RESTORED"),
            "TYPE" => "OK",
        ]);
    } elseif (! empty($request->getPost('secret_key')) && (! empty($request->getPost('cash_register_id'))) && (! empty($request->getPost('order_code'))) && (! empty($request->getPost('host')))) {
        Option::set(ADMIN_MODULE_NAME, "secret_key", $request->getPost('secret_key'));
        Option::set(ADMIN_MODULE_NAME, "cash_register_id", $request->getPost('cash_register_id'));
        Option::set(ADMIN_MODULE_NAME, "order_code", $request->getPost('order_code'));
        Option::set(ADMIN_MODULE_NAME, "host", $request->getPost('host'));
        CAdminMessage::showMessage([
            "MESSAGE" => Loc::getMessage("REFERENCES_OPTIONS_SAVED"),
            "TYPE" => "OK",
        ]);
    } else {
        CAdminMessage::showMessage(Loc::getMessage("REFERENCES_INVALID_VALUE"));
    }
}

$tabControl->begin();
?>

<form method="post"
      action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->beginNextTab();
    ?>
    <tr>
        <td width="40%">
            <label for="host"><?=Loc::getMessage("REFERENCES_HOST")?>:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   name="host"
                   value="<?=HtmlFilter::encode(Option::get(ADMIN_MODULE_NAME, "host", "https://api.qkkmserver.ru"));?>"
            />
        </td>
    </tr>

    <tr>
        <td width="40%">
            <label for="secret_key"><?=Loc::getMessage("REFERENCES_SECRET_KEY")?>:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   name="secret_key"
                   value="<?=HtmlFilter::encode(Option::get(ADMIN_MODULE_NAME, "secret_key", 0));?>"
            />
        </td>
    </tr>

    <tr>
        <td width="40%">
            <label for="cash_register_id"><?=Loc::getMessage("REFERENCES_CASH_REGISTER_ID")?>:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   maxlength="5"
                   name="cash_register_id"
                   value="<?=HtmlFilter::encode(Option::get(ADMIN_MODULE_NAME, "cash_register_id", 0));?>"
            />
        </td>
    </tr>

    <tr>
        <td width="40%">
            <label for="order_code"><?=Loc::getMessage("REFERENCES_ORDER_CODE")?>:</label>
        <td width="60%">
            <input type="text"
                   size="50"
                   name="order_code"
                   value="<?=HtmlFilter::encode(Option::get(ADMIN_MODULE_NAME, "order_code", 0));?>"
            />
        </td>
    </tr>

    <?php
    $tabControl->buttons();
    ?>
    <input type="submit"
           name="save"
           value="<?=Loc::getMessage("MAIN_SAVE")?>"
           title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>"
           class="adm-btn-save"
    />
    <input type="submit"
           name="restore"
           title="<?=Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>"
           onclick="return confirm('<?=AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')"
           value="<?=Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>"
    />
    <?php
    $tabControl->end();
    ?>
</form>
