<?php
$language_data = array (
    'script_delim' => array (
        'start' => array (
            0 => '(?:<\?(?:php|=)?|<script language="php">|<%)(?=\s)',
            1 => QBB_CASELESS
        ),
        'end' => array (
            0 => '(?:[%?]>|</script>)',
            1 => QBB_CASELESS
        )
    ),
    'comments' => array (
        0 => array (
            0 => '(?:/\*.*?\*/)',
            1 => QBB_DOTALL
        ),
        1 => array (
            0 => '(?:#|//).*?(?=\?>|\n)',
            1 => QBB_NONE
        )
    ),
    'strings' => array (
        0 => array (
            0 => '(?:"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"|\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\')',
            1 => QBB_DOTALL
        )
    ),
    'regexes' => array (
        0 => array (
            0 => '[$][a-z_][a-z0-9_]*',
            1 => QBB_CASELESS
        ),
        1 => array (
            0 => '<<<([a-z_][a-z0-9_]*)\n.*?\n\1(?=;\n)',
            1 => QBB_DOTALL | QBB_CASELESS
        )
    ),
    'symbols' => array (
        0 => '[-+*%^|&=.@!?();,<>{}\[\]:]',
        1 => QBB_NONE
    ),
    'keywords' => array (
        0 => array (
            0 => '(?:include|require|include_once|require_once|for(?:each)?|echo|else(?:if)?|if|while|do|switch|return|array|continue|break|class|empty|print|isset|private|protected|public|namespace|trait|interface|finally|try|catch)\b',
            1 => QBB_CASELESS
        ),
        1 => array (
            0 => '(?:(?:as|endwhile|endif|case|endswitch|&?new|var|default|function)\b|\((?:array|float|string|double|int(?:eger)?)\))',
            1 => QBB_CASELESS
        ),
        2 => array (
            0 => '(?:<\?(?:php)?|<script language="php">|<%|[%?]>|</script>)',
            1 => QBB_CASELESS
        ),
        3 => array (
            0 =>  '(?:xml_set_processing_instruction_handler|xml_set_start_namespace_decl_handler|xml_set_unparsed_entity_decl_handler|xml_set_external_entity_ref_handler|xml_set_end_namespace_decl_handler|xml_set_character_data_handler|aggregate_properties_by_regexp|openssl_x509_check_private_key|xml_set_notation_decl_handler|xml_get_current_column_number|aggregate_properties_by_list|openssl_x509_export_to_file|openssl_pkey_export_to_file|xml_get_current_line_number|aggregate_methods_by_regexp|register_shutdown_function|xml_get_current_byte_index|get_html_translation_table|openssl_csr_export_to_file|stream_context_get_options|output_reset_rewrite_vars|openssl_x509_checkpurpose|session_get_cookie_params|stream_context_set_option|aggregate_methods_by_list|stream_context_set_params|session_set_cookie_params|set_magic_quotes_runtime|import_request_variables|get_magic_quotes_runtime|unregister_tick_function|session_set_save_handler|mysql_real_escape_string|openssl_pkey_get_private|stream_wrapper_register|apache_response_headers|quoted_printable_decode|stream_set_write_buffer|mb_decode_numericentity|stream_register_wrapper|image_type_to_mime_type|mb_substitute_character|bind_textdomain_codeset|i18n_mime_header_encode|i18n_mime_header_decode|define_syslog_variables|mb_encode_numericentity|openssl_private_decrypt|xml_set_element_handler|xml_set_default_handler|openssl_private_encrypt|openssl_pkey_get_public|mb_preferred_mime_name|i18n_internal_encoding|i18n_discover_encoding|openssl_get_privatekey|apache_child_terminate|mb_ereg_search_getregs|output_add_rewrite_var|openssl_public_decrypt|call_user_method_array|mysql_unbuffered_query|apache_request_headers|register_tick_function|pg_set_client_encoding|openssl_public_encrypt|openssl_get_publickey|get_loaded_extensions|mbereg_search_getregs|openssl_pkcs7_decrypt|get_defined_functions|restore_error_handler|mysql_get_client_info|preg_replace_callback|mysql_client_encoding|session_cache_limiter|mysql_get_server_info|get_defined_constants|session_regenerate_id|php_ini_scanned_files|openssl_pkcs7_encrypt|session_is_registered|xml_parser_get_option|xml_parser_set_option|array_merge_recursive|mb_ereg_search_setpos|xml_parse_into_struct|stream_context_create|array_change_key_case|array_intersect_assoc|mb_ereg_search_getpos|stream_filter_prepend|yp_get_default_domain|mbereg_search_setpos|stream_filter_append|posix_get_last_error|mb_decode_mimeheader|stream_get_meta_data|pg_setclientencoding|session_cache_expire|restore_include_path|mb_encode_mimeheader|xml_parser_create_ns|openssl_error_string|get_declared_classes|magic_quotes_runtime|zlib_get_coding_type|aggregate_properties|openssl_pkcs7_verify|mysql_list_processes|get_magic_quotes_gpc|pg_connection_status|mb_convert_variables|mysql_get_proto_info|wddx_serialize_value|mb_regex_set_options|mbereg_search_getpos|call_user_func_array|socket_create_listen|mb_internal_encoding|mysql_escape_string|pg_connection_reset|mysql_affected_rows|stream_set_blocking|session_module_name|openssl_pkey_export|wddx_serialize_vars|openssl_x509_export|get_extension_funcs|socket_iovec_delete|mysql_get_host_info|mb_ereg_search_init|mb_convert_encoding|socket_set_nonblock|mysql_fetch_lengths|mb_ereg_search_regs|set_socket_blocking|session_write_close|socket_set_blocking|i18n_ja_jp_hantozen|get_required_files|iconv_set_encoding|session_unregister|stream_set_timeout|socket_getpeername|pg_client_encoding|xml_get_error_code|openssl_x509_parse|mbereg_search_init|socket_set_timeout|socket_getsockname|pg_connection_busy|openssl_pkcs7_sign|array_count_values|mysql_fetch_object|iconv_get_encoding|move_uploaded_file|filepro_fieldcount|mbereg_search_regs|mb_ereg_search_pos|convert_cyr_string|socket_create_pair|filepro_fieldwidth|socket_iovec_fetch|mb_detect_encoding|html_entity_decode|socket_iovec_alloc|socket_clear_error|connection_aborted|openssl_csr_export|get_included_files|apache_get_version|mysql_free_result|mb_output_handler|mysql_list_fields|openssl_x509_read|filepro_fieldname|filepro_fieldtype|pg_clientencoding|socket_last_error|socket_iovec_free|xml_parser_create|openssl_x509_free|get_resource_type|file_get_contents|mysql_list_tables|pg_unescape_bytea|wddx_packet_start|connection_status|cal_days_in_month|mysql_fetch_array|mysql_fetch_assoc|mb_regex_encoding|socket_set_option|mbereg_search_pos|session_save_path|socket_get_option|ob_implicit_flush|socket_get_status|mysql_fetch_field|aggregate_methods|apache_lookup_uri|openssl_pkey_free|set_error_handler|get_class_methods|mysql_field_table|ignore_user_abort|mysql_field_flags|is_uploaded_file|array_key_exists|get_current_user|ob_iconv_handler|openssl_pkey_new|ob_list_handlers|get_defined_vars|openssl_csr_sign|mysql_table_name|array_diff_assoc|call_user_method|openssl_free_key|pg_affected_rows|aggregation_info|socket_set_block|extension_loaded|mysql_field_seek|mysql_field_name|disk_total_space|get_include_path|mysql_field_type|mysql_fieldtable|mysql_fieldflags|memory_get_usage|mbregex_encoding|session_register|i18n_http_output|set_include_path|wddx_deserialize|htmlspecialchars|highlight_string|mb_eregi_replace|pg_result_status|socket_iovec_add|pg_field_is_null|mysql_listtables|mysql_num_fields|pg_escape_string|socket_iovec_set|get_parent_class|mysql_listfields|filepro_retrieve|filepro_rowcount|xml_error_string|mysql_freeresult|getprotobynumber|mb_detect_order|mb_ereg_replace|mysql_thread_id|mysql_select_db|function_exists|mysql_tablename|i18n_http_input|mysql_numfields|mysql_field_len|mysql_fetch_row|mysql_insert_id|mb_substr_count|mysql_fieldname|mysql_data_seek|mysql_create_db|get_object_vars|mberegi_replace|mysql_fieldtype|mb_convert_case|mb_convert_kana|pg_field_prtlen|socket_strerror|posix_getrlimit|pg_fetch_result|session_destroy|pg_escape_bytea|error_reporting|socket_shutdown|debug_zval_dump|create_function|set_file_buffer|pg_result_error|debug_backtrace|socket_recvfrom|pg_errormessage|pg_fetch_object|array_intersect|pg_cancel_query|disk_free_space|ftp_ssl_connect|ob_get_contents|posix_getgroups|openssl_csr_new|array_multisort|version_compare|xml_parser_free|wddx_packet_end|ftp_nb_continue|read_exif_data|session_decode|posix_getgrnam|posix_getlogin|preg_match_all|mysql_db_query|mysql_createdb|posix_getpwuid|posix_strerror|highlight_file|socket_recvmsg|mb_ereg_search|str_word_count|bindtextdomain|assert_options|substr_replace|zend_logo_guid|xml_set_object|is_subclass_of|mb_http_output|socket_sendmsg|socket_connect|shm_remove_var|set_time_limit|clearstatcache|call_user_func|posix_getgrgid|mbereg_replace|session_encode|posix_getpwnam|pg_lo_read_all|getprotobyname|pg_result_seek|exif_read_data|pg_fetch_assoc|exif_imagetype|pg_last_notice|mysql_list_dbs|pg_fieldprtlen|mysql_num_rows|pg_free_result|gethostbynamel|mysql_pconnect|pg_fieldisnull|pg_fetch_array|exif_thumbnail|get_class_vars|mysql_fieldlen|openssl_verify|ftp_get_option|parse_ini_file|escapeshellcmd|escapeshellarg|ftp_set_option|mysql_selectdb|socket_sendto|mysql_numrows|socket_setopt|ob_get_status|ob_get_length|yp_err_string|mb_strimwidth|socket_select|mb_http_input|socket_writev|wddx_add_vars|mb_strtolower|pg_field_name|trigger_error|mb_ereg_match|strnatcasecmp|stripcslashes|pg_field_size|token_get_all|jdtogregorian|stream_select|pg_field_type|pg_freeresult|pg_send_query|mysql_connect|number_format|mt_getrandmax|posix_setegid|posix_seteuid|method_exists|posix_getppid|mysql_db_name|posix_ctermid|posix_getegid|php_sapi_name|php_logo_guid|posix_getpgrp|posix_getpgid|posix_setpgid|posix_ttyname|socket_create|mysql_listdbs|posix_geteuid|socket_getopt|mbereg_search|socket_listen|socket_accept|pg_get_notify|pg_last_error|pg_num_fields|session_start|session_unset|pg_get_result|pg_getlastoid|mb_strtoupper|mysql_drop_db|eregi_replace|gregoriantojd|getallheaders|gethostbyname|getservbyport|getservbyname|base64_decode|get_meta_tags|base64_encode|array_reverse|array_unshift|apache_setenv|diskfreespace|func_num_args|func_get_args|gethostbyaddr|is_executable|preg_replace|mb_parse_str|posix_setgid|posix_mkfifo|posix_setuid|posix_setsid|stripslashes|shmop_delete|substr_count|mb_send_mail|mysql_result|openssl_open|dba_firstkey|base_convert|ob_end_clean|ob_end_flush|dba_handlers|bzdecompress|socket_readv|func_get_arg|socket_close|class_exists|similar_text|headers_sent|ob_get_clean|socket_write|ob_gzhandler|rawurlencode|openssl_seal|mbereg_match|ob_get_level|i18n_convert|dba_optimize|ob_get_flush|htmlentities|gzuncompress|rawurldecode|posix_getsid|pg_fetch_all|array_filter|pg_loreadall|pg_fetch_row|pg_meta_data|pg_numfields|pg_copy_from|is_writeable|openssl_sign|pg_lo_create|gettimeofday|pg_fieldtype|pg_lo_unlink|pg_fieldsize|pg_fieldname|pg_lo_import|pg_field_num|zend_version|pg_lo_export|ctype_xdigit|session_name|array_values|posix_getcwd|array_unique|posix_getgid|posix_getpid|posix_isatty|posix_getuid|money_format|mysql_dbname|array_splice|exif_tagname|array_reduce|pg_cmdtuples|mysql_dropdb|array_search|getimagesize|ereg_replace|get_browser|ctype_digit|pg_last_oid|ctype_space|mysql_query|ctype_punct|ctype_print|ctype_graph|ctype_lower|get_cfg_var|posix_uname|pg_num_rows|pg_lounlink|pg_end_copy|pg_pconnect|jddayofweek|pg_put_line|pg_loimport|pg_loexport|pg_fieldnum|pg_lo_close|pg_lo_write|pg_locreate|file_exists|easter_days|posix_errno|ftp_systype|dba_replace|dba_nextkey|nl_langinfo|sem_release|sem_acquire|ftp_rawlist|ftp_nb_fput|ftp_connect|easter_date|deaggregate|posix_times|ftp_nb_fget|natcasesort|ctype_upper|sql_regcase|socket_send|str_replace|is_writable|is_resource|str_shuffle|cal_from_jd|socket_recv|show_source|shmop_write|socket_bind|chunk_split|socket_read|is_readable|ini_get_all|mb_get_info|utf8_decode|is_callable|mb_language|unserialize|utf8_encode|strncasecmp|ini_restore|array_shift|mb_strwidth|array_slice|is_infinite|mysql_close|array_merge|count_chars|ctype_alnum|mysql_error|shmop_close|shm_get_var|addcslashes|shm_put_var|apache_note|jdmonthname|ctype_cntrl|levenshtein|ctype_alpha|mysql_errno|array_chunk|ezmlm_hash|pg_lo_open|phpversion|phpcredits|pg_get_pid|pfsockopen|var_export|jdtofrench|user_error|posix_kill|jdtojewish|pg_untrace|jdtojulian|array_walk|frenchtojd|is_integer|pg_lo_tell|token_name|pg_numrows|pg_options|pg_copy_to|pg_lowrite|array_keys|array_diff|array_fill|localeconv|array_flip|pg_loclose|array_push|jewishtojd|pg_connect|pg_lo_seek|is_numeric|array_rand|juliantojd|key_exists|addslashes|pg_convert|pg_lo_read|dcngettext|gzpassthru|readgzfile|dba_exists|str_repeat|shm_detach|strcasecmp|dba_delete|ftp_rename|getlastmod|bzcompress|ftp_delete|shmop_read|dba_insert|mysql_stat|shmop_size|sem_remove|shmop_open|shm_remove|checkdnsrr|mysql_ping|strip_tags|session_id|preg_match|mb_strrpos|shell_exec|strtolower|gmstrftime|gzcompress|textdomain|strtoupper|ftp_nb_get|shm_attach|getrandmax|preg_quote|preg_split|proc_close|mysql_info|getmyinode|ftp_nb_put|pg_loread|pg_loopen|setcookie|sha1_file|aggregate|yp_master|dba_fetch|dba_close|setlocale|rewinddir|dcgettext|doubleval|array_sum|php_uname|proc_open|dngettext|strnatcmp|urldecode|urlencode|strtotime|preg_grep|pg_update|quotemeta|checkdate|array_pop|array_pad|array_map|cal_to_jd|str_rot13|error_log|dba_popen|xml_parse|pg_result|serialize|pg_select|is_scalar|is_string|is_object|iptcembed|ftp_chdir|fsockopen|fpassthru|metaphone|lcg_value|mb_substr|parse_url|parse_str|ini_alter|localtime|iptcparse|ftp_close|get_class|mb_strpos|mb_strcut|getrusage|gzinflate|gzdeflate|is_double|mb_strlen|ftp_mkdir|ftp_login|microtime|is_finite|ftruncate|ftp_rmdir|mbstrrpos|ftp_nlist|pg_delete|pg_insert|fileowner|pg_dbname|fileatime|fileperms|filegroup|filemtime|fileinode|filectime|ftp_size|readfile|in_array|readlink|dba_open|ftp_site|realpath|ftp_quit|ftp_pasv|ftp_mdtm|ftp_fput|dba_sync|is_float|yp_first|vsprintf|ob_flush|yp_order|ob_clean|mbstrlen|constant|is_array|gzencode|mb_split|gzrewind|bzerrstr|mbstrcut|mt_srand|unixtojd|gmmktime|closelog|ngettext|mb_eregi|var_dump|ftp_fget|mbsubstr|md5_file|closedir|getmyuid|getmypid|getmygid|dba_list|ob_start|filesize|pathinfo|yp_match|cal_info|wordwrap|strftime|floatval|linkinfo|ftp_exec|passthru|filetype|basename|overload|dgettext|pg_trace|jdtounix|mbstrpos|ftp_cdup|yp_errno|pg_close|pg_query|bzerror|ucwords|bzerrno|mberegi|gzclose|shuffle|bzwrite|gettype|bzflush|gettext|ini_get|str_pad|current|pg_host|compact|soundex|extract|gzgetss|bin2hex|hebrevc|stripos|ucfirst|mbsplit|pg_port|is_bool|gzwrite|bzclose|mt_rand|strrchr|settype|sprintf|fnmatch|strncmp|yp_next|opendir|symlink|openlog|rad2deg|ini_set|readdir|deg2rad|defined|tmpfile|strcoll|is_link|strcspn|bcscale|ftp_get|ftp_put|is_long|print_r|mb_ereg|ftp_pwd|phpinfo|pg_exec|natsort|sem_get|long2ip|is_file|explode|ip2long|getmxrr|pg_ping|getdate|implode|virtual|fgetcsv|strrpos|dirname|is_null|is_real|filepro|vprintf|stristr|tempnam|bzopen|bzread|strcmp|bccomp|strspn|strstr|strrev|mbereg|intval|strlen|strpos|strtok|bcsqrt|hexdec|spliti|assert|substr|strval|strchr|static|sscanf|bindec|fwrite|arsort|getcwd|rewind|rename|octdec|getenv|syslog|global|yp_cat|usleep|getopt|putenv|printf|fscanf|yp_all|krsort|pg_tty|is_nan|define|is_int|decbin|dechex|decoct|gmdate|is_dir|uksort|sizeof|uniqid|pclose|mktime|uasort|system|hebrev|fclose|fflush|gztell|header|gzgets|fgetss|gzgetc|gzfile|gzseek|unpack|unlink|gzopen|gzread|gzputs|ksort|acosh|usort|umask|asinh|touch|asort|unset|strtr|log10|lstat|ltrim|log1p|sleep|round|rsort|rmdir|reset|range|rtrim|crypt|mysql|expm1|count|gzeof|crc32|ftell|fstat|floor|flush|flock|fgets|fgetc|eregi|popen|fseek|fread|fputs|fopen|mkdir|nl2br|bcpow|bcmul|chgrp|bcmod|bcsub|chdir|srand|hypot|iconv|split|bcdiv|chmod|atanh|atan2|chown|bcadd|mail|each|fmod|exec|acos|feof|pack|file|ereg|join|link|list|sqrt|eval|exit|stat|sha1|is_a|tanh|glob|ftok|date|asin|time|trim|copy|cosh|sinh|chop|atan|next|sort|rand|prev|ceil|pow|dir|sin|exp|min|chr|die|cos|abs|end|tan|log|md5|max|key|pos|ord|dl|pi|abstract|final)\b',
            1 => QBB_CASELESS
        ),
    ),
    'oo_splitters' => array (
        0 => '(?:\:\:|\->)',
        1 => QBB_NONE
    ),
    'links' => array (
        0 => 'http://www.php.net/%s',
        1 => '',
        2 => '',
        3 => 'http://www.php.net/%s',
    ),
    'styles' => array (
        'comments' => array (
            0 => 'color: #ff8000;',
            1 => 'color: #ff8000;'
        ),
        'strings' => array (
            0 => 'color: #dd0000;'
        ),
        'regexes' => array (
            0 => 'color: #0000bb;',
            1 => 'color: #0000bb;',
        ),
        'symbols' => array (
            0 => 'color: #007700;'
        ),
        'keywords' => array (
            0 => 'color: #007700;',
            1 => 'color: #007700;',
            2 => 'color: #0000bb;',
            3 => 'color: #0000bb;',
        ),
        'oo_splitters' => array (
            0 => 'color: #007700;'
        ),
        'oo_methods' => array (
            0 => 'color: #0000bb;'
        ),
        'numbers' => 'color: #0000bb;',
        'overall' => 'color: #0000bb;'
    )
);
