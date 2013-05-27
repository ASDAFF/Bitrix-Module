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

?>

<?php if ($arResult['VEHICLES']) : ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="catalog_section_table">
        <tr>
            <th>&nbsp;</th>
            <th><a href="<?php
                    echo (($isActive = ('name' == $arParams['ORDER_BY'])) && ('asc' == $arParams['ORDER_DIRECTION']))
                        ? $arResult['URL']['ORDER_BY_NAME_DESC']
                        : $arResult['URL']['ORDER_BY_NAME_ASC']
                ?>">Марка</a>&nbsp;
                <a href="<?php echo $arResult['URL']['ORDER_BY_NAME_ASC'] ?>" class="sort_up"><img
                        src="/bitrix/templates/passage_inner/images/sort_up<?php echo $isActive && 'asc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
                <a href="<?php echo $arResult['URL']['ORDER_BY_NAME_DESC'] ?>" class="sort_down"><img
                        src="/bitrix/templates/passage_inner/images/sort_down<?php echo $isActive && 'desc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
            </th>
            <th><a href="<?php
                    echo (($isActive = ('name' == $arParams['ORDER_BY'])) && ('asc' == $arParams['ORDER_DIRECTION']))
                        ? $arResult['URL']['ORDER_BY_NAME_DESC']
                        : $arResult['URL']['ORDER_BY_NAME_ASC']
                ?>">Модель&nbsp;
                <a href="<?php echo $arResult['URL']['ORDER_BY_NAME_ASC'] ?>" class="sort_up"><img
                        src="/bitrix/templates/passage_inner/images/sort_up<?php echo $isActive && 'asc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
                <a href="<?php echo $arResult['URL']['ORDER_BY_NAME_DESC'] ?>" class="sort_down"><img
                        src="/bitrix/templates/passage_inner/images/sort_down<?php echo $isActive && 'desc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
            </th>
            <th><a href="<?php
                    echo (($isActive = ('year' == $arParams['ORDER_BY'])) && ('desc' == $arParams['ORDER_DIRECTION']))
                        ? $arResult['URL']['ORDER_BY_YEAR_ASC']
                        : $arResult['URL']['ORDER_BY_YEAR_DESC']
                ?>">Год выпуска&nbsp;
                <a href="<?php echo $arResult['URL']['ORDER_BY_YEAR_ASC'] ?>" class="sort_up"><img
                        src="/bitrix/templates/passage_inner/images/sort_up<?php echo $isActive && 'asc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
                <a href="<?php echo $arResult['URL']['ORDER_BY_YEAR_DESC'] ?>" class="sort_down"><img
                        src="/bitrix/templates/passage_inner/images/sort_down<?php echo $isActive && 'desc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
            </th>
            <th>Объём двигателя</th>
            <th><a href="<?php
                    echo (($isActive = ('distance' == $arParams['ORDER_BY'])) && ('asc' == $arParams['ORDER_DIRECTION']))
                        ? $arResult['URL']['ORDER_BY_DISTANCE_DESC']
                        : $arResult['URL']['ORDER_BY_DISTANCE_ASC']
                ?>">Пробег&nbsp;
                <a href="<?php echo $arResult['URL']['ORDER_BY_DISTANCE_ASC'] ?>" class="sort_up"><img
                        src="/bitrix/templates/passage_inner/images/sort_up<?php echo $isActive && 'asc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
                <a href="<?php echo $arResult['URL']['ORDER_BY_DISTANCE_DESC'] ?>" class="sort_down"><img
                        src="/bitrix/templates/passage_inner/images/sort_down<?php echo $isActive && 'desc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
            </th>
            <th><a href="<?php
                    echo (($isActive = ('price' == $arParams['ORDER_BY'])) && ('asc' == $arParams['ORDER_DIRECTION']))
                        ? $arResult['URL']['ORDER_BY_PRICE_DESC']
                        : $arResult['URL']['ORDER_BY_PRICE_ASC']
                ?>">Цена&nbsp;
                <a href="<?php echo $arResult['URL']['ORDER_BY_PRICE_ASC'] ?>" class="sort_up"><img
                        src="/bitrix/templates/passage_inner/images/sort_up<?php echo $isActive && 'asc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
                <a href="<?php echo $arResult['URL']['ORDER_BY_PRICE_DESC'] ?>" class="sort_down"><img
                        src="/bitrix/templates/passage_inner/images/sort_down<?php echo $isActive && 'desc' == $arParams['ORDER_DIRECTION'] ? '_akt' : '' ?>.png"
                        alt="" border="0" /></a>
            </th>
        </tr>
        <?php foreach ($arResult['VEHICLES'] as $position => $arVehicle) : ?>
            <tr class="<?php echo (false === $position % 2 ? 'even_line' : '') ?>">
                <td><?php if ($arVehicle['PHOTO']) : ?><a
                        href="<?php echo $arVehicle['URL_TO_VEHICLE'] ?>"><img
                        src="/upload/maxposter/<?php
                            echo $arVehicle['DEALER_ID'], '/', $arVehicle['VEHICLE_ID'], '/medium/', $arVehicle['PHOTO'];
                        ?>"
                        alt="<?php
                            echo htmlspecialchars($arVehicle['MARK']['NAME']), ' ', htmlspecialchars($arVehicle['MODEL_NAME']);
                        ?>"
                    /></a><?php else : ?><img src="/bitrix/images/maxposter/no_photo.gif" width="100" height="75" /><?php endif ?></td>
                <td><a href="<?php echo $arVehicle['URL_TO_VEHICLE'] ?>"><?php echo $arVehicle['MARK']['NAME'] ?></a></td>
                <td><a href="<?php echo $arVehicle['URL_TO_VEHICLE'] ?>"><?php
                    echo ((18 < mb_strlen($arVehicle['MODEL']['NAME']) && $pos = mb_strpos($arVehicle['MODEL']['NAME'], '(', 5)) ? trim(mb_substr($arVehicle['MODEL']['NAME'], 0, $pos)) : $arVehicle['MODEL']['NAME'])
                ?></a></td>
                <td><?php echo $arVehicle['YEAR'] ?></td>
                <td><?php echo $arVehicle['ENGINE_VOLUME'] ?>&nbsp;см&#179;</td>
                <td><?php echo number_format($arVehicle['DISTANCE'], '0', '.', ' '), ' ', ('km' == $arVehicle['DISTANCE_UNIT'] ? 'км' : 'миль'); ?></td>
                <td><?php echo maxPrice($arVehicle['PRICE'], $arVehicle['PRICE_UNIT']) ?></td>
            </tr>
        <?php endforeach ?>
    </table>

    <br />

    <?php if ($arResult['PAGER'] && (1 < $arResult['PAGER']['TOTAL'])) : ?>
        <div class="page_navigation">
            <?php if (1 < $arResult['PAGER']['CURRENT']) : ?>
                <a href="<?php echo $arResult['PAGER']['PAGES']['NUM'][$arResult['PAGER']['CURRENT']-1] ?>" class="pn_prev">&nbsp;</a>
            <?php endif ?>
            <?php foreach ($arResult['PAGER']['PAGES']['NUM'] as $num => $url) : ?>
                <?php if ($arResult['PAGER']['CURRENT'] == $num) : ?>
                    <a href="javascript:void(0)" class="pn_page_akt">&nbsp;<?php echo $num ?>&nbsp;</a>
                <?php else : ?>
                    <a href="<?php echo $url ?>" class="pn_page">&nbsp;<?php echo $num ?>&nbsp;</a>
                <?php endif ?>
            <?php endforeach ?>
            <?php if ($arResult['PAGER']['TOTAL'] > $arResult['PAGER']['CURRENT']) : ?>
                <a href="<?php echo $arResult['PAGER']['PAGES']['NUM'][$arResult['PAGER']['CURRENT']+1] ?>" class="pn_next">&nbsp;</a>
            <?php endif ?>
        </div>
    <?php endif ?>
<?php endif ?>