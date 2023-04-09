<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Gwolle Guestbook
 *
 * Class gwolle_gb_entry
 * Each instance is an entry in the guestbook.
 *
 * Member variables are set to protected, you are supposed to use setter and getter functions.
 *
 * $datetime is a UNIX timestamp in local time.
 * This does not really exist, since a UNIX timestamp is always GMT.
 * For backwards compatibility this is used even though it is wrong.
 *
 * Variable:        Database field:  Type in DB   Type in PHP   Description:                             Value when saving in db:
 * $id              id               int(10)      int           id of the entry/row/instance             required, autoincrement
 * $author_name     author_name      text         string        name of the author                       required
 * $author_id       author_id        int(5)       int           author is also registered user           required, default 0
 * $author_email    author_email     text         string        email address of the author              required
 * $author_origin   author_origin    text         string        city of the author                       required
 * $author_website  author_website   text         string        website of the author                    required
 * $author_ip       author_ip        text         string        ip address of the author                 required
 * $author_host     author_host      text         string        hostname of that ip address              required
 * $content         content          longtext     string        content of the entry                     required
 * $date            date             varchar(10)                date of posting the entry, timestamp     deprecated, use datetime which is an int, not varchar (sorting goes wrong).
 * $datetime        datetime         bigint(8)    int           date of posting the entry, timestamp     required, local timestamp
 * $ischecked       ischecked        tinyint(1)   int           checked/moderated by an admin, 0 or 1    required, default 0
 * $checkedby       checkedby        int(5)       int           admin who checked/moderated this entry   required, default 0
 * $istrash         istrash          varchar(1)   int           entry is placed in the trashbin, 0 or 1  required, default 0
 * $isspam          isspam           varchar(1)   int           entry is considered as spam, 0 or 1      required, default 0
 * $admin_reply     admin_reply      longtext     string        content of the reply from an admin       required, default empty
 * $admin_reply_uid admin_reply_uid  int(5)       int           user_id of the admin that replied        required, default 0
 * $book_id         book_id          int(8)       int           the book in which the entry is placed    required, default 1
 *
 */


class gwolle_gb_entry {

	protected $id, $author_name, $author_id, $author_email, $author_origin, $author_website,
		$author_ip, $author_host, $content, $datetime, $ischecked, $checkedby, $istrash, $isspam,
		$admin_reply, $admin_reply_uid, $book_id;

	/*
	 * Construct an instance
	 */

	public function __construct() {
		$this->id              = (int) 0;
		$this->author_name     = (string) '';
		$this->author_id       = (int) 0;
		$this->author_email    = (string) '';
		$this->author_origin   = (string) '';
		$this->author_website  = (string) '';
		$this->author_ip       = (string) '';
		$this->author_host     = (string) '';
		$this->content         = (string) '';
		$this->datetime        = (int) current_time( 'timestamp' );
		$this->ischecked       = (int) 0;
		$this->checkedby       = (int) 0;
		$this->istrash         = (int) 0;
		$this->isspam          = (int) 0;
		$this->admin_reply     = (string) '';
		$this->admin_reply_uid = (int) 0;
		$this->book_id         = (int) 1;
	}


	/*
	 * function load
	 * Loads the entry from database and sets the data in the instance
	 *
	 * Parameter:
	 * $id id of the entry to be loaded
	 *
	 * Return: true or false, depending on success
	 */

	public function load( $id ) {
		global $wpdb;

		$where = " 1 = %d";
		$values = array( 1 );

		if ( ! is_numeric($id) ) {
			return false;
		}

		if ( (int) $id > 0 ) {
			$where .= "
				AND
				id = %d";
			$values[] = $id;
		} else {
			return false;
		}

		$tablename = $wpdb->prefix . "gwolle_gb_entries";

		$sql = "
				SELECT
					`id`,
					`author_name`,
					`author_id`,
					`author_email`,
					`author_origin`,
					`author_website`,
					`author_ip`,
					`author_host`,
					`content`,
					`datetime`,
					`ischecked`,
					`checkedby`,
					`istrash`,
					`isspam`,
					`admin_reply`,
					`admin_reply_uid`,
					`book_id`
				FROM
					" . $tablename . "
				WHERE
					" . $where . "
				;";

		$sql = $wpdb->prepare( $sql, $values );

		/* Support caching of the entry. */
		$key         = md5( serialize( $sql ) );
		$cache_key   = "gwolle_gb_class_load:$key";
		$cache_value = wp_cache_get( $cache_key );

		if ( false === $cache_value ) {

			// Do a real query.
			$data = $wpdb->get_row( $sql, ARRAY_A );

			wp_cache_add( $cache_key, $data );

			// $wpdb->print_error();
			// echo "number of rows: " . $wpdb->num_rows;

		} else {

			// This is data from cache.
			$data = $cache_value;

		}

		if ( empty($data) ) {
			return false;
		}

		// Use the fields that the setter method expects
		$item = array(
			'id'              => (int) $data['id'],
			'author_name'     => stripslashes($data['author_name']),
			'author_id'       => (int) $data['author_id'],
			'author_email'    => stripslashes($data['author_email']),
			'author_origin'   => stripslashes($data['author_origin']),
			'author_website'  => stripslashes($data['author_website']),
			'author_ip'       => $data['author_ip'],
			'author_host'     => $data['author_host'],
			'content'         => stripslashes($data['content']),
			'datetime'        => (int) $data['datetime'],
			'ischecked'       => (int) $data['ischecked'],
			'checkedby'       => (int) $data['checkedby'],
			'istrash'         => (int) $data['istrash'],
			'isspam'          => (int) $data['isspam'],
			'admin_reply'     => stripslashes($data['admin_reply']),
			'admin_reply_uid' => (int) $data['admin_reply_uid'],
			'book_id'         => (int) $data['book_id'],
		);

		$this->set_data( $item );

		return true;
	}


	/* function save
	 * Saves the current $entry to database
	 * Return:
	 * - id:    if saved
	 * - false: if not saved
	 */

	public function save() {
		global $wpdb;

		// Add filter for the entry before saving, so devs can manipulate it. This is probably the right place.
		$data = apply_filters( 'gwolle_gb_entry_save', get_object_vars( $this ) );
		$this->set_data( $data );

		$this->check_userids();

		if ( $this->get_id() ) {
			// entry exists, use UPDATE

			//if ( WP_DEBUG ) { echo "Saving ID:: "; var_dump($this->get_id()); }

			$sql = "
				UPDATE $wpdb->gwolle_gb_entries
				SET
					author_name = %s,
					author_id = %d,
					author_email = %s,
					author_origin = %s,
					author_website = %s,
					author_ip = %s,
					author_host = %s,
					content = %s,
					datetime = %d,
					isspam = %d,
					ischecked = %s,
					checkedby = %d,
					istrash = %d,
					admin_reply = %s,
					admin_reply_uid = %d,
					book_id = %d
				WHERE
					id = %d
				";

			$values = array(
					$this->get_author_name(),
					$this->get_author_id(),
					$this->get_author_email(),
					$this->get_author_origin(),
					$this->get_author_website(),
					$this->get_author_ip(),
					$this->get_author_host(),
					$this->get_content(),
					$this->get_datetime(),
					$this->get_isspam(),
					$this->get_ischecked(),
					$this->get_checkedby(),
					$this->get_istrash(),
					$this->get_admin_reply(),
					$this->get_admin_reply_uid(),
					$this->get_book_id(),
					$this->get_id(),
				);

			$result = $wpdb->query(
					$wpdb->prepare( $sql, $values )
				);

		} else {
			// entry is new, use INSERT

			$result = $wpdb->query( $wpdb->prepare(
				"
				INSERT INTO $wpdb->gwolle_gb_entries
				(
					author_name,
					author_id,
					author_email,
					author_origin,
					author_website,
					author_ip,
					author_host,
					content,
					datetime,
					isspam,
					ischecked,
					checkedby,
					istrash,
					admin_reply,
					admin_reply_uid,
					book_id
				) VALUES (
					%s,
					%d,
					%s,
					%s,
					%s,
					%s,
					%s,
					%s,
					%d,
					%d,
					%d,
					%d,
					%d,
					%s,
					%d,
					%d
				)
				",
				array(
					$this->get_author_name(),
					$this->get_author_id(),
					$this->get_author_email(),
					$this->get_author_origin(),
					$this->get_author_website(),
					$this->get_author_ip(),
					$this->get_author_host(),
					$this->get_content(),
					$this->get_datetime(),
					$this->get_isspam(),
					$this->get_ischecked(),
					$this->get_checkedby(),
					$this->get_istrash(),
					$this->get_admin_reply(),
					$this->get_admin_reply_uid(),
					$this->get_book_id(),
				)
			) );

			if ($result > 0) {
				// Entry saved successfully.
				$this->set_id( $wpdb->insert_id );
			}
		}


		// Error handling
		if ( false ) {
			//$wpdb->print_error(); echo '<br />';
		}


		if ( $result === false ) {
			// result = 0 when no change was made. We still want to return the ID.
			return false;
		}

		return $this->get_id();
	}


	/* The Setter methods */

	/*
	 * Set all fields, $args is an array with fields
	 * Can be used after a $_POST or by the gwolle_gb_get_entries function
	 *
	 * Array $args:
	 * - id
	 * - author_name
	 * - author_id
	 * - author_email
	 * - author_origin
	 * - author_website
	 * - author_ip
	 * - author_host
	 * - content
	 * - datetime
	 * - ischecked
	 * - checkedby
	 * - istrash
	 * - isspam
	 * - admin_reply
	 * - admin_reply_uid
	 * - book_id

	 */

	public function set_data( $args ) {

		if ( isset( $args['id']) ) {
			$this->set_id( $args['id'] );
		}
		if ( isset( $args['author_name']) ) {
			$this->set_author_name( $args['author_name'] );
		}
		if ( isset( $args['author_id']) ) {
			$this->set_author_id( $args['author_id'] );
		}
		if ( isset( $args['author_email'] ) ) {
			$this->set_author_email( $args['author_email'] );
		}
		if ( isset( $args['author_origin'] ) ) {
			$this->set_author_origin( $args['author_origin'] );
		}
		if ( isset( $args['author_website'] ) ) {
			$this->set_author_website( $args['author_website'] );
		}
		if ( isset( $args['author_ip'] ) ) {
			$this->set_author_ip( $args['author_ip'] );
		} else if ( ! $this->get_author_ip() ) {
			$this->set_author_ip(); // set as new
		}
		if ( isset( $args['author_host'] ) ) {
			$this->set_author_host( $args['author_host'] );
		}
		if ( isset( $args['content'] ) ) {
			$this->set_content( $args['content'] );
		}
		if ( isset( $args['datetime'] ) ) {
			$this->set_datetime( $args['datetime'] );
		} else if ( ! $this->get_datetime() ) {
			$this->set_datetime(); // set as new
		}
		if ( isset( $args['ischecked'] ) ) {
			$this->set_ischecked( $args['ischecked'] );
		}
		if ( isset( $args['checkedby'] ) ) {
			$this->set_checkedby( $args['checkedby'] );
		}
		if ( isset( $args['istrash'] ) ) {
			$this->set_istrash( $args['istrash'] );
		}
		if ( isset( $args['isspam'] ) ) {
			$this->set_isspam( $args['isspam'] );
		}
		if ( isset( $args['admin_reply'] ) ) {
			$this->set_admin_reply( $args['admin_reply'] );
		}
		if ( isset( $args['admin_reply_uid'] ) ) {
			$this->set_admin_reply_uid( $args['admin_reply_uid'] );
		}
		if ( isset( $args['book_id'] ) ) {
			$this->set_book_id( $args['book_id'] );
		}

		return true;
	}

	public function set_id( $id ) {
		$id = (int) $id;
		if ($id) {
			$this->id = $id;
		}
	}
	public function set_author_name( $author_name ) {
		$author_name = gwolle_gb_sanitize_input($author_name);
		if ($author_name) {
			$this->author_name = $author_name;
		}
	}
	public function set_author_id( $author_id ) {
		$this->author_id = (int) $author_id;
	}
	public function set_author_email( $author_email ) {
		$author_email = gwolle_gb_sanitize_input($author_email);
		//$author_email = filter_var($author_email, FILTER_VALIDATE_EMAIL);
		$this->author_email = $author_email;
	}
	public function set_author_origin( $author_origin ) {
		$author_origin = gwolle_gb_sanitize_input($author_origin);
		$this->author_origin = $author_origin;
	}
	public function set_author_website( $author_website ) {
		$author_website = gwolle_gb_sanitize_input($author_website);
		$pattern = '/^http/';
		if ( ! preg_match($pattern, $author_website, $matches) ) {
			$author_website = 'http://' . $author_website;
		}
		$author_website = filter_var($author_website, FILTER_VALIDATE_URL);
		$this->author_website = $author_website;
	}
	public function set_author_ip( $author_ip = NULL ) {
		$author_ip = gwolle_gb_sanitize_input($author_ip);
		$this->author_ip = $author_ip;
	}
	public function set_author_host( $author_host = NULL ) {
		$author_host = gwolle_gb_sanitize_input($author_host);
		$this->author_host = $author_host;
	}
	public function set_content( $content ) {
		$content = gwolle_gb_sanitize_input($content, 'content');
		if ( strlen($content) > 0 ) {
			$this->content = $content;
		}
	}
	public function set_date( $date = NULL ) {
		_deprecated_function( __FUNCTION__, ' 1.4.2', 'set_datetime()' );
	}
	public function set_datetime( $datetime = 0 ) {
		$datetime = (int) $datetime; // timestamp can be cast to int.
		if ( ! $datetime ) {
			$datetime = current_time( 'timestamp' );
		}
		if ($datetime) {
			$this->datetime = $datetime;
		}
	}
	public function set_ischecked( $ischecked ) {
		// $ischecked means the message has been moderated
		$ischecked = (int) $ischecked;
		$this->ischecked = $ischecked;
	}
	public function set_checkedby( $checkedby ) {
		// $checkedby is a userid of the moderator
		$checkedby = (int) $checkedby;
		if ($checkedby) {
			$this->checkedby = $checkedby;
		}
	}
	public function set_istrash( $istrash ) {
		$istrash = (int) $istrash;
		$this->istrash = $istrash;
	}
	public function set_isspam( $isspam ) {
		$isspam = (int) $isspam;
		$this->isspam = $isspam;
	}
	public function set_admin_reply( $admin_reply ) {
		$admin_reply = gwolle_gb_sanitize_input($admin_reply, 'admin_reply');
		$this->admin_reply = $admin_reply;
	}
	public function set_admin_reply_uid( $admin_reply_uid ) {
		$this->admin_reply_uid = (int) $admin_reply_uid;
	}
	public function set_book_id( $book_id ) {
		$this->book_id = (int) $book_id;
		if ( ! $book_id) {
			$this->book_id = 1;
		}
	}


	/* The Getter methods */

	public function get_id() {
		return $this->id;
	}
	public function get_author_name() {
		return gwolle_gb_sanitize_output($this->author_name);
	}
	public function get_author_id() {
		return $this->author_id;
	}
	public function get_author_email() {
		return gwolle_gb_sanitize_output($this->author_email);
	}
	public function get_author_origin() {
		return gwolle_gb_sanitize_output($this->author_origin);
	}
	public function get_author_website() {
		return gwolle_gb_sanitize_output($this->author_website);
	}
	public function get_author_ip() {
		return $this->author_ip;
	}
	public function get_author_host() {
		return $this->author_host;
	}
	public function get_content() {
		return gwolle_gb_sanitize_output($this->content, 'content');
	}
	public function get_date() {
		_deprecated_function( __FUNCTION__, ' 1.4.2', 'get_datetime()' );
		return $this->datetime;
	}
	public function get_datetime() {
		return $this->datetime;
	}
	public function get_ischecked() {
		return $this->ischecked;
	}
	public function get_checkedby() {
		return $this->checkedby;
	}
	public function get_istrash() {
		return $this->istrash;
	}
	public function get_isspam() {
		return $this->isspam;
	}
	public function get_admin_reply() {
		return gwolle_gb_sanitize_output($this->admin_reply, 'admin_reply');
	}
	public function get_admin_reply_uid() {
		return $this->admin_reply_uid;
	}
	public function get_book_id() {
		return $this->book_id;
	}


	/* function delete
	 * Deletes the current $entry from database
	 *
	 * Return:
	 * - true: deleted
	 * - false: not deleted
	 *
	 */

	public function delete() {
		global $wpdb;

		if ( $this->get_isspam() === 0 && $this->get_istrash() === 0 ) {
			// Do not delete the good stuff.
			return false;
		}

		$id = $this->get_id();

		$sql = "
			DELETE
			FROM
				$wpdb->gwolle_gb_entries
			WHERE
				id = %d
			LIMIT 1";

		$values = array(
				$id,
			);

		$result = $wpdb->query(
				$wpdb->prepare( $sql, $values )
			);


		if ($result === 1) {
			// Also remove the log entries and possibly meta fields.
			do_action( 'gwolle_gb_delete_entry', $id );

			return true;
		}
		return false;
	}

	public function check_userids() {
		$author_id = $this->get_author_id();
		if ( $author_id > 0 ) {
			$userdata = get_userdata( $author_id );
			if ( ! is_object($userdata) ) {
				// reset non-existent user because of heavy load in db queries (userid 0 does not get cached).
				$this->author_id = 0;
			}
		}
		$checkedby = $this->get_checkedby();
		if ( $checkedby > 0 ) {
			$userdata = get_userdata( $checkedby );
			if ( ! is_object($userdata) ) {
				// reset non-existent user because of heavy load in db queries (userid 0 does not get cached).
				$this->checkedby = 0;
			}
		}
	}
}
