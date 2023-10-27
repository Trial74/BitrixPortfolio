<script type="text/javascript" src="/bitrix/modules/extremelook/js/partnernew.js?<?=time()?>"></script>
<script type="text/javascript" src="/bitrix/modules/extremelook/js/unloading_part_1c.js?<?=time()?>"></script>
<script type="text/javascript" src="/bitrix/modules/extremelook/js/push.js?<?=time()?>"></script>
<script type="text/javascript" src="/bitrix/modules/extremelook/js/telegram_bot.js?<?=time()?>"></script>
<script type="text/javascript" src="/bitrix/modules/extremelook/js/mobileapp_users.js?<?=time()?>"></script>
<style>
    /**PUSH START**/
    textarea.arrusers{
        width: 100%;
        resize: none;
        color: #7b66fe;
        font-weight: bold;
        font-size: 18px;
        opacity: 1;
    }
    textarea.message-push{
        resize: none;
    }
    .label-ex{
        cursor: pointer;
        font-weight: bold !important;
    }
    .label-count,
    .textarea-count{
        padding-left: 15px;
        font-weight: bold;
        font-size: 17px;
        color: #04c409;
    }
    .label-warning{
        color: #e8a100 !important;
    }
    .label-warningg{
        color: #ff0200 !important;
    }
    .display-none{
        display: none;
    }
    /**PUSH END**/

    .none{
        display: none;
    }
    .adm-workarea .adm-detail-content-cell-r .adm-input-wrap{
        width: 250px;
    }
    .adm-extreme .adm-main-menu-item-icon, .adm-extreme.adm-main-menu-item-active .adm-main-menu-item-icon {
        height: 47px;
        background: url('/bitrix/themes/.default/icons/extremelook/extreme_admin_menu_icon.png') no-repeat 50% 0;
        background-size: 40px auto;
    }
    .adm-extreme:hover .adm-main-menu-item-icon{
        background-position: 50% 106%;
    }
    .link_name_g{
        color: green;
        font-weight:bold;
    }
    .link_name_y{
        color: #b5b51d;
        font-weight: bold;
    }
    .link_name_r{
        color: red;
        font-weight: bold;
    }
    #table_result > tbody#list_new_part > tr:nth-child(odd){
        background-color: #fff;
    }
    #table_result > tbody#list_new_part > tr:nth-child(even){
        background-color: #f5f9f9;
    }
    #table_result > tbody#list_new_part > tr > td{
        padding: 15px;
    }
    #table_result > tbody#list_new_part > tr.lnp_error{
        background: #ff000042;
    }
    #table_result > tbody#list_new_part > tr > td:nth-child(1){
        text-align: center;
    }
    #table_result > tbody#list_new_part > tr > td > .adm-sub-submenu-block > .adm-submenu-item-name,
    #table_result > tbody#list_new_part > tr > td > .adm-sub-submenu-block > .adm-submenu-item-name > .adm-submenu-item-arrow{
        min-width: unset;
        height: unset;
        width: unset;
    }
    #table_result > tbody#list_new_part > tr > td > .adm-sub-submenu-block > .adm-submenu-item-name > .adm-submenu-item-arrow > .adm-submenu-item-arrow-icon{
        right: -12px;
        top: -7px;
    }
    #file_log_block > pre{
        height: 700px;
        width: 1450px;
        overflow: scroll;
    }
    #file_log_block_import > pre{
        height: 500px;
        overflow: scroll;
    }
    /* ------------------------------------- */

    .ex_table-contragents {
        border-collapse: collapse;
        border-left: 3px solid #7b66fe;
        border-right: 3px solid #7b66fe;
        border-bottom: 3px solid #7b66fe;
        font-family: "Lucida Grande", sans-serif;
        width: 75%;
    }
    .ex_table-contragents > thead{
        background: white;
        font-size: 16px;
        font-weight: 700;
    }
    .ex_table-contragents caption {
        background: #7b66fe;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 10px;
        box-shadow: 0 2px  4px 0 rgba(0,0,0,.3);
        color: white;
        font-family: "Roboto Slab",serif;
        font-style: normal;
        font-size: 26px;
        text-align: center;
        margin: 0;
    }
    .ex_table-contragents td, .ex_table-contragents th {
        padding: 10px;
    }
    .ex_table-contragents th {
        text-align: left;
        font-size: 18px;
    }
    .ex_table-contragents tr:nth-child(2n) {
        background: #E5E5E5;
    }
    .ex_table-contragents td:last-of-type {
        text-align: center;
    }
    .ex_table-contragents .ex_c-err{
        background: #b15b49;
        color: white;
        font-weight: 600;
    }
    #cashResult{
        display: none;
    }

    /**TELEGRAM BOT SCREENSHOT**/
    .tbot_screen-form-date{
        display: flex;
        align-items: center;
        justify-content: flex-start;
        grid-gap: 50px;
        margin-bottom: 50px;
    }
    .tbot_screen-tag{
        font-size: 10px !important;
    }
    .tbot_screen-button-date-count{
        margin-left: 10px !important;
    }
</style>