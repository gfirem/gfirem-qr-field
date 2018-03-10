<tr>
    <td><label for="size_<?php echo esc_attr( $field['id'] ) ?>"><?php _e( "Size","gfirem_qr-locale" ) ?></label></td>
    <td>
        <label for="size_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php _e( "Size for QR Code, by default is '1'. " ,"gfirem_qr-locale"); ?></label>

        <select name="field_options[size_<?php echo esc_attr( $field['id'] ) ?>]" id="size_<?php echo esc_attr( $field['id'] ) ?>">
            <option <?php selected( esc_attr( $field['size'] ), "1" ) ?> value="1">1</option>
            <option <?php selected( esc_attr( $field['size'] ), "2" ) ?> value="2">2</option>
            <option <?php selected( esc_attr( $field['size'] ), "3" ) ?> value="3">3</option>
            <option <?php selected( esc_attr( $field['size'] ), "4" ) ?> value="4">4</option>
        </select>
    </td>
</tr>

<tr>
    <td><label for="ECC_<?php echo esc_attr( $field['id'] ) ?>"><?php _e( "ECC","gfirem_qr-locale" ) ?></label></td>
    <td>
        <label for="ECC_<?php echo esc_attr( $field['id'] ) ?>" class="howto"><?php  _e( "Error Correction Capability, by default is 'Level L (7%)'. " ,"gfirem_qr-locale"); ?></label>

        <select name="field_options[ECC_<?php echo esc_attr( $field['id'] ) ?>]" id="ECC_<?php echo esc_attr( $field['id'] ) ?>">
            <option <?php selected( esc_attr( $field['ECC'] ), "1" ) ?> value="1">Level L (7%)</option>
            <option <?php selected( esc_attr( $field['ECC'] ), "2" ) ?> value="2">Level M (15%)</option>
            <option <?php selected( esc_attr( $field['ECC'] ), "3" ) ?> value="3">Level Q (25%)</option>
            <option <?php selected( esc_attr( $field['ECC'] ), "4" ) ?> value="4">Level H (30%)</option>
        </select>
    </td>
</tr>

