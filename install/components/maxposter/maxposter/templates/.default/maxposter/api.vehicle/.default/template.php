<?php
/* @class CBitrixComponentTemplate */
/**
  0 => string 'arResult' (length=8)
  1 => string 'arParams' (length=8)
  2 => string 'parentTemplateFolder' (length=20)
  3 => string 'APPLICATION' (length=11)
  4 => string 'USER' (length=4)
  5 => string 'DB' (length=2)
  6 => string 'templateName' (length=12)
  7 => string 'templateFile' (length=12)
  8 => string 'templateFolder' (length=14)
  9 => string 'componentPath' (length=13)
  10 => string 'component' (length=9)
  11 => string 'templateData' (length=12)
**/
#var_dump(array_keys(get_defined_vars()));
?>
<div class="catalog_element">
    <?php if ($arResult['PHOTOS']) : ?>
        <div class="images_block">
            <?php $firstPhoto = array_shift($arResult['PHOTOS']); ?>
            <a
                href="/upload/maxposter/<?php echo $arResult['DEALER_ID'], '/', $arResult['ID'], '/original/', $firstPhoto; ?>"
                class="big_img"
                style="height:191px; width:255px; background:url('/upload/maxposter/<?php echo $arResult['DEALER_ID'], '/', $arResult['ID'], '/big/', $firstPhoto; ?>') top left no-repeat;">&nbsp;</a>
            <?php if ($arResult['PHOTOS']) : ?>
                <?php array_unshift($arResult['PHOTOS'], $firstPhoto) ?>
                <?php foreach ($arResult['PHOTOS'] as $photo) : ?>
                    <a href="javascript:void(0)"
                        rel="/upload/maxposter/<?php echo $arResult['DEALER_ID'], '/', $arResult['ID'], '/big/', $photo; ?>"
                        name="/upload/maxposter/<?php echo $arResult['DEALER_ID'], '/', $arResult['ID'], '/original/', $photo; ?>"
                        class="small_img"
                        style="width:75px; height:56px; background:url('/upload/maxposter/<?php echo $arResult['DEALER_ID'], '/', $arResult['ID'], '/small/', $photo; ?>') top left no-repeat;">&nbsp;</a>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    <?php else : ?>
        <div class="images_block">
            <img src="/bitrix/images/maxposter/no_photo.gif" width="120" height="90" />
        </div>
    <?php endif ?>

    <div class="element_params">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?php if (array_key_exists('PRICE_OLD', $arResult)) : ?>
                <tr><td><?php echo GetMessage('MAX_OLD_PRICE') ?></td><td><span class="element_old_price"><?php echo maxPrice($arResult['PRICE_OLD'], $arResult['PRICE_UNIT']) ?></span></td></tr>
            <?php endif ?>
            <tr><td><?php echo GetMessage('MAX_PRICE') ?></td><td><span class="element_price"><?php echo maxPrice($arResult['PRICE'], $arResult['PRICE_UNIT']) ?></span></td></tr>
            <?php if ('Y' == $arParams['SHOW_MARK_MODEL']) : ?>
                <tr><td><?php echo GetMessage('MAX_MARK') ?></td><td><?php echo $arResult['MARK_NAME'] ?></td></tr>
                <tr><td><?php echo GetMessage('MAX_MODEL') ?></td><td><?php echo $arResult['MODEL_NAME'] ?></td></tr>
            <?php endif ?>
            <tr><td><?php echo GetMessage('MAX_YEAR') ?></td><td><?php echo $arResult['YEAR'] ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_BODY_TYPE') ?></td><td><?php echo $arResult['BODY_TYPE'] ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_ENGINE_TYPE') ?></td><td><?php echo $arResult['ENGINE_TYPE'] ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_ENGINE_VOLUME') ?></td><td><?php echo $arResult['ENGINE_VOLUME'] ?>&nbsp;סל&#179;<?php echo ($arResult['ENGINE_POWER'] ? sprintf('&nbsp;(%s&nbsp;כ.ס.)', $arResult['ENGINE_POWER']) : '') ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_DISTANCE') ?></td><td><?php echo number_format($arResult['DISTANCE'], '0', '.', ' '), ' ', ('km' == $arResult['DISTANCE_UNIT'] ? 'ךל' : 'לטכü'); ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_DRIVE_TYPE') ?></td><td><?php echo $arResult['DRIVE_TYPE'] ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_GEARBOX_TYPE') ?></td><td><?php echo $arResult['GEARBOX_TYPE'] ?></td></tr>
            <tr><td><?php echo GetMessage('MAX_WHEEL_PLACE') ?></td><td><?php echo $arResult['WHEEL_PLACE'] ?></td></tr>
            <?php if ('Y' == $arParams['SHOW_CONDITION']) : ?>
                <tr><td><?php echo GetMessage('MAX_CONDITION') ?></td><td><?php echo $arResult['CONDITION'] ?></td></tr>
            <?php endif ?>
            <?php if ('Y' == $arParams['SHOW_COLOR']) : ?>
                <tr><td><?php echo GetMessage('MAX_BODY_COLOR') ?></td><td><?php echo $arResult['BODY_COLOR'] ?></td></tr>
            <?php endif ?>
            <?php if ('Y' == $arParams['SHOW_CUSTOMS']) : ?>
                <tr><td><?php echo GetMessage('MAX_PRICE_NO_CUSTOMS') ?></td><td><?php
                    echo ($arResult['PRICE_NO_CUSTOMS'] ? GetMessage('MAX_PRICE_NO_CUSTOMS_FALSE') : GetMessage('MAX_PRICE_NO_CUSTOMS_TRUE'));
                ?></td></tr>
            <?php endif ?>
            <?php if ('Y' == $arParams['SHOW_AVAILABILITY']) : ?>
                <tr><td><?php echo GetMessage('MAX_AVAILABILITY') ?></td><td><?php echo $arResult['AVAILABILITY'] ?></td></tr>
            <?php endif ?>
            <?php if ('Y' == $arParams['SHOW_PTS']) : ?>
                <tr><td><?php echo GetMessage('MAX_PTS_OWNERS') ?></td><td><?php echo $arResult['PTS_OWNERS'] ?></td></tr>
            <?php endif ?>
        </table>
    </div>

    <div class="element_description">
        <?php if ($arResult['OPTIONS']) : ?>
        <h2><?php echo GetMessage('MAX_OPTIONS_TITLE') ?></h2>
        <ul>
            <?php foreach ($arResult['OPTIONS'] as $name => $option) : ?>
                <li><?php echo GetMessage('MAX_' . $name), (true === $option ? '' : ': ' . $option); ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <?php if ('Y' == $arParams['SHOW_DESCRIPTION'] && !empty($arResult['DESCRIPTION'])) : ?>
            <h2><?php echo GetMessage('MAX_DESCRIPTION') ?></h2>
            <p><?php echo $arResult['DESCRIPTION'] ?></p>
        <?php endif ?>
        <?php if ('Y' == $arParams['SHOW_INSPECTION'] && !empty($arResult['INSPECTION'])) : ?>
            <h3><?php echo GetMessage('MAX_INSPECTION') ?></h3>
            <p><?php echo $arResult['INSPECTION'] ?></p>
        <?php endif ?>
    </div>

    <?php if (('Y' == $arParams['SHOW_CONTACTS']) && (bool) $arResult['PHONES']) : ?>
        <div class="element_description">
            <h2><?php echo GetMessage('MAX_CONTACTS') ?></h2>
            <p><?php echo implode(';<br />', $arResult['PHONES']) ?></p>
        </div>
    <?php endif ?>
</div>