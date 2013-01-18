<?php
/*
 Plugin Name: WooCommerce Add Fields
 Plugin URI: http://felipematos.com/loja
 Description: Adiciona o gateway de pagamento do Pagamento Digital no WooCommerce
 Version: 1.0
 Author: Felipe Matos <chucky_ath@yahoo.com.br>
 Author URI: http://felipematos.com
 License: GPLv2
 Requires at least: 3.3
 Tested up to: 3.4.1
 */
  
  
	add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
	add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

	function my_show_extra_profile_fields( $user ) { 
		?>
		<h3>Extra profile information</h3>
		<table class="form-table">
			<tr>
				<th><label for="twitter">Twitter</label></th>
				<td>
					<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" />
					<br/>
					<span class="description">Please enter your Twitter username.</span>
				</td>
			</tr>
			<tr>
				<th><label for="billing_cpf">CPF</label></th>
				<td>
					<input type="text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( get_the_author_meta( 'billing_cpf', $user->ID ) ); ?>" class="regular-text" />
					<br/>
					<span class="description">Please enter your CPF.</span>
				</td>
			</tr>
		</table>
		<?php 
	}
  
	add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
	add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

	function my_save_extra_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) return false;
		
		/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
		update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
		update_usermeta( $user_id, 'billing_cpf', $_POST['billing_cpf'] );
	}

?>