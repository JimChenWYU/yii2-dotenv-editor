<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */
    'overview'  => '总览',
    'addnew'    => '添加',
    'backups'   => '备份',
    'upload'    => '上传',

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */
    'title'     => '.env-Editor',

    /*
    |--------------------------------------------------------------------------
    | View overview
    |--------------------------------------------------------------------------
    */
    'overview_title'                => '你当前 .env 文件',
    'overview_text'                 => '查看当前.env文件内容。<br>点击 <strong>加载</strong> 查看内容。',
    'overview_button'               => '加载',
    'overview_table_key'            => 'Key',
    'overview_table_value'          => 'Value',
    'overview_table_options'        => '选项',
    'overview_table_popover_edit'   => '编辑条目',
    'overview_table_popover_delete' => '删除条目',
    'overview_delete_modal_text'    => '你确定要删除此条目吗？',
    'overview_delete_modal_yes'     => '是的，删除条目',
    'overview_delete_modal_no'      => '不，退出',
    'overview_edit_modal_title'     => '编辑条目',
    'overview_edit_modal_save'      => '保存',
    'overview_edit_modal_quit'      => '关于',
    'overview_edit_modal_value'     => '新建值',
    'overview_edit_modal_key'       => 'Key',

    /*
    |--------------------------------------------------------------------------
    | View add new
    |--------------------------------------------------------------------------
    */
    'addnew_title'      => '添加新的键值对',
    'addnew_text'       => '为你<strong>当前</strong>.env文件新建键值对。<br>请确保已经进行备份',
    'addnew_label_key'  => 'Key',
    'addnew_label_value'=> 'Value',
    'addnew_button_add' => '添加',
    'addnew_exists_warning' => '键值对已存在',

    /*
    |--------------------------------------------------------------------------
    | View backup
    |--------------------------------------------------------------------------
    */
    'backup_title_one'              => '保存你当前.env文件',
    'backup_create'                 => '创建备份',
    'backup_download'               => '下载当前.env',
    'backup_title_two'              => '你当前生效的备份',
    'backup_restore_text'           => '回滚到当前你的一个备份',
    'backup_restore_warning'        => '该操作会覆写你当前活跃的.env文件！请确保已备份当前.env文件！',
    'backup_no_backups'             => '当前没有任何.env备份。',
    'backup_table_nr'               => 'nr',
    'backup_table_date'             => '日期',
    'backup_table_options'          => '选项',
    'backup_table_options_show'     => '展示备份内容',
    'backup_table_options_restore'  => '回滚到此版本',
    'backup_table_options_download' => '下载此版本',
    'backup_table_options_delete'   => '删除此版本',
    'backup_modal_title'            => '备份内容',
    'backup_modal_key'              => 'Key',
    'backup_modal_value'            => 'Value',
    'backup_modal_close'            => '关闭',
    'backup_modal_restore'          => '回滚',
    'backup_modal_delete'           => '删除',

    /*
    |--------------------------------------------------------------------------
    | View upload
    |--------------------------------------------------------------------------
    */
    'upload_title'  => '上传一个备份',
    'upload_text'   => '从你的电脑上传一个备份。',
    'upload_warning'=> '<strong>警告:</strong> 这将覆写你当前活跃的.env文件，请确保上传前已备份当前.env文件。',
    'upload_label'  => '选择一个文件',
    'upload_button' => '上传',

    /*
    |--------------------------------------------------------------------------
    | Messages from View
    |--------------------------------------------------------------------------
    */
    'new_entry_added'   => '新的键值对已添加到你的.env文件中！',
    'entry_edited'      => '键值对编辑成功！',
    'entry_deleted'     => '键值对删除成功！',
    'delete_entry'      => '删除一个条目',
    'backup_created'    => '创建新的备份成功！',
    'backup_restored'   => '回滚备份成功！',
    'warning_operating' => '你确定要这样处理吗？',

    /*
    |--------------------------------------------------------------------------
    | Messages from Controller
    |--------------------------------------------------------------------------
    */
    'controller_backup_deleted' => '删除备份成功！',
    'controller_backup_created' => '创建备份成功！'
];
