<?php
/** @var $APPLICATION CAllMain */

$module_id = "maxposter.api";
if ($APPLICATION->GetGroupRight($module_id) < "R") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
CModule::IncludeModule($module_id);
IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");

// сброс настроек
if (
    ($REQUEST_METHOD == "GET")
    && ($APPLICATION->GetGroupRight($module_id) == "W")
    && ($RestoreDefaults == 'Y')
) {
    COption::RemoveOption($module_id);
}

// TODO: поддержать мультисайтовость, см. socialservices
$arDisplayOptions = array(
    array('MAX_API_LOGIN', GetMessage('MAX_API_LOGIN'), array('text', 20)),
    array('MAX_API_PASSWORD', GetMessage('MAX_API_PASSWORD'), array('text', 40)),
    array('MAX_UPLOAD_PATH', GetMessage('MAX_UPLOAD_PATH'), array('text', 40)),
);

//
if ($REQUEST_METHOD == "POST" && strlen($Update) > 0 && $APPLICATION->GetGroupRight($module_id) == "W" && check_bitrix_sessid()) {
    $oldLogin = COption::GetOptionString($module_id, 'MAX_API_LOGIN');
    while(list($key,$name) = each($arDisplayOptions)) {
        $val = $$name[0];
        if($name[2][0]=="checkbox" && $val != "Y") {
            $val="N";
        } elseif(!array_key_exists($name[0], $_POST)) {
            continue;
        }

        if ($name[0] == 'MAX_API_LOGIN') {
            $newLogin = $val;
        }

        COption::SetOptionString($module_id, $name[0], $val);
    }
    // Очистить кеш при смене логина
    if ($oldLogin != $newLogin) {
        ;
    }
    unset($oldLogin, $newLogin);
}

// Tabs
$aTabs = array(
    0 => array(
        "DIV"   => "edit1",
        "TAB"   => GetMessage("MAIN_TAB_SET"),
        "ICON"  => "main_settings",
        "TITLE" => GetMessage("MAIN_TAB_TITLE_SET"),
    ),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?><form method="post" action="<?php
    echo $APPLICATION->GetCurPage()
    ?>?mid=<?php echo htmlspecialchars($mid)
    ?>&lang=<?php echo LANGUAGE_ID ?>">
    <?php echo bitrix_sessid_post() ?>
    <?php echo $tabControl->BeginNextTab() ?>
    <?php
        foreach($arDisplayOptions as $Option):
            $val = COption::GetOptionString($module_id, $Option[0]);
            $type = $Option[2];
            ?>
            <tr>
                <td valign="top" width="30%"><?php
                    if($type[0] == "checkbox") {
                        echo "<label for=\"".htmlspecialchars($Option[0])."\">".$Option[1]."</label>";
                    } else {
                        echo $Option[1];
                    } ?></td>
                <td valign="top" width="70%"><?
                    if($type[0]=="checkbox"):
                        ?><input type="checkbox" name="<?echo htmlspecialchars($Option[0])?>" id="<?echo htmlspecialchars($Option[0])?>" value="Y"<?if($val=="Y")echo" checked";?>><?
                    elseif($type[0]=="text"):
                        ?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialchars($val)?>" name="<?echo htmlspecialchars($Option[0])?>"><?
                    elseif($type[0]=="textarea"):
                        ?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialchars($Option[0])?>"><?echo htmlspecialchars($val)?></textarea><?
                    endif;
                    ?></td>
            </tr>
            <?php
        endforeach;
    ?>

    <?php $tabControl->Buttons() ?>
    <script language="JavaScript">
        function RestoreDefaults() {
            if(confirm('<?php echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING")) ?>'))
                window.location = "<?php
                    echo $APPLICATION->GetCurPage()
                    ?>?RestoreDefaults=Y&lang=<?php echo LANGUAGE_ID
                    ?>&mid=<?php echo urlencode($mid) ?>";
        }
    </script>
    <input type="submit" name="Update" value="<?php echo GetMessage("MAIN_SAVE") ?>">
    <input type="hidden" name="Update" value="Y">
    <input type="reset" name="reset" value="<?php echo GetMessage("MAIN_RESET") ?>">
    <input type="button" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="RestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
    <?php $tabControl->End() ?>
</form>
