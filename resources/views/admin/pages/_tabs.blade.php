<?php
$tabs = [
    '/index/'.$id => '<i class="ico left fa-list"></i>Подстраницы',
    '/edit/'.$id => '<i class="ico left fa-list"></i>Контент',
    '/history/'.$id => '<i class="ico fa-history" title="История правок"></i>', 
];
?>
<ul class="tabs classic topline">
  <?php foreach ($tabs as $uri=>$title):?>
  <li class="tabs__item"><a class="tabs__link<?=(Request::is(config('cms.admin_uri').$uri.'*')?' active':'')?>" href="<?= config('cms.admin_uri') ?><?=$uri?>"><?=$title?></a></li>
  <?php endforeach;?>
</ul>
