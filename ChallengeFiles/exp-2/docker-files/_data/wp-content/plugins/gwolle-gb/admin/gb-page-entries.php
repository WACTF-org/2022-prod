<?php
/*
 * Displays the guestbook entries in a list.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Admin page with lists of entries.
 */
function gwolle_gb_page_entries() {

	if ( ! current_user_can('moderate_comments') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	gwolle_gb_admin_enqueue();

	$show = 'all';
	if ( isset($_GET['show']) && in_array($_GET['show'], array( 'checked', 'unchecked', 'spam', 'trash', 'user' ), true ) ) {
		$show = $_GET['show'];
	}

	if ( isset($_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'entries' ) {
		gwolle_gb_page_entries_update();
	}
	$gwolle_gb_messages = gwolle_gb_get_messages();
	$gwolle_gb_errors = gwolle_gb_get_errors();
	$messageclass = '';
	if ( $gwolle_gb_errors ) {
		$messageclass = 'error';
	}

	// Get entry counts
	$count = array();
	$count['checked'] = gwolle_gb_get_entry_count(array(
		'checked' => 'checked',
		'trash'   => 'notrash',
		'spam'    => 'nospam',
	));
	$count['unchecked'] = gwolle_gb_get_entry_count(array(
		'checked' => 'unchecked',
		'trash'   => 'notrash',
		'spam'    => 'nospam',
	));
	$count['spam']  = gwolle_gb_get_entry_count(array( 'spam' => 'spam'  ));
	$count['trash'] = gwolle_gb_get_entry_count(array( 'trash'=> 'trash' ));
	$count['all']   = gwolle_gb_get_entry_count(array( 'all'  => 'all'   ));
	$count['user']  = 0; // dummy data, there is no pagination on user tab.

	$num_entries = (int) get_option('gwolle_gb-entries_per_page', 20);

	$page_num = 1;
	if ( isset($_GET['pageNum']) && (int) $_GET['pageNum'] > 0) {
		$page_num = (int) $_GET['pageNum'];
	}

	$pages_total = (int) ceil( $count["$show"] / $num_entries );
	if ($page_num > $pages_total) {
		$page_num = 1; // page does not exist, return to first page.
	}

	// Calculate query.
	if ($page_num === 1 && $count["$show"] > 0) {
		$offset = 0;
	} else if ($count["$show"] === 0) {
		$offset = 0;
	} else {
		$offset = ( $page_num - 1 ) * $num_entries;
	}

	$book_id = 0;
	if ( isset( $_GET['book_id'] ) ) {
		$book_id = (int) $_GET['book_id'];
	}

	// Get the entries.
	if ( $show === 'checked' ) {
		$entries = gwolle_gb_get_entries(array(
			'num_entries' => $num_entries,
			'offset'  => $offset,
			'checked' => 'checked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
			'book_id' => $book_id,
		));
		$count_entries = gwolle_gb_get_entry_count(array(
			'checked' => 'checked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
			'book_id' => $book_id,
		));
	} else if ( $show === 'unchecked' ) {
		$entries = gwolle_gb_get_entries(array(
			'num_entries' => $num_entries,
			'offset'  => $offset,
			'checked' => 'unchecked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
			'book_id' => $book_id,
		));
		$count_entries = gwolle_gb_get_entry_count(array(
			'checked' => 'unchecked',
			'trash'   => 'notrash',
			'spam'    => 'nospam',
			'book_id' => $book_id,
		));
	} else if ( $show === 'spam' ) {
		$entries = gwolle_gb_get_entries(array(
			'num_entries' => $num_entries,
			'offset'  => $offset,
			'spam'    => 'spam',
			'book_id' => $book_id,
		));
		$count_entries = gwolle_gb_get_entry_count(array(
			'spam'    => 'spam',
			'book_id' => $book_id,
		));
	} else if ( $show === 'trash' ) {
		$entries = gwolle_gb_get_entries(array(
			'num_entries' => $num_entries,
			'offset'  => $offset,
			'trash'   => 'trash',
			'book_id' => $book_id,
		));
		$count_entries = gwolle_gb_get_entry_count(array(
			'trash'   => 'trash',
			'book_id' => $book_id,
		));
	} else if ( $show === 'user' ) {
		$entries = array();
		if ( isset( $_POST['gwolle_gb_user_id']) && ( (int) $_POST['gwolle_gb_user_id'] > 0 ) ) {
			$user_id = (int) $_POST['gwolle_gb_user_id'];
			$entries = gwolle_gb_get_entries(array(
					'author_id'   => $user_id,
					'num_entries' => -1,
					'all'         => 'all',
				));
		} else if ( isset( $_POST['gwolle_gb_user_email']) && strlen($_POST['gwolle_gb_user_email']) > 0 ) {
			$user_email = sanitize_text_field( $_POST['gwolle_gb_user_email'] );
			$entries = gwolle_gb_get_entries(array(
					'email'       => $user_email,
					'num_entries' => -1,
					'all'         => 'all',
				));
		}
		if ( empty( $entries ) ) {
			$count_entries = 0;
		} else {
			$count_entries = count($entries); // all on one page
		}
	} else {
		$entries = gwolle_gb_get_entries(array(
			'num_entries' => $num_entries,
			'offset'  => $offset,
			'all'     => 'all',
			'book_id' => $book_id,
		));
		$count_entries = gwolle_gb_get_entry_count(array(
			'all'     => 'all',
			'book_id' => $book_id,
		));
	}
	$count_entrypages = (int) ceil( $count_entries / $num_entries );


	if ( empty( $entries ) ) {
		$entries_on_page = 0;
	} else {
		$entries_on_page = count($entries);
	}
	?>

	<div class="wrap gwolle_gb">
		<div id="icon-gwolle-gb"><br /></div>
		<h1><?php esc_html_e('Guestbook entries', 'gwolle-gb'); ?> (Gwolle Guestbook) - v<?php echo GWOLLE_GB_VER; ?></h1>

		<?php
		if ( $gwolle_gb_messages ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible ' . $messageclass . ' ">' .
					$gwolle_gb_messages .
				'</div>';
		} ?>

		<form name="gwolle_gb_entries" id="gwolle_gb_entries" action="#" method="POST" accept-charset="UTF-8">

			<input type="hidden" name="gwolle_gb_page" value="entries" />
			<!-- the following fields give us some information used for processing the mass edit -->
			<input type="hidden" name="pageNum" value="<?php echo esc_attr( $page_num ); ?>">
			<input type="hidden" name="entriesOnThisPage" value="<?php echo esc_attr( $entries_on_page ); ?>">
			<input type="hidden" name="show" value="<?php echo esc_attr( $show ); ?>">

			<?php
			/* Nonce */
			$nonce = wp_create_nonce( 'gwolle_gb_page_entries' );
			echo '<input type="hidden" id="gwolle_gb_wpnonce" name="gwolle_gb_wpnonce" value="' . esc_attr( $nonce ) . '" />';
			?>

			<ul class="subsubsub">
				<li><a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=all' ); ?>" <?php
					if ($show === 'all') { echo 'class="current"'; }
					?>>
					<?php esc_html_e('All', 'gwolle-gb'); ?> <span class="count gwolle_gb_all">(<?php echo $count['all']; ?>)</span></a> |
				</li>
				<li><a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=checked' ); ?>" <?php
					if ($show === 'checked') { echo 'class="current"'; }
					?>>
					<?php esc_html_e('Unlocked', 'gwolle-gb'); ?> <span class="count gwolle_gb_unlocked">(<?php echo $count['checked']; ?>)</span></a> |
				</li>
				<li><a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=unchecked' ); ?>" <?php
					if ($show === 'unchecked') { echo 'class="current"'; }
					?>><?php esc_html_e('New', 'gwolle-gb'); ?> <span class="count gwolle_gb_new">(<?php echo $count['unchecked']; ?>)</span></a> |
				</li>
				<li><a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=spam' ); ?>" <?php
					if ($show === 'spam') { echo 'class="current"'; }
					?>><?php esc_html_e('Spam', 'gwolle-gb'); ?> <span class="count gwolle_gb_spam_">(<?php echo $count['spam']; ?>)</span></a> |
				</li>
				<li><a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=trash' ); ?>" <?php
					if ($show === 'trash') { echo 'class="current"'; }
					?>><?php /* translators: Is in Trashcan */ esc_html_e('In Trash', 'gwolle-gb'); ?> <span class="count gwolle_gb_trash_">(<?php echo $count['trash']; ?>)</span></a> |
				</li>
				<li><a href="<?php echo admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/entries.php&show=user' ); ?>" <?php
					if ($show === 'user') { echo 'class="current"'; }
					?>><?php esc_html_e('Author', 'gwolle-gb'); ?></a>
				</li>
			</ul>

			<div class="tablenav">
				<?php if ( $show === 'user' ) {
					if ( empty( $user_id ) ) { $user_id = ''; }
					if ( empty( $user_email ) ) { $user_email = ''; }
					?>
					<h3><?php esc_html_e('Select one option below, either User ID or Email address', 'gwolle-gb'); ?></h3>
					<p>
						<label for="gwolle_gb_user_id" class="text-info"><?php esc_html_e('User ID', 'gwolle-gb'); ?>:<br />
							<input type="text" name="gwolle_gb_user_id" value="<?php echo esc_attr( $user_id ); ?>" placeholder="<?php esc_html_e('User ID', 'gwolle-gb'); ?>" />
						</label><br />
						<label for="gwolle_gb_user_email" class="text-info"><?php esc_html_e('User Email', 'gwolle-gb'); ?>:<br />
							<input type="text" name="gwolle_gb_user_email" value="<?php echo esc_attr( $user_email ); ?>" placeholder="<?php esc_html_e('User Email', 'gwolle-gb'); ?>" />
						</label><br />
						<input type="submit" name="gb_search_user" id="gb_search_user" class="button button-primary" value="<?php esc_attr_e('Search entries', 'gwolle-gb'); ?>"  />
					</p><?php
				} ?>

				<div class="alignleft actions">
					<?php
					$mass_edit_controls_select = '<select name="massEditAction1">';
					$mass_edit_controls = '<option value="-1" selected="selected">' . esc_html__('Mass edit actions', 'gwolle-gb') . '</option>';
					if ($show === 'trash') {
						$mass_edit_controls .= '
							<option value="untrash">' . esc_html__('Recover from trash', 'gwolle-gb') . '</option>
							<option value="remove">' . esc_html__('Remove permanently', 'gwolle-gb') . '</option>';
					} else {
						if ($show !== 'checked') {
							$mass_edit_controls .= '<option value="check">' . esc_html__('Mark as checked', 'gwolle-gb') . '</option>';
						}
						if ($show !== 'unchecked') {
							$mass_edit_controls .= '<option value="uncheck">' . esc_html__('Mark as not checked', 'gwolle-gb') . '</option>';
						}
						if ($show !== 'spam') {
							$mass_edit_controls .= '<option value="spam">' . esc_html__('Mark as spam', 'gwolle-gb') . '</option>';
						}
						$mass_edit_controls .= '<option value="no-spam">' . esc_html__('Mark as not spam', 'gwolle-gb') . '</option>';
						if ( get_option('gwolle_gb-akismet-active', 'false') === 'true' ) {
							$mass_edit_controls .= '<option value="akismet">' . esc_html__('Check with Akismet', 'gwolle-gb') . '</option>';
						}
						$mass_edit_controls .= '<option value="trash">' . esc_html__('Move to trash', 'gwolle-gb') . '</option>';
						if ( $show === 'spam' ) {
							$mass_edit_controls .= '<option value="remove">' . esc_html__('Remove permanently', 'gwolle-gb') . '</option>';
						}
						$mass_edit_controls .= '<option value="anon">' . esc_html__('Anonymize', 'gwolle-gb') . '</option>';

					}
					$mass_edit_controls .= '</select>';
					$mass_edit_controls .= '<input type="submit" value="' . esc_attr__('Apply', 'gwolle-gb') . '" name="doaction" id="doaction" class="button-secondary action" />';
					$empty_button = '';
					if ( $show === 'spam' ) {
						$empty_button = '<input type="submit" name="delete_all" id="delete_all" class="button apply" value="' . esc_attr__('Empty Spam', 'gwolle-gb') . '" />';
					} else if ( $show === 'trash' ) {
						$empty_button = '<input type="submit" name="delete_all" id="delete_all" class="button apply" value="' . esc_attr__('Empty Trash', 'gwolle-gb') . '" />';
					}

					// Only show controls when there are entries
					if ( is_array($entries) && ! empty($entries) ) {
						echo $mass_edit_controls_select . $mass_edit_controls . $empty_button;
					} ?>
				</div>

				<?php
				if ( $show === 'user' ) {
					echo '<div class="tablenav-pages">
						<span class="displaying-num">' . esc_html__('Showing:', 'gwolle-gb') . ' ' . $count_entries . '</span>
					</div>';

				} else {
					$pagination = gwolle_gb_pagination_admin( $page_num, $count_entrypages, $count_entries, $show );
					echo $pagination;
				}
				?>
			</div>

			<div>
				<table class="widefat">
					<thead>
						<tr>
							<th scope="col" class="manage-column column-cb check-column"><input name="check-all-top" id="check-all-top" type="checkbox"></th>
							<th scope="col"><?php esc_html_e('Book', 'gwolle-gb'); if ($book_id > 0) { echo ' ' . $book_id; } ?></th>
							<th scope="col"><?php esc_html_e('ID', 'gwolle-gb'); ?></th>
							<?php
							if (get_option('gwolle_gb-showEntryIcons', 'true') === 'true') { ?>
								<th scope="col"><?php esc_html_e('Status', 'gwolle-gb'); ?></th><!-- this is the icon-column -->
							<?php
							} ?>
							<th scope="col"><?php esc_html_e('Date', 'gwolle-gb'); ?></th>
							<th scope="col"><?php esc_html_e('Author', 'gwolle-gb'); ?></th>
							<th scope="col"><?php esc_html_e('Entry (excerpt)', 'gwolle-gb'); ?></th>
							<th scope="col"><?php esc_html_e('Action', 'gwolle-gb'); ?></th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<th scope="col" class="manage-column column-cb check-column"><input name="check-all-bottom" id="check-all-bottom" type="checkbox"></th>
							<th scope="col"><?php esc_html_e('Book', 'gwolle-gb'); if ($book_id > 0) { echo ' ' . $book_id; } ?></th>
							<th scope="col"><?php esc_html_e('ID', 'gwolle-gb'); ?></th>
							<?php
							if (get_option('gwolle_gb-showEntryIcons', 'true') === 'true') { ?>
								<th scope="col"><?php esc_html_e('Status', 'gwolle-gb'); ?></th><!-- this is the icon-column -->
							<?php
							} ?>
							<th scope="col"><?php esc_html_e('Date', 'gwolle-gb'); ?></th>
							<th scope="col"><?php esc_html_e('Author', 'gwolle-gb'); ?></th>
							<th scope="col"><?php esc_html_e('Entry (excerpt)', 'gwolle-gb'); ?></th>
							<th scope="col"><?php esc_html_e('Action', 'gwolle-gb'); ?></th>
						</tr>
					</tfoot>


					<tbody>
						<?php
						$request_uri = $_SERVER['REQUEST_URI'];
						$row_odd = true;
						$html_output = '';
						if ( ! is_array( $entries ) || empty( $entries ) ) {
							$colspan = 7;
							if ( get_option('gwolle_gb-showEntryIcons', 'true') === 'true') {
								$colspan = 8;
							}
							$html_output .= '
								<tr>
									<td colspan="' . esc_attr( $colspan ) . '" align="center">
										<strong>' . esc_html__('No entries found.', 'gwolle-gb') . '</strong>
									</td>
								</tr>';
						} else {
							foreach ($entries as $entry) {

								// rows have a different color.
								if ($row_odd) {
									$row_odd = false;
									$class = ' alternate';
								} else {
									$row_odd = true;
									$class = '';
								}

								// Attach 'spam' to class if the entry is spam
								if ( $entry->get_isspam() === 1 ) {
									$class .= ' spam';
								} else {
									$class .= ' nospam';
								}

								// Attach 'trash' to class if the entry is in trash
								if ( $entry->get_istrash() === 1 ) {
									$class .= ' trash';
								} else {
									$class .= ' notrash';
								}

								// Attach 'checked/unchecked' to class
								if ( $entry->get_ischecked() === 1 ) {
									$class .= ' checked';
								} else {
									$class .= ' unchecked';
								}

								// Attach 'visible/invisible' to class
								if ( $entry->get_isspam() === 1 || $entry->get_istrash() === 1 || $entry->get_ischecked() === 0 ) {
									$class .= ' invisible';
								} else {
									$class .= ' visible';
								}

								// Add admin-entry class to an entry from an admin
								$author_id = $entry->get_author_id();
								$is_moderator = gwolle_gb_is_moderator( $author_id );
								if ( $is_moderator ) {
									$class .= ' admin-entry';
								}

								// Checkbox and ID columns
								$html_output .= '
									<tr id="entry_' . $entry->get_id() . '" class="entry ' . $class . '">
										<td class="check">
											<input name="check-' . $entry->get_id() . '" id="check-' . $entry->get_id() . '" type="checkbox">
										</td>
										<td class="book">
											<span class="book-icon" title="' . esc_html__('Book ID', 'gwolle-gb') . ' ' . $entry->get_book_id() . '">
												<a href="' . add_query_arg( 'book_id', $entry->get_book_id(), $request_uri ) . '"
													title="' . esc_attr__('Book ID', 'gwolle-gb') . ' ' . $entry->get_book_id() . '">
													' . $entry->get_book_id() . '
												</a>
											</span>
										</td>
										<td class="id">
											<label for="check-' . $entry->get_id() . '">
												' . $entry->get_id() . '
											</label>
										</td>';

								// Optional Icon column where CSS is being used to show them or not
								if ( get_option('gwolle_gb-showEntryIcons', 'true') === 'true' ) {
									$html_output .= '
										<td class="entry-icons">
											<span class="visible-icon" title="' . esc_attr__('Visible', 'gwolle-gb') . '"></span>
											<span class="invisible-icon" title="' . esc_attr__('Invisible', 'gwolle-gb') . '"></span>
											<span class="spam-icon" title="' . esc_attr__('Spam', 'gwolle-gb') . '"></span>
											<span class="trash-icon" title="' . /* translators: Is in Trashcan */ esc_attr__('In Trash', 'gwolle-gb') . '"></span>';
									$admin_reply = gwolle_gb_sanitize_output( $entry->get_admin_reply(), 'admin_reply' );
									if ( strlen( trim($admin_reply) ) > 0 ) {
										$html_output .= '
											<span class="admin_reply-icon" title="' . esc_attr__('Admin Replied', 'gwolle-gb') . '"></span>';
									}
									$html_output .= '
											<span class="gwolle_gb_ajax" title="' . esc_attr__('Wait...', 'gwolle-gb') . '"></span>
										</td>';
								}

								// Date column
								$html_output .= '
									<td class="entry-date">' . date_i18n( get_option('date_format'), $entry->get_datetime() ) . ', ' .
										date_i18n( get_option('time_format'), $entry->get_datetime() ) .
									'</td>';

								// Author column
								$author_name_html = gwolle_gb_get_author_name_html($entry);
								$html_output .= '
									<td class="entry-author-name">
										<span class="author-name">' . $author_name_html . '</span><br />
										<span class="author-email">' . $entry->get_author_email() . '</span>
									</td>';

								// Excerpt column
								$html_output .= '
									<td class="entry-content">
									';
								$entry_content = gwolle_gb_get_excerpt( $entry->get_content(), 17 );
								if ( get_option('gwolle_gb-showSmilies', 'true') === 'true' ) {
									$entry_content = convert_smilies($entry_content);
								}
								$html_output .= $entry_content . '
									</td>';

								// Actions column
								$html_output .= '
									<td class="gwolle_gb_actions">
										<span class="gwolle_gb_edit">
											<a href="' . admin_url( 'admin.php?page=' . GWOLLE_GB_FOLDER . '/editor.php&entry_id=' . $entry->get_id() ) . '" title="' . esc_attr__('Edit entry', 'gwolle-gb') . '">' . esc_html__('Edit', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_check">&nbsp;|&nbsp;
											<a id="check_' . $entry->get_id() . '" href="#" class="vim-a" title="' . esc_attr__('Check entry', 'gwolle-gb') . '">' . esc_html__('Check', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_uncheck">&nbsp;|&nbsp;
											<a id="uncheck_' . $entry->get_id() . '" href="#" class="vim-u" title="' . esc_attr__('Uncheck entry', 'gwolle-gb') . '">' . esc_html__('Uncheck', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_spam">&nbsp;|&nbsp;
											<a id="spam_' . $entry->get_id() . '" href="#" class="vim-s vim-destructive" title="' . esc_attr__('Mark entry as spam.', 'gwolle-gb') . '">' . esc_html__('Spam', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_unspam">&nbsp;|&nbsp;
											<a id="unspam_' . $entry->get_id() . '" href="#" class="vim-a" title="' . esc_attr__('Mark entry as not-spam.', 'gwolle-gb') . '">' . esc_html__('Not spam', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_trash">&nbsp;|&nbsp;
											<a id="trash_' . $entry->get_id() . '" href="#" class="vim-d vim-destructive" title="' . esc_attr__('Move entry to trash.', 'gwolle-gb') . '">' . /* translators: Move to Trashcan */ esc_html__('Trash', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_untrash">&nbsp;|&nbsp;
											<a id="untrash_' . $entry->get_id() . '" href="#" class="vim-d" title="' . esc_attr__('Recover entry from trash.', 'gwolle-gb') . '">' . esc_html__('Untrash', 'gwolle-gb') . '</a>
										</span>
										<span class="gwolle_gb_ajax">&nbsp;|&nbsp;
											<a id="ajax_' . $entry->get_id() . '" href="#" class="ajax vim-d vim-destructive" title="' . esc_attr__('Please wait...', 'gwolle-gb') . '">' . esc_html__('Wait...', 'gwolle-gb') . '</a>
										</span>
									</td>
								</tr>';
							}
						}
						echo $html_output;
						?>
					</tbody>
				</table>
			</div>

			<div class="tablenav">
				<div class="alignleft actions">
					<?php
					$mass_edit_controls_select = '<select name="massEditAction2">';
					$empty_button = '';
					if ( $show === 'spam' ) {
						$empty_button = '<input type="submit" name="delete_all2" id="delete_all2" class="button apply" value="' . esc_attr__('Empty Spam', 'gwolle-gb') . '"  />';
					} else if ( $show === 'trash' ) {
						$empty_button = '<input type="submit" name="delete_all2" id="delete_all2" class="button apply" value="' . esc_attr__('Empty Trash', 'gwolle-gb') . '"  />';
					}

					// Only show controls when there are entries
					if ( is_array($entries) && ! empty($entries) ) {
						echo $mass_edit_controls_select . $mass_edit_controls . $empty_button;
					} ?>
				</div>
				<?php
				if ( $show !== 'user' ) {
					echo $pagination;
				} ?>
			</div>

		</form>

	</div>
	<?php
}



/*
 * Update admin page with lists of entries.
 *
 * @since 3.0.0
 */
function gwolle_gb_page_entries_update() {

	if ( ! current_user_can('moderate_comments') ) {
		die(esc_html__('You need a higher level of permission.', 'gwolle-gb'));
	}

	$show = 'all';
	if ( isset($_GET['show']) && in_array($_GET['show'], array( 'checked', 'unchecked', 'spam', 'trash', 'user' ), true ) ) {
		$show = $_GET['show'];
	}

	/* Check Nonce */
	if ( isset($_POST['gwolle_gb_wpnonce']) ) {
		$verified = wp_verify_nonce( $_POST['gwolle_gb_wpnonce'], 'gwolle_gb_page_entries' );
		if ( $verified === false ) {
			// Nonce is invalid, so considered spam.
			gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
			return;
		}
	} else {
		// Nonce is not set, so considered spam.
		gwolle_gb_add_message( '<p>' . esc_html__('The Nonce did not validate. Please reload the page and try again.', 'gwolle-gb') . '</p>', true, false);
		return;
	}

	/* Check if we are not sending in more entries than were even listed. */
	$entries_checked = 0;
	$num_entries = (int) get_option('gwolle_gb-entries_per_page', 20);
	foreach ( array_keys($_POST) as $post_element_name ) {
		if (strpos($post_element_name, 'check') > -1 && ! strpos($post_element_name, '-all-') && $_POST["$post_element_name"] === 'on') {
			$entries_checked++;
		}
	}
	if ( $entries_checked < ( $num_entries + 1 ) ) {
		// OK: number of entries checked is less or equal to the number listed on the page.
	} else if ( $show === 'user' ) {
		// OK: special case for mass edit all entries from user.
	} else {
		gwolle_gb_add_message( '<p>' . esc_html__('It seems you checked more entries then were even listed on the page.', 'gwolle-gb') . '</p>', true, false);
		return;
	}
	/* End of security checks. */


	if ( isset($_POST['gwolle_gb_page']) && $_POST['gwolle_gb_page'] === 'entries' ) {
		$action = '';
		if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'check' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'check' ) ) {
			$action = 'check';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'uncheck' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'uncheck' ) ) {
			$action = 'uncheck';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'spam' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'spam' ) ) {
			$action = 'spam';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'no-spam' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'no-spam' ) ) {
			$action = 'no-spam';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'akismet' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'akismet' ) ) {
			$action = 'akismet';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'trash' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'trash' ) ) {
			$action = 'trash';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'untrash' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'untrash' ) ) {
			$action = 'untrash';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'remove' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'remove' ) ) {
			$action = 'remove';
		} else if ( ( isset($_POST['massEditAction1']) && $_POST['massEditAction1'] === 'anon' ) || ( isset($_POST['massEditAction2']) && $_POST['massEditAction2'] === 'anon' ) ) {
			$action = 'anon';
		}
		if ( $action === '' && $show !== 'user' && ! isset( $_POST['delete_all'] ) && ! isset( $_POST['delete_all2'] ) ) {
			gwolle_gb_add_message( '<p>' . esc_html__('Something went wrong. Please try again.', 'gwolle-gb') . '</p>', true, false);
			return;
		}

		// Initialize variables to generate messages with.
		$entries_handled = 0;
		$entries_not_handled = 0;
		$akismet_spam = 0;
		$akismet_not_spam = 0;
		$akismet_already_spam = 0;
		$akismet_already_not_spam = 0;

		foreach ( array_keys($_POST) as $post_element_name ) {
			if (strpos($post_element_name, 'check') > -1 && ! strpos($post_element_name, '-all-') && $_POST["$post_element_name"] === 'on') {
				$entry_id = str_replace('check-', '', $post_element_name);
				$entry_id = (int) $entry_id;
				if ( isset($entry_id) && $entry_id > 0 ) {
					$entry = new gwolle_gb_entry();
					$result = $entry->load( $entry_id );
					if ( $result ) {

						if ( $action === 'check' ) {
							if ( $entry->get_ischecked() === 0 ) {
								$entry->set_ischecked( true );
								$user_id = get_current_user_id(); // returns 0 if no current user
								$entry->set_checkedby( $user_id );
								gwolle_gb_add_log_entry( $entry->get_id(), 'entry-checked' );
								$result = $entry->save();
								if ( $result ) {
									$entries_handled++;
									gwolle_gb_mail_author_on_moderation( $entry );
									do_action( 'gwolle_gb_save_entry_admin', $entry );
								} else {
									$entries_not_handled++;
								}
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'uncheck' ) {
							if ( $entry->get_ischecked() === 1 ) {
								$entry->set_ischecked( false );
								$user_id = get_current_user_id(); // returns 0 if no current user
								$entry->set_checkedby( $user_id );
								gwolle_gb_add_log_entry( $entry->get_id(), 'entry-unchecked' );
								$result = $entry->save();
								if ( $result ) {
									$entries_handled++;
									do_action( 'gwolle_gb_save_entry_admin', $entry );
								} else {
									$entries_not_handled++;
								}
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'spam' ) {

							if ( $entry->get_isspam() === 0 ) {
								$entry->set_isspam( true );
								if ( get_option('gwolle_gb-akismet-active', 'false') === 'true' ) {
									gwolle_gb_akismet( $entry, 'submit-spam' );
								}
								gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-spam' );
								$result = $entry->save();
								if ( $result ) {
									$entries_handled++;
									do_action( 'gwolle_gb_save_entry_admin', $entry );
								} else {
									$entries_not_handled++;
								}
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'no-spam' ) {
							if ( $entry->get_isspam() === 1 ) {
								$entry->set_isspam( false );
								if ( get_option('gwolle_gb-akismet-active', 'false') === 'true' ) {
									gwolle_gb_akismet( $entry, 'submit-ham' );
								}
								gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-not-spam' );
								$result = $entry->save();
								if ( $result ) {
									$entries_handled++;
									gwolle_gb_mail_author_on_moderation( $entry );
									do_action( 'gwolle_gb_save_entry_admin', $entry );
								} else {
									$entries_not_handled++;
								}
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'akismet' ) {
							/* Check for spam and set accordingly */
							if ( get_option('gwolle_gb-akismet-active', 'false') === 'true' ) {
								$isspam = gwolle_gb_akismet( $entry, 'comment-check' );
								if ( $isspam ) {
									// Returned true, so considered spam
									if ( $entry->get_isspam() === 0 ) {
										$entry->set_isspam( true );
										gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-spam' );
										$result = $entry->save();
										if ( $result ) {
											$akismet_spam++;
											do_action( 'gwolle_gb_save_entry_admin', $entry );
										} else {
											$akismet_not_spam++;
										}
									} else {
										$akismet_already_spam++;
									}
								} else {
									if ( $entry->get_isspam() === 1 ) {
										$entry->set_isspam( false );
										gwolle_gb_add_log_entry( $entry->get_id(), 'marked-as-not-spam' );
										$result = $entry->save();
										if ( $result ) {
											$akismet_not_spam++;
											do_action( 'gwolle_gb_save_entry_admin', $entry );
										} else {
											$akismet_spam++;
										}
									} else {
										$akismet_already_not_spam++;
									}
								}
							}
						} else if ( $action === 'trash' ) {
							if ( $entry->get_istrash() === 0 ) {
								$entry->set_istrash( true );
								gwolle_gb_add_log_entry( $entry->get_id(), 'entry-trashed' );
								$result = $entry->save();
								if ( $result ) {
									$entries_handled++;
									do_action( 'gwolle_gb_save_entry_admin', $entry );
								} else {
									$entries_not_handled++;
								}
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'untrash' ) {
							if ( $entry->get_istrash() === 1 ) {
								$entry->set_istrash( false );
								gwolle_gb_add_log_entry( $entry->get_id(), 'entry-untrashed' );
								$result = $entry->save();
								if ( $result ) {
									$entries_handled++;
									gwolle_gb_mail_author_on_moderation( $entry );
									do_action( 'gwolle_gb_save_entry_admin', $entry );
								} else {
									$entries_not_handled++;
								}
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'remove' ) {
							$result = $entry->delete();
							if ( $result ) {
								$entries_handled++;
								do_action( 'gwolle_gb_save_entry_admin', $entry );
							} else {
								$entries_not_handled++;
							}
						} else if ( $action === 'anon' ) {
							$entry = gwolle_gb_privacy_anonymize_entry( $entry );
							$result = $entry->save();
							if ( $result ) {
								$entries_handled++;
								do_action( 'gwolle_gb_save_entry_admin', $entry );
								gwolle_gb_add_log_entry( $entry->get_id(), 'entry-anonymized' );
							} else {
								$entries_not_handled++;
							}
						}
					} else { // no result on load()
						$entries_not_handled++;
					}
				} else { // entry_id is not set or not > 0
					$entries_not_handled++;
				}
			} // no entry with the check-'entry_id' input, continue
		} // foreach


		/* Construct Message */
		if ( $action === 'check' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry checked.', '%s entries checked.', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'uncheck' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry unchecked.', '%s entries unchecked.', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'spam' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry marked as spam and submitted to Akismet as spam (if Akismet was enabled).', '%s entries marked as spam and submitted to Akismet as spam (if Akismet was enabled).', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'no-spam' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry marked as not spam and submitted to Akismet as ham (if Akismet was enabled).', '%s entries marked as not spam and submitted to Akismet as ham (if Akismet was enabled).', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'akismet' ) {
			if ( $akismet_spam > 0 ) {
				/* translators: %s is the number of entries */
				gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry considered spam and marked as such.', '%s entries considered spam and marked as such.', $akismet_spam, 'gwolle-gb'), $akismet_spam ) . '</p>', false, false);
			}
			if ( $akismet_not_spam > 0 ) {
				/* translators: %s is the number of entries */
				gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry considered not spam and marked as such.', '%s entries considered not spam and marked as such.', $akismet_not_spam, 'gwolle-gb'), $akismet_not_spam ) . '</p>', false, false);
			}
			if ( $akismet_already_spam > 0 ) {
				/* translators: %s is the number of entries */
				gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry already considered spam and not changed.', '%s entries already considered spam and not changed.', $akismet_already_spam, 'gwolle-gb'), $akismet_already_spam ) . '</p>', false, false);
			}
			if ( $akismet_already_not_spam > 0 ) {
				/* translators: %s is the number of entries */
				gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry already considered not spam and not changed.', '%s entries already considered not spam and not changed.', $akismet_already_not_spam, 'gwolle-gb'), $akismet_already_not_spam ) . '</p>', false, false);
			}
		} else if ( $action === 'trash' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry moved to trash.', '%s entries moved to trash.', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'untrash' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry recovered from trash.', '%s entries recovered from trash.', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'remove' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry removed permanently.', '%s entries removed permanently.', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		} else if ( $action === 'anon' ) {
			/* translators: %s is the number of entries */
			gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry anonymized.', '%s entries anonymized.', $entries_handled, 'gwolle-gb'), $entries_handled ) . '</p>', false, false);
		}

		if ( isset( $_POST['delete_all'] ) || isset( $_POST['delete_all2'] ) ) {
			// Delete all entries in spam or trash.
			if ( in_array( $show, array( 'spam', 'trash' ), true ) ) {
				$deleted = gwolle_gb_del_entries( $show );
				/* translators: %s is the number of entries */
				gwolle_gb_add_message( '<p>' . sprintf( _n('%s entry removed permanently.', '%s entries removed permanently.', $deleted, 'gwolle-gb'), $deleted ) . '</p>', false, false);
			}
		}
	}
}
