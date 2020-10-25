<?php
include 'header.php';
require XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/include/functions.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$GLOBALS['xoopsOption']['template_main'] = 'xenttestimony_temoignageview.html';
$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();

foreach ($_REQUEST as $a => $b) {
    $$a = $b;
}

function XENT_TT_Quote($quote_id)
{
    global $xoopsDB, $xoopsTpl, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xentUsers;

    $result = $xoopsDB->query('SELECT id, id_user, quote_experience, quote_quotetitle, citation, status FROM ' . $xoopsDB->prefix('xent_tt_quote') . " WHERE id=$quote_id");

    [$id, $id_user, $quote_experience, $quote_quotetitle, $citation, $status] = $xoopsDB->fetchRow($result);

    if (0 == $status) {
        redirect_header('index.php', 1, _MI_XENT_TT_NOTEXIST);
    }

    $myts = MyTextSanitizer::getInstance();

    $xentModules = new XentModules();

    $mod_xEntGen_exist = $xentModules->modulesExists('xentgen');

    $mod_xEntCareer_exist = $xentModules->modulesExists('xentcareer');

    $user = $xentUsers->getUser($id_user);

    $name = $xentUsers->transformName($user['name']);

    $citation = $myts->displayTarea($citation);

    $quote_experience = $myts->displayTarea($quote_experience);

    $quote_quotetitle = $myts->displayTarea($quote_quotetitle);

    $hModule = xoops_getHandler('module');

    $hModConfig = xoops_getHandler('config');

    $smartModule = $hModule->getByDirname('xentgen');

    $this->smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

    $desclink = $smartModule['desctitrelink'];

    $xoopsTpl->assign('id', $id);

    $xoopsTpl->assign('quote_nom', $xentUsers->transformName($user['name']));

    $xoopsTpl->assign('quote_pict', $user['pictpropath']);

    $xoopsTpl->assign('quote_titreposte', $user['job']);

    $xoopsTpl->assign('quote_typeposte', $user['typeposte']);

    $xoopsTpl->assign('quote_location', $user['location']);

    $xoopsTpl->assign('quote_experience', $quote_experience);

    $xoopsTpl->assign('quote_quotetitle', $quote_quotetitle);

    $xoopsTpl->assign('citation', $citation);

    $xoopsTpl->assign('LANG_typeposte', _MI_XENT_TT_TYPEPOSTE);

    $xoopsTpl->assign('LANG_nom', _MI_XENT_TT_NOM);

    $xoopsTpl->assign('LANG_titreposte', _MI_XENT_TT_POSTEOCCUPE);

    $xoopsTpl->assign('LANG_location', _MI_XENT_TT_LOCATIONS);

    $xoopsTpl->assign('LANG_experience', _MI_XENT_TT_EXPERIENCE);

    $xoopsTpl->assign('LANG_viewotherquote', _MI_XENT_TT_VIEWOTHERQUOTE);

    $xoopsTpl->assign('titreposte_id', $user['id_job']);

    if (1 == $mod_xEntCareer_exist and 1 == $desclink) {
        $viewdesclink = 1;
    } else {
        $viewdesclink = 0;
    }

    $xoopsTpl->assign('view_desclink', $viewdesclink);
}

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';
switch ($op) {
    case 'XENT_TT_Quote':
    XENT_TT_Quote($quote_id);
    break;
    default:
    redirect_header('index.php', 1, _MI_XENT_TT_RETURNTOINDEX);
    exit;
    break;
}

include 'footer.php';
