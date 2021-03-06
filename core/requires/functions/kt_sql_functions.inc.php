<?php
add_action("after_switch_theme", "kt_sql_initialize_database");

/**
 * Po aktivaci šablony zkusí zavést obecné SQL skripty šablony
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz
 * 
 */
function kt_sql_initialize_database() {
    $wasError = false;
    $logsTableName = "kt_logs";
    if (!kt_sql_create_sql($logsTableName, "
            CREATE TABLE `{$logsTableName}` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `level_id` int(5) unsigned NOT NULL,
            `scope` varchar(30) DEFAULT NULL,
            `message` text NOT NULL,
            `date` datetime NOT NULL,
            `logged_user_name` varchar(60) DEFAULT NULL,
            `file` varchar(300) DEFAULT NULL,
            `line` int(15) unsigned DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;
            ")) {
        $wasError = true;
    }
    $termmetaTableName = "kt_termmeta";
    if (!kt_sql_create_sql($termmetaTableName, "
            CREATE TABLE `{$termmetaTableName}` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `level_id` int(5) unsigned NOT NULL,
            `scope` varchar(30) DEFAULT NULL,
            `message` text NOT NULL,
            `date` datetime NOT NULL,
            `logged_user_name` varchar(60) DEFAULT NULL,
            `file` varchar(300) DEFAULT NULL,
            `line` int(15) unsigned DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;
            ")) {
        $wasError = true;
    }
    if ($wasError) {
        add_action("admin_notices", "kt_sql_script_admin_notice");
    }
}

/**
 * Založí tabulku dle zadaného názvu (pro kontrolu) a SQL skriptu
 * s
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz
 * 
 * @param string $tableName
 * @param string $sql
 * @return boolean
 * @global \WPDB $wpdb
 */
function kt_sql_create_sql($tableName, $sql) {
    /* @var $wpdb \WPDB */
    global $wpdb;
    if (KT::issetAndNotEmpty($tableName) && KT::issetAndNotEmpty($sql)) {
        if (strcasecmp($wpdb->get_var("SHOW TABLES LIKE '{$tableName}'"), $tableName) !== 0) {
            if ($wpdb->query($sql)) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Vypíše hlášu pro chybu v systémových SQL skriptech souboru kt_core.sql
 * NENÍ POTŘEBA VOLAT VEŘEJNĚ
 * 
 * @author Martin Hlaváč
 * @link http://www.ktstudio.cz
 */
function kt_sql_script_admin_notice() {
    $fileName = path_join(KT_CORE_PATH, "kt_core.sql");
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e("POZOR: SQL skripty ze souboru \"$fileName\" se základními tabulkami WP Frameworku nebyly (plně) automaticky provedeny...", KT_DOMAIN); ?></p>
    </div>
    <?php
}
