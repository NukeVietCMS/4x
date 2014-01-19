<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 28 Dec 2013 12:56:09 GMT
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) )
	die( 'Stop!!!' );

$sql_drop_module = array();

$count = $db->query( "select count(*) from all_tables where table_name='" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows" ) . "'" )->fetchColumn();
if( $count )
{
	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows cascade constraints PURGE';
	$sql_drop_module[] = 'drop SEQUENCE SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS';

	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_send cascade constraints PURGE';
	$sql_drop_module[] = 'drop SEQUENCE SNV_' . strtoupper( $lang . '_' . $module_data ) . '_SEND';
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_rows (
	 id NUMBER(8,0) DEFAULT NULL,
	 full_name VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 phone VARCHAR2(255 CHAR) DEFAULT NULL,
	 fax VARCHAR2(255 CHAR) DEFAULT NULL,
	 email VARCHAR2(100 CHAR) DEFAULT NULL,
	 note VARCHAR2(4000 CHAR) DEFAULT NULL,
	 admins VARCHAR2(4000 CHAR) DEFAULT NULL,
	 act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
 primary key (id),
 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_rows_full_name UNIQUE (full_name)
)";

//Tạo TRIGGER cho bảng nvx_vi_module_rows
$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_send (
	 id NUMBER(8,0) DEFAULT NULL,
	 cid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 content VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	 send_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 sender_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 sender_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	 sender_email VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	 sender_phone VARCHAR2(255 CHAR) DEFAULT '',
	 sender_ip VARCHAR2(15 CHAR) DEFAULT '' NOT NULL ENABLE,
	 is_read NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 is_reply NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 reply_content VARCHAR2(4000 CHAR) DEFAULT '',
	 reply_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 reply_aid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (id)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_SEND';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_SEND
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_send
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_SEND;';

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (full_name, phone, fax, email, note, admins, act) VALUES ('Webmaster', '', '', '', '', '1/1/1/0;', 1)";
?>