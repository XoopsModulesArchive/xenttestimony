<?php

require __DIR__ . '/admin_header.php';

foreach ($_REQUEST as $a => $b) {
    $$a = $b;
}

require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();
xoops_cp_header();
echo $oAdminButton->renderButtons('index');

echo '<br> <br>';
foldertest(XOOPS_ROOT_PATH . $xoopsModuleConfig['sbuploaddir_cv']);
echo '<br> <br>';
foldertest(XOOPS_ROOT_PATH . $xoopsModuleConfig['sbuploaddir_quote']);
echo '<br> <br>';
echo _AM_XENT_CR_SETUPFOLDERTEST;
xoops_cp_footer();
