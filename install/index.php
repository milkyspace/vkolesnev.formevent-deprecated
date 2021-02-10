<?

IncludeModuleLangFile(__FILE__);

use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Page\Asset;

/**
 * Class vkolesnev_formevent
 */
Class Vkolesnev_FormEvent extends \CModule
{

    var $errors;

    const MODULE_ID = 'vkolesnev.formevent';
    const MODULE_NAME = 'События для форм';
    const MODULE_DESCRIPTION = 'События для форм';

    public $MODULE_ID = self::MODULE_ID;
    public $MODULE_NAME = self::MODULE_NAME;
    public $MODULE_DESCRIPTION = self::MODULE_DESCRIPTION;
    public $MODULE_VERSION = '1.0.0';
    public $MODULE_VERSION_DATE = '20.01.2020';
    public $PARTNER_NAME = 'vkolesnev';

    private static function isLocal()
    {
        $moduleID = self::MODULE_ID;
        return is_file($_SERVER['DOCUMENT_ROOT'] . "/local/modules/{$moduleID}/install/index.php");
    }

    /**
     * @return bool|void
     */
    public function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        $this->InstallHL();
        $this->InstallTableControl();
        ModuleManager::RegisterModule("vkolesnev.formevent");
        return true;
    }

    /**
     * @return bool|void
     */
    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallHL();
        $this->UnInstallTableControl();
        ModuleManager::UnRegisterModule("vkolesnev.formevent");
        return true;
    }

    public function InstallHL()
    {
        $result = HighloadBlockTable::add([
            'NAME' => 'FormEventList',
            'TABLE_NAME' => 'p_vkolesnev_formevent_hl',
        ]);
        $this->errors = false;
        if (!$result->isSuccess()) {
            $this->errors = $result->getErrorMessages();
        } else {
            $id = $result->getId();
            \COption::SetOptionString(self::MODULE_ID, 'hl_id', $id);
            $oUserTypeEntity = new CUserTypeEntity();
            $aUserFields = [
                [
                    'ENTITY_ID' => 'HLBLOCK_' . $id,
                    'FIELD_NAME' => 'UF_BUTTON_SELECTOR',
                    'USER_TYPE_ID' => 'string',
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'I',
                    'EDIT_FORM_LABEL' => [
                        'ru' => 'Селектор Кнопки',
                        'en' => 'Selector of button',
                    ],
                    'LIST_COLUMN_LABEL' => [
                        'ru' => 'Селектор Кнопки',
                        'en' => 'Selector of button',
                    ],
                    'LIST_FILTER_LABEL' => [
                        'ru' => 'Селектор Кнопки',
                        'en' => 'Selector of button',
                    ]
                ],
                [
                    'ENTITY_ID' => 'HLBLOCK_' . $id,
                    'FIELD_NAME' => 'UF_FORM_SELECTOR',
                    'USER_TYPE_ID' => 'string',
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'I',
                    'EDIT_FORM_LABEL' => [
                        'ru' => 'Селектор Формы',
                        'en' => 'Selector of form',
                    ],
                    'LIST_COLUMN_LABEL' => [
                        'ru' => 'Селектор Формы',
                        'en' => 'Selector of form',
                    ],
                    'LIST_FILTER_LABEL' => [
                        'ru' => 'Селектор Формы',
                        'en' => 'Selector of form',
                    ]
                ],
                [
                    'ENTITY_ID' => 'HLBLOCK_' . $id,
                    'FIELD_NAME' => 'UF_EVENT',
                    'USER_TYPE_ID' => 'string',
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'I',
                    'EDIT_FORM_LABEL' => [
                        'ru' => 'Событие',
                        'en' => 'Event',
                    ],
                    'LIST_COLUMN_LABEL' => [
                        'ru' => 'Событие',
                        'en' => 'Event',
                    ],
                    'LIST_FILTER_LABEL' => [
                        'ru' => 'Событие',
                        'en' => 'Event',
                    ]
                ],
                [
                    'ENTITY_ID' => 'HLBLOCK_' . $id,
                    'FIELD_NAME' => 'UF_EVENT_NAME',
                    'USER_TYPE_ID' => 'string',
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'I',
                    'EDIT_FORM_LABEL' => [
                        'ru' => 'Тип события Битрикс',
                        'en' => 'Event',
                    ],
                    'LIST_COLUMN_LABEL' => [
                        'ru' => 'Тип события Битрикс',
                        'en' => 'Event',
                    ],
                    'LIST_FILTER_LABEL' => [
                        'ru' => 'Тип события Битрикс',
                        'en' => 'Event',
                    ]
                ]

            ];
            foreach ($aUserFields as $field) {
                $iUserFieldId[] = $oUserTypeEntity->Add($field);
            }

        }
        if (!$this->errors) {
            return $id;
        } else {
            return $this->errors;
        }
    }

    public function InstallTableControl()
    {
        global $DB;
        $DB->RunSQLBatch(__DIR__ . '/installTableControl.sql');
    }

    public function UnInstallHL()
    {
        $hlblock = HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'FormEventList']
        ])->fetch()['ID'];

        HighloadBlockTable::delete($hlblock);
    }

    public function UnInstallTableControl()
    {
        global $DB;
        $DB->RunSQLBatch(__DIR__ . '/uninstallTableControl.sql');
    }

    public function InstallDB()
    {
    }

    public function UnInstallDB()
    {
    }

    public function InstallEvents()
    {
        \RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, __CLASS__, 'OnBeforeProlog');
        \RegisterModuleDependences("main", "OnBuildGlobalMenu", $this->MODULE_ID, __CLASS__, "DoBuildGlobalMenu");
        \RegisterModuleDependences("main", "OnBeforeEventAdd", $this->MODULE_ID, __CLASS__, "OnBeforeEventAddHandler");

        return true;
    }

    public function UnInstallEvents()
    {
        \UnRegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, __CLASS__, 'OnBeforeProlog');
        \UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', $this->MODULE_ID, __CLASS__, 'DoBuildGlobalMenu');
        return true;
    }

    public function InstallFiles()
    {
        return true;
    }

    public function UnInstallFiles()
    {
        return true;
    }

    /**
     * @param $aGlobalMenu
     * @param $aModuleMenu
     */
    public static function DoBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        $hlId = \COption::GetOptionString(self::MODULE_ID, 'hl_id');
        $aModuleMenu[] = [
            "parent_menu" => "global_menu_settings",
            "icon" => "formevent_icon",
            "page_icon" => "formevent_icon",
            "sort" => "1",
            "text" => "События для форм",
            "title" => "События для форм",
            "more_url" => [],
            "items" => [
                [
                    'text' => 'Настройки модуля',
                    'url' => '/bitrix/admin/settings.php?lang=ru&mid=vkolesnev.formevent',
                    'dynamic' => true,
                    'module_id' => self::MODULE_ID,
                    'items_id' => 'menu_formevent_1',
                    'title' => 'Настройки модуля',
                ],
                [
                    'text' => 'Список правил',
                    'url' => '/bitrix/admin/highloadblock_rows_list.php?ENTITY_ID=' . $hlId,
                    'dynamic' => true,
                    'module_id' => self::MODULE_ID,
                    'items_id' => 'menu_formevent_2',
                    'title' => 'Список правил',
                ],
            ]
        ];
    }

    public static function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        global $DB, $USER;
        $DB->PrepareFields("b_vkolesnev_formevent_event_by_user");
        $user = ($USER->GetID()) ? $USER->GetID() : session_id();
        $arFields = array(
            "CREATED_AT" => $DB->GetNowFunction(),
            "EVENT_TYPE" => "'" . trim($event) . "'",
            "USER_ID" => "'" . trim($user) . "'"
        );
        $DB->StartTransaction();
        $ID = $DB->Insert("b_vkolesnev_formevent_event_by_user", $arFields, $err_mess . __LINE__);
        $DB->Commit();
    }

    public static function OnBeforeProlog()
    {
        global $APPLICATION;
        if (isset($_POST['MODULE']) && $_POST['MODULE'] === self::MODULE_ID) {
            $APPLICATION->RestartBuffer();

            header('Content-Type: application/json; charset=UTF-8');

            require dirname(__DIR__) . '/ajax.php';

            ob_flush();

            exit;
        }

        $dir = \str_replace(\realpath($_SERVER['DOCUMENT_ROOT']), '', \realpath(\dirname(__DIR__)));
        $script = \preg_replace('#//+#', '/', "/{$dir}/js/base.js");
        $str = '<script type="text/javascript" src="' . $script . '"></script>';
        $obAsset = Asset::getInstance()->addString($str);

        $script = \preg_replace('#//+#', '/', "/{$dir}/js/form.js");
        $str = '<script type="text/javascript" src="' . $script . '"></script>';
        $obAsset = Asset::getInstance()->addString($str);

        $style = \preg_replace('#//+#', '/', "/{$dir}/css/style.css");
        $str = '<link rel="stylesheet" type="text/css" href="' . $style . '">';
        $obAsset = Asset::getInstance()->addString($str);

        $gtagOrGtm = \COption::GetOptionString(self::MODULE_ID, 'gtag_or_gtm');

        if ($gtagOrGtm === 'gtag') {
            $gtagId = \COption::GetOptionString(self::MODULE_ID, 'gtagidcode');
            $gtag = "<!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $gtagId . "\"></script>
                <script>
                    window.dataLayer = window.dataLayer || [];
            
                    function gtag() {
                        dataLayer.push(arguments);
                    }
            
                    gtag('js', new Date());
                    gtag('config', '" . $gtagId . "');
                </script>";
            $obAsset = Asset::getInstance()->addString($gtag);
        }
    }
}