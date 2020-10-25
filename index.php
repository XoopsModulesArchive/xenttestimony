<?php
include 'header.php';
require XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/include/functions.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

$GLOBALS['xoopsOption']['template_main'] = 'xenttestimony_temoignagelist.html';
$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();

foreach ($_REQUEST as $a => $b) {
    $$a = $b;
}

function liste()
{
    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables, $xentUsers;

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('xent_tt_quote'));

    $myts = MyTextSanitizer::getInstance();

    $i = 0;

    while (false !== ($cat_data = $xoopsDB->fetchArray($result))) {
        $i++;

        $user = $xentUsers->getUser($cat_data['id_user']);

        $name = $xentUsers->transformName($user['name']);

        //$cat_data['quote_titreposte'] = reference("xent_TT_titres", "titres", "id_titres", $cat_data['quote_titreposte']);

        $cat_data['quote_quotetitle'] = $myts->displayTarea($cat_data['quote_quotetitle']);

        $lien = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/temoignage.php?op=XENT_TT_Quote&quote_id=';

        $temoignage['lien'] = $lien;

        $temoignage['id'] = $cat_data['id'];

        $temoignage['quote_nom'] = $name;

        $temoignage['quote_titreposte'] = $user['job'];

        $temoignage['quote_quotetitle'] = $cat_data['quote_quotetitle'];

        $temoignage['count'] = $i;

        $xoopsTpl->append('temoignageliste', $temoignage);
    }

    $xoopsTpl->assign('lang_TEMOIGNAGETITLE', _MI_XENT_TT_TEMOIGNAGELIST);
}

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';
switch ($op) {
    case 'liste':
    liste($ordre);
    break;
    /*case "XENT_TT_Fullview":
    XENT_TT_Fullview($row_pos, $ordre);
    break;*/
    default:
    //$ordre = "id_job";
    liste();
    break;
}

include 'footer.php';
