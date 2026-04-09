<?php
require __DIR__ . '/bootstrap.php';

pageHeader('Фотолетопись кафедры', 'gallery');

renderGalleryBlock('Фотолетопись кафедры', 'Учебные занятия, выездные этапы и демонстрация разработок на профильных площадках', $departmentGallery);
renderGalleryBlock('Кафедральная символика', 'Эмблемы кафедры и архивные знаки', $departmentHeraldry, true);

pageFooter();
