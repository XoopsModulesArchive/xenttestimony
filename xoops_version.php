<?php
// ------------------------------------------------------------------------- //
//                    Module Carrière pour Xoops 2.0.7                       //
//                              Version:  1.0                                //
// ------------------------------------------------------------------------- //
// Author: Yoyo2021                                        				     //
// Purpose: Module Carrière                          				 //
// email: info@fpsquebec.net                                                 //
// URLs: http://www.fpsquebec.net                      //
//---------------------------------------------------------------------------//
global $xoopsModuleConfig;
$modversion['name'] = _MI_XENT_TT_NAME;
$modversion['version'] = '1';
$modversion['description'] = _MI_XENT_TT_DESC;
$modversion['credits'] = 'Mathieu Delisle (info@site3web.net)';
$modversion['author'] = 'Ecrit pour Xoops2<br>par MAthieu Delisle (Yoyo2021)<br>http://www.site3web.net';
$modversion['license'] = '';
$modversion['official'] = 1;
$modversion['image'] = 'images/xenttestimony.png';
$modversion['help'] = '';
$modversion['dirname'] = 'xenttestimony';

// MYSQL FILE
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file
$modversion['tables'][1] = 'xent_tt_quote';

$modversion['templates'][1]['file'] = 'xenttestimony_temoignageview.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'xenttestimony_temoignagelist.html';
$modversion['templates'][2]['description'] = '';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_XENT_TT_SMNAME2;
$modversion['sub'][1]['url'] = 'temoignagelist.php';

// Block
$modversion['blocks'][1]['file'] = 'xenttestimony_random_quote.php';
$modversion['blocks'][1]['name'] = _MI_XENT_TT_BNAME2;
$modversion['blocks'][1]['description'] = _MI_XENT_TT_BDESC2;
$modversion['blocks'][1]['show_func'] = 'random_quote_show';
$modversion['blocks'][1]['template'] = 'xenttestimony_random_quote.html';
$modversion['blocks'][3]['template'] = 'xentcareer_menulink.html';

//CONFIGUE

$modversion['config'][1]['name'] = 'desctitrelink';
$modversion['config'][1]['title'] = '_MI_XENT_TT_DESCTITRELINK';
$modversion['config'][1]['description'] = '_MI_XENT_TT_DESCTITRELINKDSC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;
