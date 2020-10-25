<?php
/**
 * $Id: functions.php,v 1.4 2005/04/07 15:10:28 m4d3l Exp $
 * Module: Soapbox
 * Version: v 1.0
 * Release Date: 18 February 2004
 * Author: hsalazar
 * Licence: GNU
 */

 /**
  * getLinkedUnameFromId()
  *
  * @param int $userid Userid of author etc
  * @param int $name:  0 Use Usenamer 1 Use realname
  * @return
  **/
 require_once XOOPS_ROOT_PATH . '/modules/xentgen/include/xentfunctions.php';

if (preg_match('/functions.php/', $_SERVER['PHP_SELF'])) {
    die('Access denied');
}

function getLinkedUnameFromId($userid = 0, $name = 0)
{
    if (!is_numeric($userid)) {
        return $userid;
    }

    $userid = (int)$userid;

    if ($userid > 0) {
        $memberHandler = xoops_getHandler('member');

        $user = $memberHandler->getUser($userid);

        if (is_object($user)) {
            $ts = MyTextSanitizer::getInstance();

            $username = $user->getVar('uname');

            $usernameu = $user->getVar('name');

            if (($name) && !empty($usernameu)) {
                $username = $user->getVar('name');
            }

            if (!empty($usernameu)) {
                $linkeduser = "$usernameu [<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $userid . "'>" . $ts->htmlSpecialChars($username) . '</a>]';
            } else {
                $linkeduser = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $userid . "'>" . ucwords($ts->htmlSpecialChars($username)) . '</a>';
            }

            return $linkeduser;
        }
    }

    return $GLOBALS['xoopsConfig']['anonymous'];
}

function updaterating($sel_id) // updates rating data in itemtable for a given item
{
    global $xoopsDB;

    $totalrating = 0;

    $votesDB = 0;

    $finalrating = 0;

    $query = 'select rating FROM ' . $xoopsDB->prefix('sbvotedata') . " WHERE lid = $sel_id ";

    $voteresult = $xoopsDB->query($query);

    $votesDB = $xoopsDB->getRowsNum($voteresult);

    while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
        $totalrating += $rating;
    }

    if (0 != ($totalrating) && 0 != $votesDB) {
        $finalrating = $totalrating / $votesDB;

        $finalrating = number_format($finalrating, 4);
    }

    $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('sbarticles') . " SET rating = '$finalrating', votes = '$votesDB' WHERE articleID  = $sel_id");
}

function countByCategory($c)
{
    global $xoopsUser, $xoopsDB, $xoopsModule;

    $count = 0;

    $sql = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('sbarticles') . " WHERE submit ='0' and columnID = '$c'");

    while ($myrow = $xoopsDB->fetchArray($sql)) {
        $perm_name = 'Column Permissions';

        $perm_itemid = $myrow['topicID'];

        if ($xoopsUser) {
            $groups = $xoopsUser->getGroups();
        } else {
            $groups = XOOPS_GROUP_ANONYMOUS;
        }

        $module_id = $xoopsModule->getVar('mid');

        $gpermHandler = xoops_getHandler('groupperm');

        if ($gpermHandler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {
            $count++;
        }
    }

    return $count;
}

function displayimage($image = 'blank.gif', $path = '', $imgsource = '', $alttext = '')
{
    global $xoopsConfig, $xoopsUser, $xoopsModule;

    $showimage = '';

    /**
     * Check to see if link is given
     */

    if ($path) {
        $showimage = '<a href=' . $path . '>';
    }

    /**
     * checks to see if the file is valid else displays default blank image
     */

    if (!is_dir(XOOPS_ROOT_PATH . '/' . $imgsource . '/' . $image) && file_exists(XOOPS_ROOT_PATH . '/' . $imgsource . '/' . $image)) {
        $showimage .= '<img src=' . XOOPS_URL . '/' . $imgsource . '/' . $image . " border='0' alt=" . $alttext . '></a>';
    } else {
        if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $showimage .= "<img src=images/brokenimg.png border='0' alt='" . _AM_SB_ISADMINNOTICE . "'></a>";
        } else {
            $showimage .= "<img src=images/blank.png border='0' alt=" . $alttext . '></a>';
        }
    }

    // clearstatcache();

    return $showimage;
}

function uploading($allowed_mimetypes, $httppostfiles, $redirecturl = 'index.php', $num = 0, $dir = 'uploads', $redirect = 0)
{
    require_once XOOPS_ROOT_PATH . '/class/uploader.php';

    global $xoopsConfig, $xoopsModuleConfig, $_POST;

    $maxfilesize = $xoopsModuleConfig['maxfilesize'];

    $maxfilewidth = $xoopsModuleConfig['maximgwidth'];

    $maxfileheight = $xoopsModuleConfig['maximgheight'];

    $uploaddir = XOOPS_ROOT_PATH . '/' . $dir . '/';

    $uploader = new XoopsMediaUploader($uploaddir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$num])) {
        if (!$uploader->upload()) {
            $errors = $uploader->getErrors();

            redirect_header($redirecturl, 1, $errors);
        } else {
            if ($redirect) {
                redirect_header($redirecturl, '1', 'Image Uploaded');
            }
        }
    } else {
        $errors = $uploader->getErrors();

        redirect_header($redirecturl, 1, $errors);
    }
}

function htmlarray($thishtmlpage, $thepath)
{
    global $xoopsConfig, $wfsConfig;

    $file_array = filesarray($thepath);

    echo "<select size='1' name='htmlpage'>";

    echo "<option value='-1'>------</option>";

    foreach ($file_array as $htmlpage) {
        if ($htmlpage == $thishtmlpage) {
            $opt_selected = "selected='selected'";
        } else {
            $opt_selected = '';
        }

        echo "<option value='" . $htmlpage . "' $opt_selected>" . $htmlpage . '</option>';
    }

    echo '</select>';

    return $htmlpage;
}

function filesarray($filearray)
{
    $files = [];

    $dir = opendir($filearray);

    while (false !== ($file = readdir($dir))) {
        if ((!preg_match('/^[.]{1,2}$/', $file) && preg_match('/[.htm|.html|.xhtml]$/i', $file) && !is_dir($file))) {
            if ('cvs' != mb_strtolower($file) && !is_dir($file)) {
                $files[$file] = $file;
            }
        }
    }

    closedir($dir);

    asort($files);

    reset($files);

    return $files;
}

function getuserForm($user)
{
    global $xoopsDB, $xoopsConfig;

    echo "<select name='author'>";

    echo "<option value='-1'>------</option>";

    $result = $xoopsDB->query('SELECT uid, uname FROM ' . $xoopsDB->prefix('users') . ' ORDER BY uname');

    while (list($uid, $uname) = $xoopsDB->fetchRow($result)) {
        if ($uid == $user) {
            $opt_selected = "selected='selected'";
        } else {
            $opt_selected = '';
        }

        echo "<option value='" . $uid . "' $opt_selected>" . $uname . '</option>';
    }

    echo '</select></div>';
}

function copieimage()
{
    global $xoopsModule, $xoopsModuleConfig;

    $source = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/images/nopicture.png';

    $destination = XOOPS_ROOT_PATH . $xoopsModuleConfig['sbuploaddir_quote'] . '/blank.png';

    /*echo '<br>';
    echo $source;
    echo '<br>';
    echo $destination;
    echo '<br>';*/

    if (!copy($source, $destination)) {
        print("La copie du fichier $source vers $destination n'a pas réussi...<br> Veuillez le faire manuellement ou changer le repertoire dans vos preférences<br>");
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
    global $xoopsModule, $xoopsModuleConfig;

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
        echo sprintf(_AM_XENT_CR_FOLDERTEST_ERRCHMOD, $target);

        exit;
    }

    if ($target == $xoopsModuleConfig['sbuploaddir_quote']) {
        copieimage();
    }
}

function foldertest($folder)
{
    global $xoopsModuleConfig;

    if (file_exists($folder)) {
        copieimage();

        print sprintf(_AM_XENT_CR_FOLDERTEST_EXISTE, $folder);
    } else {
        mkpath($folder);

        print sprintf(_AM_XENT_CR_FOLDERTEST_EXISTEPAS, $folder);
    }
}

/*function reference($fct1, $fct2, $fct3, $id){
global $xoopsDB;
$myts = MyTextSanitizer::getInstance();
//$sql = "SELECT ".$fct3.", ".$fct2." FROM " . $xoopsDB -> prefix( $fct1 ) . " ";
//$result = $xoopsDB -> query( $sql );
//$thearray = array();


$result = $xoopsDB->query("SELECT ".$fct3.", ".$fct2." FROM ".$xoopsDB->prefix($fct1)." WHERE ".$fct3."=$id");
        list($id, $champs) = $xoopsDB->fetchRow($result);
$titres = $myts->displayTarea($champs);
return $titres;

}*/

/*function GetTopic($fct1, $fct2, $fct3) {
global $xoopsDB;
$myts = MyTextSanitizer::getInstance();
$sql = "SELECT ".$fct3.", ".$fct2." FROM " . $xoopsDB -> prefix( $fct1 ) . " ";
$result = $xoopsDB -> query( $sql );
$thearray = array();

while ( $topic = $xoopsDB -> fetcharray( $result ) ) {
$theid = htmlspecialchars($topic[$fct3]);
$thename = htmlspecialchars($topic[$fct2]);
$thearray[$theid] = $thename;

}

//$locations = htmlspecialchars($topic[$fct3]);




return $thearray;

}*/

// ca c une fonction qui te bati un array
function getTypeArray($fromadminsection = false)
{
    $typearray = [0 => _AM_SF_TOPICTYPE0, 1 => _AM_SF_TOPICTYPE1, 2 => _AM_SF_TOPICTYPE2];

    return $typearray;
}

function xoops_module_install_liaise($module)
{
    global $modulepermHandler;

    /*
    $msgs[] = 'Setting up default permissions...';
    $m = '&nbsp;&nbsp;Grant permission of form id %u to group id %u ......%s';
    */

    for ($i = 1; $i < 4; $i++) {
        $perm = $modulepermHandler->create();

        $perm->setVar('gperm_name', 'liaise_form_access');

        $perm->setVar('gperm_itemid', 1);

        $perm->setVar('gperm_groupid', $i);

        $perm->setVar('gperm_modid', $module->getVar('mid'));

        $modulepermHandler->insert($perm);

        /*
        if( !$modulepermHandler->insert($perm) ){
            $msgs[] = sprintf($m, 1, $i, 'failed');
        }else{
            $msgs[] = sprintf($m, 1, $i, 'done');
        }
        */
    }

    return true;
}
