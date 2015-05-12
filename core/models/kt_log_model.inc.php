<?php

/**
 * Model pro výpis KT logů
 *
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz
 */
class KT_Log_Model extends KT_Crud implements KT_Modelable {

    const TABLE = "kt_logs";
    const ORDER_COLUMN = self::TITLE_COLUMN;
    const PREFIX = "kt_log";
    const FORM_PREFIX = "kt-log";
    // sloupce v DB
    const ID_COLUMN = "id";
    const LEVEL_ID_COLUMN = "level_id";
    const MESSAGE_COLUMN = "message";
    const DATE_COLUMN = "date";
    const LOGGED_USER_NAME_COLUMN = "logged_user_name";
    const FILE_COLUMN = "file";
    const LINE_COLUMN = "line";

    private $level;

    public function __construct($rowId = null) {
        parent::__construct(self::TABLE, self::ID_COLUMN, null, $rowId);
        $this->post = get_post($this->getProductId());
    }

    // --- gettery & settery ------------------------

    /**
     * @return int
     */
    public function getLevelId() {
        return $levelId = $this->getColumnValue(self::LEVEL_ID_COLUMN);
    }

    /**
     * @return \KT_Log_Level_Enum
     */
    public function getLevel() {
        $level = $this->level;
        if (KT::issetAndNotEmpty($level)) {
            return $level;
        }
        return $this->level = new KT_Log_Level_Enum($this->getLevelId());
    }

    /**
     * @return datetime
     */
    public function getDate() {
        return $message = $this->getColumnValue(self::DATE_COLUMN);
    }

    /**
     * @return string
     */
    public function getLoggedUserName() {
        return $message = $this->getColumnValue(self::LOGGED_USER_NAME_COLUMN);
    }

    /**
     * @return string
     */
    public function getFile() {
        return $message = $this->getColumnValue(self::FILE_COLUMN);
    }

    /**
     * @return int
     */
    public function getLine() {
        return $message = $this->getColumnValue(self::LINE_COLUMN);
    }

    // --- veřejné funkce ------------------------
    // --- neveřejné funkce ------------------------

    /**
     * Provede inicializaci sloupců v DB
     */
    protected function initColumns() {
        $this->addColumn(self::ID_COLUMN, KT_CRUD_Column::INT);
        $this->addColumn(self::LEVEL_ID_COLUMN, KT_CRUD_Column::INT);
        $this->addColumn(self::MESSAGE_COLUMN);
        $this->addColumn(self::DATE_COLUMN, KT_CRUD_Column::DATETIME);
        $this->addColumn(self::LOGGED_USER_NAME_COLUMN, KT_CRUD_Column::TEXT, true);
        $this->addColumn(self::FILE_COLUMN, KT_CRUD_Column::TEXT, true);
        $this->addColumn(self::LINE_COLUMN, KT_CRUD_Column::INT, true);
    }

}