<?php

require __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

foreach ($_REQUEST as $a => $b) {
    $$a = $b;
}

require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();

xoops_cp_header();

function AddNewQuote()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $oAdminButton;

    echo $oAdminButton->renderButtons('adminaddquote');

    $xentUsers = new XentUsers();

    $colimage = 'blank.png';

    OpenTable();

    //$log_time = date("Y-m-d");

    //$log_time = formatTimestamp(time(), 'Y-m-d');

    //echo $log_time;

    $sform = new XoopsThemeForm(_AM_XENT_TT_FORMNAME, 'addquote', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    //$sform -> addElement( new XoopsFormSelectUser(_AM_XENT_TT_SELECTUSER, "id_user", 0, "", 1, false));

    $user_select = new XoopsFormSelect(_AM_XENT_TT_SELECTUSER, 'id_user');

    $user_select->addOptionArray($xentUsers->getAllUsersInArray());

    $sform->addElement($user_select);

    $sform->addElement(new XoopsFormTextArea(_AM_XENT_TT_EXPERIENCE, 'experience', '[fr]Francais[/fr][en]Anglais[/en]', $rows = 5, $cols = 50, $hiddentext = 'xoopsHiddenText'));

    $sform->addElement(new XoopsFormText(_AM_XENT_TT_QUOTETITLE, 'quotetitle', '35', '255', $value = '[fr]Francais[/fr][en]Anglais[/en]'));

    $sform->addElement(new XoopsFormTextArea(_AM_XENT_TT_CITATION, 'citation', '[fr]Francais[/fr][en]Anglais[/en]', $rows = 5, $cols = 50, $hiddentext = 'xoopsHiddenText'), true);

    $sform->addElement(new XoopsFormRadioYN(_AM_XENT_TT_STATUS, 'status', 0));

    $button_tray = new XoopsFormElementTray('', '');

    //$button_tray->addElement(new XoopsFormButton('', 'preview', _AM_PREVIEW, 'submit'));

    $button_tray->addElement(new XoopsFormButton('', 'add', _AM_XENT_TT_ADD, 'submit'));

    $sform->addElement($button_tray);

    $sform->addElement(new XoopsFormHidden('op', 'AddQuote'));

    $sform->display();

    CloseTable();

    // echo '</form>';
}

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

}

// ca c une fonction qui te bati un array
function getTypeArray($fromadminsection=false)
{
$typearray = array(0=>_AM_SF_TOPICTYPE0, 1=>_AM_SF_TOPICTYPE1, 2=>_AM_SF_TOPICTYPE2);
return $typearray;
}
*/
function EditQuote($quote_id)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $oAdminButton;

    $xentUsers = new XentUsers();

    echo $oAdminButton->renderButtons('adminquote');

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT id, id_user, quote_experience, quote_quotetitle, citation, status FROM ' . $xoopsDB->prefix('xent_tt_quote') . " WHERE id=$quote_id");

    [$id, $id_user, $quote_experience, $quote_quotetitle, $citation, $status] = $xoopsDB->fetchRow($result);

    //$citation = $myts->displayTarea($citation);

    OpenTable();

    //$log_time = date("Y-m-d");

    //$log_time = formatTimestamp(time(), 'Y-m-d');

    //echo $log_time;

    $sform = new XoopsThemeForm(_AM_XENT_TT_FORMNAME, 'EditQuote', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    // Code to call the file browser to select an image to upload

    $sform->addElement(new XoopsFormHidden('quote_id', $quote_id));

    $sform->addElement(new XoopsFormHidden('id_user', $id_user));

    //$sform -> addElement( new XoopsFormSelectUser(_AM_XENT_TT_SELECTUSER, "id_user", 0, $id_user, 1, false));

    $user_select = new XoopsFormSelect(_AM_XENT_TT_SELECTUSER, 'id_user', $id_user);

    $user_select->addOptionArray($xentUsers->getAllUsersInArray());

    $sform->addElement($user_select);

    $sform->addElement(new XoopsFormTextArea(_AM_XENT_TT_EXPERIENCE, 'quote_experience', $quote_experience, $rows = 5, $cols = 50, $hiddentext = 'xoopsHiddenText'));

    $sform->addElement(new XoopsFormText(_AM_XENT_TT_QUOTETITLE, 'quote_quotetitle', '35', '255', $value = $quote_quotetitle));

    $sform->addElement(new XoopsFormTextArea(_AM_XENT_TT_CITATION, 'citation', $citation, $rows = 5, $cols = 50, $hiddentext = 'xoopsHiddenText'), true);

    $sform->addElement(new XoopsFormRadioYN(_AM_XENT_TT_STATUS, 'status', $status));

    $button_tray = new XoopsFormElementTray('', '');

    //$button_tray->addElement(new XoopsFormButton('', 'preview', _AM_PREVIEW, 'submit'));

    $button_tray->addElement(new XoopsFormButton('', 'add', _AM_XENT_TT_EDITQUOTE, 'submit'));

    $sform->addElement($button_tray);

    $sform->addElement(new XoopsFormHidden('op', 'SaveQuote'));

    $sform->display();

    CloseTable();
}

function DelQuote($quote_id, $ok)
{
    global $xoopsDB, $xoopsConfig, $xoopsModule;

    if (1 == $ok) {
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xent_tt_quote') . " WHERE id=$quote_id");

        redirect_header('quote.php', 1, _AM_XENT_TT_DBDELUPDATED);

        exit();
    }  

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT id, id_user, citation FROM ' . $xoopsDB->prefix('xent_tt_quote') . " WHERE id=$quote_id");

    [$id, $id_user, $citation] = $xoopsDB->fetchRow($result);

    //$result = $xoopsDB->query("SELECT id_log, time_log, uname_log, ip_log, server_log, command_log, result_log FROM ".$xoopsDB->prefix("xent_tt_job")." WHERE id_log=$log_id");

    //list($log_id, $log_time, $log_uname, $log_ip, $log_server_name, $log_command, $log_result) = $xoopsDB->fetchRow($result);

    $myts = MyTextSanitizer::getInstance();

    //$description = htmlspecialchars($description, 1, 1, 1);

    $citation = $myts->displayTarea($citation);

    $xentUsers = new XentUsers();

    $user = $xentUsers->getUser($id_user);

    $quote_nom = $user['name'];

    OpenTable();

    echo '<big><b>' . _AM_XENT_TT_QUOTEDEL . "</big></b>

        <form action='addserver.php' method='post'>
        <input type='hidden' name='job_id' value='$job_id'>
        <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
        <tr>
        <td class='bg2'>
                <table border='0' cellpadding='4' cellspacing='1' width='100%'>
                <tr>
                <td class='bg3' width='200'></td>
                <td class='bg3'></td>
                </tr>
                <tr>
                <td class='bg3'><b>" . _AM_XENT_TT_VIEWID . "</b></td>
                <td class='bg1'>" . $id . "</td>
                </tr>
				<tr>
                <td class='bg3'><b>" . _AM_XENT_TT_NAME . "</b></td>
                <td class='bg1'>" . $quote_nom . "</td>
                </tr>        
                <tr>
                <td class='bg3'><b>" . _AM_XENT_TT_QUOTECITATION . "</b></td>
                <td class='bg1'>" . $citation . "</td>
                </tr>

                <tr>
                <td class='bg3'></td>
                <td class='bg3'></td>
                </tr>
                </table>
        </td>
        </tr>
        </table>

        </form>";

    echo "<table valign='top'><tr>";

    echo "<td width='30%'valign='top'><span style='color:#ff0000;'><b>" . _AM_XENT_TT_WANTDEL . '</b></span></td>';

    echo "<td width='3%'>\n";

    echo myTextForm("quote.php?op=DelQuote&quote_id=$quote_id&ok=1", _AM_XENT_TT_YES);

    echo "</td><td>\n";

    echo myTextForm('quote.php', _AM_XENT_TT_NO);

    echo "</td></tr></table>\n";

    CloseTable();
}

function QuoteAdmin($ordre)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $oAdminButton;

    echo $oAdminButton->renderButtons('adminquote');

    OpenTable();

    $xentUsers = new XentUsers();

    $xentModules = new XentModules();

    $mod_xEntGen_exist = $xentModules->modulesExists('xentgen');

    $mod_xEntCareer_exist = $xentModules->modulesExists('xentcareer');

    if (0 == $mod_xEntCareer_exist) {
        echo "Le module xEntCareer n'est pas installé. Certaines fonctions du module seront désactiver car il requiere ce module.";
    }

    if (0 == $mod_xEntGen_exist) {
        echo "Ce module est ESSENTIELLE au bon fonctionne de ce module. Vous devez absoluement l'installer.";
    }

    echo "<h4 style='text-align:left;'>" . _AM_XENT_TT_QUOTETITLE . "</h4>
        <form action='quote.php' method='post'>
        <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
        <tr>
        <td class='bg2'>
                <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                <tr class='bg1'>
                <td><b>" . _AM_XENT_TT_VIEWID . '</b></td>
                <td><b>' . _AM_XENT_TT_NAME . '</b></td>
				<td><b>' . _AM_XENT_TT_QUOTE_TITLE . '</b></td>
				<td><b>' . _AM_XENT_TT_STATUS . '</b></td>
                <td><b>' . _AM_XENT_TT_VIEWFUNCTION . '</b></td>';

    $sql = 'SELECT id, id_user, quote_quotetitle, status FROM ' . $xoopsDB->prefix('xent_tt_quote') . " ORDER BY $ordre";

    $result = $xoopsDB->query($sql);

    $myts = MyTextSanitizer::getInstance();

    $i = 0;

    while (list($id, $id_user, $quote_quotetitle, $status) = $xoopsDB->fetchRow($result)) {
        //$description = htmlspecialchars($description);

        $user = $xentUsers->getUser($id_user);

        $quote_nom = $user['name'];

        echo "<tr class='bg1'><td align='right'>$id</td>";

        echo "<td>$quote_nom</td>";

        echo "<td>$quote_quotetitle</td>";

        if (1 == $status) {
            echo '<td>Yes</td>';
        } else {
            echo '<td>No</td>';
        }

        echo "<td><a href='../temoignage.php?op=XENT_TT_Quote&quote_id=$id'>" . _AM_XENT_TT_VIEW . "</a>&nbsp;&nbsp;<a href='quote.php?op=EditQuote&quote_id=$id'>" . _AM_XENT_TT_EDIT . "</a><a href='quote.php?op=DelQuote&amp;quote_id=$id&amp;ok=0'>" . _AM_XENT_TT_DELETE . '</a></td>
                </tr>';

        $i += 1;
    }

    echo '</table>
        </td>
        </tr>
        </table>
        </form>';

    CloseTable();
}

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';
global $_POST;

switch ($op) {
    case 'DelQuote':
    DelQuote($quote_id, $ok);
    break;
    case 'AddQuote':
    global $xoopsDB, $eh, $myts;

    OpenTable();
    $myts = MyTextSanitizer::getInstance();
    //Recuperer la date:
    //$date2 = formatTimeStamp($jobposteddate, 'Y-m-d');
    //echo $date2;
    //$name = $myts->addSlashes($name);
    $experience = $myts->addSlashes($experience);
    $quotetitle = $myts->addSlashes($quotetitle);
    $citation = $myts->addSlashes($citation);
    $newid = 0;
    $sql = sprintf("INSERT INTO %s (id, id_user, quote_experience, quote_quotetitle, citation, status) VALUES (%u, %u, '%s', '%s', '%s', '%u')", $xoopsDB->prefix('xent_tt_quote'), $newid, $id_user, $experience, $quotetitle, $citation, $status);
    $xoopsDB->query($sql) or $eh::show('0013');
    // Si y'a pas d'erreurs ds la requete ci dessus, on redirige vers la page d'accueil ADMIN
    redirect_header('quote.php', 1, _AM_XENT_TT_DBUPDATED);
    exit();

    CloseTable();

    //       AddQuote($name, $picture, $titres_id, $typeposte_id, $locations_id, $experience, $quotetitle, $citation);
    break;
    case 'SaveQuote':
    global $xoopsDB;
    $myts = MyTextSanitizer::getInstance();
    $citation = $myts->addSlashes($citation);
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('xent_tt_quote') . " SET id='$quote_id', id_user='$id_user', quote_experience='$quote_experience', quote_quotetitle='$quote_quotetitle', citation='$citation', status='$status' WHERE id=$quote_id");
    redirect_header('quote.php', 1, _AM_XENT_TT_DBUPDATED);
exit();

    break;
    case 'AddNewQuote':
    AddNewQuote();
    break;
    case 'EditQuote':
    EditQuote($quote_id);
    break;
    case 'DeletePicture':
    DeletePicture();
    break;
    case 'QuoteAdmin':
    QuoteAdmin($ordre);
    break;
    default:
    $ordre = 'id';
    QuoteAdmin($ordre);
    break;
}
xoops_cp_footer();
