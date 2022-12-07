<?php
$currentUser = wp_get_current_user();

if (in_array('subscriber', $user->roles) && in_array('administrator', $currentUser->roles)) :
    $type_subs = self::get_type_sub($user->data->ID);
    $dateup = self::get_date_up($user->data->ID); //get_user_meta($user->data->ID, 'BH_Subsk_date_up');
    $timeleft = self::get_time_left($user->data->ID);

?> <h3> Tipo de suscripcion </h3>
    <script>
        console.log(<?= json_encode($type_subs) ?>)
    </script>
    <table class="form-table">
        <tr>
            <th>
                <label for="BH_Subsk_type_subs">Suscripcion: </label>
            </th>
            <td>
                <input type="hidden" id="BH_Subsk_type_subs_past" name="BH_Subsk_type_subs_past" value=<?= $type_subs ?> />
                <select id="BH_Subsk_type_subs" name="BH_Subsk_type_subs" class="regular-text">
                    <option value="-1">Sin suscripcion</option>
                    <?php foreach (self::get_all_sub() as $sub) : ?>
                        <?php if ($sub->post_status == 'publish') : ?>
                            <option value="<?= $sub->ID ?>" <?= ($type_subs == $sub->ID) ? 'selected' : ''; ?>><?= $sub->post_name ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <p class="description">algo aqui sobre las descripciones</p>
            </td>
        </tr>
        <tr>
            <th>
                <label for="BH_Subsk_date_up">Fecha de la suscripcion: </label>
            </th>
            <td>
                <input type="date" id="BH_Subsk_date_up" name="BH_Subsk_date_up" disabled value="<?= $dateup ?>" />
            </td>
        </tr>
        <tr>
            <th>
                <label for="BH_Subsk_time_left">Tiempo restante de la suscripcion: </label>
            </th>
            <td>
                <input type="number" id="BH_Subsk_time_left" name="BH_Subsk_time_left" value="<?= $timeleft ?>" disabled />
                Dias
            </td>
        </tr>
        <tr>
            <th>
                <label for="BH_Subsk_renew">Renovacion: </label>
            </th>
            <td>
                <input type="checkbox" name="BH_Subsk_renew" />
            </td>
        </tr>
    </table>
<?php
endif;
