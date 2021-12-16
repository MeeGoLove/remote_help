<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

use yii\helpers\Html;
?>


<td class=""><?= Html::encode($model->name); ?></td>
<td class=""><?= Html::encode($model->ipaddr) ?></td>
<td class=""><a href="<?= Html::encode($model->deviceType->defaultConnectionType->protocol_link)
        . Html::encode($model->ipaddr) ?>"> <?= Html::encode($model->deviceType->defaultConnectionType->name) ?></td>
<?php
        
        if ($model->deviceType->optionalConnectionType !== null)
        {
            $link = Html::encode($model->deviceType->optionalConnectionType->protocol_link)
        . Html::encode($model->ipaddr) ;
            $name = Html::encode($model->deviceType->optionalConnectionType->name);
            echo "<td class=\"\"><a href=\"$link\">$name</a></td>";
        }
        else            echo '<td class=\"\"></td>';
        
