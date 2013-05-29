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

//var_dump($arResult['SELECTED']);
?>

<form method="POST" action="<?php echo $arResult['URL'] ?>" id="max-filter">
    <table width="100%">
        <tr>
            <td><label
                for="<?php echo $arResult['FORM']['[mark_id]']['ID'] ?>">Марка</label></td>
            <td><select name="FS<?php echo $arResult['FORM']['[mark_id]']['NAME'] ?>" id="<?php
                    echo $arResult['FORM']['[mark_id]']['ID']
                ?>">
                <option></option>
                <?php foreach ($arResult['FORM']['[mark_id]']['OPTIONS'] as $option) : ?>
                    <option
                        value="<?php echo $option['NAME'] ?>"<?php
                        echo ($arResult['SELECTED']['mark_id'] == $option['NAME'] ? ' selected="selected"' : '')
                        ?>><?php echo $option['VALUE'] ?></option>
                <?php endforeach ?>
            </select></td>
            <td>Цена</td>
            <td><label
                    for="FS<?php echo $arResult['FORM']['[price][from]']['ID'] ?>">от</label>
                <input
                    type="text"
                    size="9"
                    name="FS<?php echo $arResult['FORM']['[price][from]']['NAME'] ?>"
                    value="<?php echo $arResult['SELECTED']['price']['from'] ?>"
                    id="FS<?php echo $arResult['FORM']['[price][from]']['ID'] ?>" />
                <label
                        for="FS<?php echo $arResult['FORM']['[price][to]']['ID'] ?>">до</label>
                <input
                        type="text"
                        size="9"
                        name="FS<?php echo $arResult['FORM']['[price][to]']['NAME'] ?>"
                        value="<?php echo $arResult['SELECTED']['price']['to'] ?>"
                        id="FS<?php echo $arResult['FORM']['[price][to]']['ID'] ?>"" />
            </td>
        </tr>
        <tr>
            <td><label
                    for="<?php echo $arResult['FORM']['[model_id]']['ID'] ?>">Модель</label></td>
            <td><select name="FS<?php echo $arResult['FORM']['[model_id]']['NAME'] ?>" id="<?php
                    echo $arResult['FORM']['[model_id]']['ID']
            ?>">
                <option></option>
                <?php foreach ($arResult['FORM']['[model_id]']['OPTIONS'] as $option) : ?>
                    <option
                        value="<?php echo $option['NAME'] ?>"<?php
                            echo ($arResult['SELECTED']['model_id'] == $option['NAME'] ? ' selected="selected"' : '')
                        ?>
                        class="mark-<?php echo $option['PARENT'] ?>"
                    ><?php echo $option['VALUE'] ?></option>
                <?php endforeach ?>
            </select></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">
                <input type="submit" value="Искать" />
                &nbsp;
                <input type="reset" value="Сбросить" />
            </td>
        </tr>
    </table>
</form>