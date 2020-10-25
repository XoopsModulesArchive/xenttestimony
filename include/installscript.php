<?php

$hModule = xoops_getHandler('module');
$hModConfig = xoops_getHandler('config');
$smartModule = $hModule->getByDirname('xentcareer');
$smartConfig = &$hModConfig->getConfigsByCat(0, $smartModule->getVar('mid'));

function copieimage()
{
    global $smartConfig;

    $source = XOOPS_ROOT_PATH . '/modules/xentcareer/images/nopicture.png';

    $destination = XOOPS_ROOT_PATH . $smartConfig['sbuploaddir_quote'] . '/blank.png';

    /*echo '<br>';
    echo $source;
    echo '<br>';
    echo $destination;
    echo '<br>';*/

    if (!copy($source, $destination)) {
        //print("La copie du fichier $source vers $destination n'a pas réussi...<br> Veuillez le faire manuellement ou changer le repertoire dans vos preférences<br>");
    }
}

/*function mkpath($path) {
    $dirs = explode("\\",realpath($path));
    $path = $dirs[0];
    for($i = 1;$i < count($dirs);$i++) {
    $path .= "/".$dirs[$i];
    if(!is_dir($path))
    mkdir($path);

        }

    }*/

function mkpath($target)
{
    global $smartConfig;

    if (is_dir($target) || empty($target)) {
        return 1;
    } // best case check first

    if (file_exists($target) && !is_dir($target)) {
        return 0;
    }

    if (mkpath(mb_substr($target, 0, mb_strrpos($target, '/')))) {
        if (!file_exists($target)) {
            return mkdir($target);
        }
    } // crawl back up & create dir tree

    return 0;
    if (!chmod($filename, 0777)) {
        //echo sprintf(_AM_XENT_CR_FOLDERTEST_ERRCHMOD, $target);

        exit;
    }

    if ($target == $smartConfig['sbuploaddir_quote']) {
        copieimage();
    }
}

function foldertest($folder)
{
    if (file_exists($folder)) {
        copieimage();

    //print sprintf(_AM_XENT_CR_FOLDERTEST_EXISTE, $folder);
    } else {
        mkpath($folder);

        //print sprintf(_AM_XENT_CR_FOLDERTEST_EXISTEPAS, $folder);
    }
}

function xoops_module_install_xentcareer(&$module)
{
    global $smartConfig;

    //echo "REPERTOIRE : ".XOOPS_ROOT_PATH . $smartConfig['sbuploaddir_cv']."<br>";

    foldertest(XOOPS_ROOT_PATH . $smartConfig['sbuploaddir_cv']);

    foldertest(XOOPS_ROOT_PATH . $smartConfig['sbuploaddir_quote']);
}
