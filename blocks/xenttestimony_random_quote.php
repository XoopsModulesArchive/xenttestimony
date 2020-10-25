<?php

require_once XOOPS_ROOT_PATH . '/modules/xenttestimony/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/xentgen/class/xent_users.php';
require_once XOOPS_ROOT_PATH . '/modules/xentgen/class/xent_modules.php';

function random_quote_show()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsTpl, $myts, $xentUsers;

    $hModule = xoops_getHandler('module');

    $hModConfig = xoops_getHandler('config');

    $smartModule = $hModule->getByDirname('xenttestimony');

    $this->smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

    $url = XOOPS_URL . '/modules/xentgen/';

    $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" media="all" href=' . $url . 'include/xentcareer.css>');

    $block = [];

    $result = $xoopsDB->query('SELECT id, id_user, quote_quotetitle FROM ' . $xoopsDB->prefix('xent_tt_quote') . " WHERE status='1	' ORDER BY RAND() LIMIT 1");

    [$id, $id_user, $quote_quotetitle] = $xoopsDB->fetchRow($result);

    $xentUsers = new XentUsers();

    $xentModules = new XentModules();

    $mod_xEntCareer_exist = $xentModules->modulesExists('xentcareer');

    $desclink = $smartModule['desctitrelink'];

    if (1 == $mod_xEntCareer_exist and 1 == $desclink) {
        $viewdesclink = 1;
    } else {
        $viewdesclink = 0;
    }

    $user = $xentUsers->getUser($id_user);

    $name = $xentUsers->transformName($user['name']);

    $quote_quotetitle = htmlspecialchars($quote_quotetitle, ENT_QUOTES | ENT_HTML5);

    $name = $xentUsers->transformName($name);

    $block['quote_id'] = $id;

    $block['quote_nom'] = $name;

    $block['quote_titreposte'] = $user['job'];

    $block['quote_pict'] = $user['pictpropath'];

    $block['quote_quotetitle'] = $quote_quotetitle;

    $block['quote_titreposte'] = $user['job'];

    $block['titreposte_id'] = $user['id_job']; //adfads

    $block['view_desclink'] = $mod_xEntCareer_exist;

    return $block;
}
