<?php
if (!current_user_can('edit_user', $user_id)) {
    return false;
}
if (isset($_POST['BH_Subsk_type_subs'])) {
    update_user_meta(
        $user_id,
        'BH_Subsk_type_subs',
        $_POST['BH_Subsk_type_subs']
    );

    if (($_POST['BH_Subsk_type_subs_past'] == -1) || isset($_POST['BH_Subsk_renew'])) {
        $date = new DateTime();
        update_user_meta(
            $user_id,
            'BH_Subsk_date_up',
            $date->format('Y-m-d')
        );
    }
    return true;
}
return false;
