<?php
/*
Plugin Name: SZ Admin form
Description: 管理者用フォームデータ管理、返信メールの編集
Version: 1.2
Plugin URI: 
Author: szk
Author URI: http://szc.cu.cc
License: GPL2
*/

// 管理メニューに追加するフック
add_action('admin_menu', 'mt_add_pages');

// 上のフックに対するaction関数
function mt_add_pages() {
    // 設定メニュー下にサブメニューを追加:
    //add_options_page('Test Options', 'Test Options', 8, 'testoptions', 'mt_options_page');

    // 管理（ツール）メニュー下にサブメニューを追加
    //add_management_page('Test Manage', 'Test Manage', 8, 'testmanage', 'mt_manage_page');

    // 新しいトップレベルメニューを追加:
    add_menu_page('管理者設定', '会員管理', 'manage_options', 'sz-form', 'mt_toplevel_page', '', 26);
    
    // カスタムのトップレベルメニューにサブメニューを追加:
    add_submenu_page('sz-form', '管理者設定', '管理者設定', 'manage_options', 'sz−form', 'mt_toplevel_page');

    // カスタムのトップレベルメニューにサブメニューを追加:
    add_submenu_page('sz-form', '会員データ', '会員データ', 'manage_options', 'userdata', 'mt_sublevel_page');

    // カスタムのトップレベルメニューに二つ目のサブメニューを追加:
    //add_submenu_page('sz-form', '出店者募集', '出店者募集', 'manage_options', 'newshop', 'mt_sublevel_page2');
    
    // カスタムのトップレベルメニューに二つ目のサブメニューを追加:
    add_submenu_page('sz-form', 'お問い合わせ', 'お問い合わせ', 'manage_options', 'contact', 'mt_sublevel_page3');
    
    remove_submenu_page( 'sz-form', 'sz-form' );
}



// mt_toplevel_page()は カスタムのトップレベルメニューのコンテンツを表示
function mt_toplevel_page() {
    //echo "<h2>Test Toplevel</h2>";
    //echo __FILE__;
    include_once('createTableDB.php');
}

// mt_sublevel_page() はカスタムのトップレベルメニューの
// 最初のサブメニューのコンテンツを表示
function mt_sublevel_page() {
    include_once('showTableController.php');
}

// mt_sublevel_page2() はカスタムのトップレベルメニューの
// 二番目のサブメニューを表示
function mt_sublevel_page2() {
    include_once('showTableController.php');
}

function mt_sublevel_page3() {
    include_once('showTableController.php');
}

