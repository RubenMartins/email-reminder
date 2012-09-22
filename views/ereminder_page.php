<?php
$fields = $data['fields'];
$messages = $data['messages'];
?>
<div class="wrap ereminder">
	<?php screen_icon('edit-comments'); ?>
	<h2 class="page-title">Create Email Reminder</h2>
	
	<?php if( !empty( $messages ) ) : ?>
		<?php if( !empty( $messages['error'] ) ): ?>
			<div class="error message">
			<?php foreach( $messages['error'] as $message ): ?>
				<?php echo $message; ?><br />
			<?php endforeach; ?>
			</div>
		<?php elseif( !empty( $messages['success'] ) ): ?>
			<div class="updated message">
			<?php foreach( $messages['success'] as $message ): ?>
				<?php echo $message; ?><br />
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	
	<form method="POST" action="">
		<p class="field">
			<label for="pd-reminder-content">Enter your reminder</label><br />
			<input type="text" size="40" name="pder[reminder]" id="pd-reminder-content" placeholder="Send Dad a birthday card" value="<?php echo $fields['reminder']; ?>" title="Type your reminder here." />
		</p>
		<p class="field">
			<label for="pd-reminder-email" title="Leave this field blank to send email to yourself">Email address to send reminder to</label><br />
			<input type="email" size="40" name="pder[email]" id="pd-reminder-email" placeholder="youemailaddress@email.com" title="Where to email the reminder to. Leave this field blank to send email to yourself" value="<?php echo $fields['email']; ?>" />
		</p>
		<p class="field">
			<label for="pd-reminder-date">When to send reminder</label><br />
			<input type="text" size="20" name="pder[date]" id="pd-reminder-date" value="<?php echo $fields['date']; ?>" placeholder="YYYY-MM-DD" title="Set the date for the reminder (Format: YYYY-MM-DD)" />
			<input type="text" size="15" name="pder[time]" id="pd-reminder-time" value="<?php echo $fields['time']; ?>" placeholder="<?php echo date( 'H:00', strtotime( current_time('mysql',0) ) ); ?>" title="Set the time for the reminder. Format: HH:MM. Example: 15:30 or 3:30pm" />
			<br />
			<span class="regular server-time description"><strong>Current Time:</strong> <code><?php echo  date( 'F j, Y h:i A', strtotime( current_time('mysql') ) ); ?></code> as set in the <a href="<?php echo admin_url('options-general.php'); ?>">Timezone settings</a></span>
		</p>
		<input type="submit" value="Set Reminder" class="button-primary" />
		<input type="hidden" name="pder-action" value="submit" />
		<?php wp_nonce_field( 'pder-submit-reminder', 'pder-submit-reminder-nonce' ); ?>
	</form>
	
	<div class="reminder-list">
		<h3>Scheduled Reminders</h3>
		<?php
			global $wpdb;
			
			$current_time = current_time('mysql') + 60;
			
			$ereminder_array = $wpdb->get_results( $wpdb->prepare("
							SELECT *
							FROM {$wpdb->posts}
							WHERE post_date <= '{$current_time}'
								AND post_type = 'ereminder'
								
							ORDER BY post_date ASC
							") );
		?>
		
		<table class="widefat">
			<thead>
				<tr>
					<th class="content">Reminder</th>
					<th class="date">Send Reminder on</th>
					<th class="email">Send To</th>
					<?php //<th class="status">Status</th> ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="content">Reminder</th>
					<th class="date">Send Reminder on</th>
					<th class="email">Send To</th>
					<?php //<th class="status">Status</th> ?>
				</tr>
			</tfoot>
			<tbody>
				<?php if( empty( $ereminder_array ) ) : ?>
					<tr><td colspan="4">There are currently no scheduled reminders.</td></t>
				<?php else : ?>
					<?php foreach( $ereminder_array as $ereminder ): ?>
						<tr>
							<td class="content"><?php echo $ereminder->post_content; ?></td>
							<td class="date"><?php echo date( 'l, F j, Y @ g:i a', strtotime( $ereminder->post_date ) ); ?></td>
							<td class="email"><?php echo $ereminder->post_excerpt; ?></td>
							<?php //<td class="status"><?php echo $ereminder->post_status == 'draft' ? 'Scheduled' : 'Sent'; </td> ?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	
</div>