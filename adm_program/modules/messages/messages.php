<?php
/**
 ***********************************************************************************************
 * PM list page
 *
 * @copyright 2004-2021 The Admidio Team
 * @see https://www.admidio.org/
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0 only
 *
 ***********************************************************************************************
 */
require_once(__DIR__ . '/../../system/common.php');

// check for valid login
if (!$gValidLogin)
{
    $gMessage->show($gL10n->get('SYS_INVALID_PAGE_VIEW'));
    // => EXIT
}

// check if the call of the page was allowed
if (!$gSettingsManager->getBool('enable_pm_module') && !$gSettingsManager->getBool('enable_mail_module'))
{
    $gMessage->show($gL10n->get('SYS_MODULE_DISABLED'));
    // => EXIT
}

// Initialize and check the parameters
$getMsgUuid = admFuncVariableIsValid($_GET, 'msg_uuid', 'string');

if ($getMsgUuid !== '')
{
    $delMessage = new TableMessage($gDb);
    $delMessage->readDataByUuid($getMsgUuid);

    // Function to delete message
    $delete = $delMessage->delete();
    if ($delete)
    {
        echo 'done';
    }
    else
    {
        echo 'delete not OK';
    }
    exit();
}

$headline = $gL10n->get('SYS_MESSAGES');

// add current url to navigation stack
$gNavigation->clear();
$gNavigation->addUrl(CURRENT_URL, $headline);

// create html page object
$page = new HtmlPage('admidio-messages', $headline);

// link to write new email
if ($gSettingsManager->getBool('enable_mail_module'))
{
    $page->addPageFunctionsMenuItem('menu_item_messages_new_email', $gL10n->get('SYS_WRITE_EMAIL'),
        ADMIDIO_URL.FOLDER_MODULES.'/messages/messages_write.php', 'fa-envelope-open');
}
// link to write new PM
if ($gSettingsManager->getBool('enable_pm_module'))
{
    $page->addPageFunctionsMenuItem('menu_item_messages_new_pm', $gL10n->get('SYS_WRITE_PM'),
        SecurityUtils::encodeUrl(ADMIDIO_URL.FOLDER_MODULES.'/messages/messages_write.php', array('msg_type' => 'PM')),
        'fa-comment-alt');
}

$table = new HtmlTable('adm_message_table', $page, true, true);
$table->setServerSideProcessing(ADMIDIO_URL.FOLDER_MODULES.'/messages/messages_data.php');

$table->setColumnAlignByArray(array('left', 'left', 'left', 'left', 'left', 'right'));
$table->addRowHeadingByArray(array(
    '<i class="fas fa-envelope" data-toggle="tooltip" title="' . $gL10n->get('SYS_CATEGORY') . '"></i>',
    $gL10n->get('SYS_SUBJECT'),
    $gL10n->get('SYS_CONVERSATION_PARTNER'),
    '<i class="fas fa-paperclip" data-toggle="tooltip" title="' . $gL10n->get('SYS_ATTACHMENT') . '"></i>',
    $gL10n->get('SYS_DATE'),
    ''
));
$table->disableDatatablesColumnsSort(array(3, 6));
$table->setDatatablesColumnsNotHideResponsive(array(6));

// open some additional functions for messages
$moduleMessages = new ModuleMessages();
$usrId = (int) $gCurrentUser->getValue('usr_id');
$rowIndex = 0;

// special settings for the table
$table->setDatatablesOrderColumns(array(array(5, 'desc')));

// add table to the form
$page->addHtml($table->show());

// add form to html page and show page
$page->show();
