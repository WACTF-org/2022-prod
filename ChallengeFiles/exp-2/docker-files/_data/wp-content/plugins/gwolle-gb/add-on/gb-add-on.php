<?php
/*
 * These strings are not used for the main plugin, but for the Commercial Add-On at:
 * https://zenoweb.nl/downloads/gwolle-guestbook-add-on/
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


function gwolle_gb_addon_translation_strings() {

	// description of readme
	/* translators: Commercial Add-On description */
	esc_html_e('Gwolle Guestbook: The Add-On is the add-on for Gwolle Guestbook that gives extra functionality for your guestbook. Meta Fields, Star ratings, Social Sharing and much more.', 'gwolle-gb');

	// function gwolle_gb_addon_page_settings() {
	/* translators: Commercial Add-On */
	esc_html_e('Add-On Settings', 'gwolle-gb');

	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Form Fields', 'gwolle-gb');
	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Reading Fields', 'gwolle-gb');
	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Social Media', 'gwolle-gb');
	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Star Rating', 'gwolle-gb');
	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Miscellaneous', 'gwolle-gb');
	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Strings', 'gwolle-gb');
	/* translators: Commercial Add-On: Settings page tab */
	esc_html_e('Abuse', 'gwolle-gb');

	// function gwolle_gb_entry_metabox_lines_admin_reply( $gb_metabox, $entry ) {
	/* translators: Commercial Add-On */
	esc_attr__('Add admin reply', 'gwolle-gb');

	// function gwolle_gb_admin_reply_javascript() {

	// function gwolle_gb_entry_metabox_lines_report_abuse( $gb_metabox, $entry ) {
	/* translators: Commercial Add-On */
	esc_attr__('Report Abuse for this entry', 'gwolle-gb');
	/* translators: Commercial Add-On, frontend metabox */
	esc_html__('Report Abuse', 'gwolle-gb');

	// function gwolle_gb_report_abuse_javascript()
	/* translators: Commercial Add-On, abuse report submitted */
	esc_html__('Reported', 'gwolle-gb');
	/* translators: Commercial Add-On, error on abuse report */
	esc_html__('Error', 'gwolle-gb');

	// gwolle_gb_addon_mail_moderators_report_abuse()
	/* translators: Commercial Add-On, mail notification on abuse report */
	esc_html__("
Hello,

There was a report for abuse for a guestbook entry at %blog_name%.
You can check it at %entry_management_url%.

Have a nice day.
Your Gwolle-GB-Mailer


Website address: %blog_url%
User name: %user_name%
User email: %user_email%
Entry status: %status%
Reports: %reports%
Entry content:
%entry_content%
"
, 'gwolle-gb');

	/* translators: Commercial Add-On, mail notification on media upload */
	esc_html__("
Hello,

An image has been uploaded into the Media Library at %blog_name%.

Media Library: %media_library%.
Image file: %attachment_src_large%.
Attachment ID: %attachment_id%.

Have a nice day.
Your Gwolle-GB-Mailer


Website address: %blog_url%
User name: %user_name%
User email: %user_email%
User IP address: %author_ip%
", 'gwolle-gb');
	/* translators: Commercial Add-On, mail notification on media upload */
	esc_html__('Media file was uploaded for the guestbook', 'gwolle-gb');


	// function gwolle_gb_entry_metabox_lines_delete_link( $gb_metabox, $entry ) {
	/* translators: Commercial Add-On */
	esc_attr__('Delete entry', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html__('Delete', 'gwolle-gb');

	// function gwolle_gb_entry_metabox_lines_social_media( $gb_metabox, $entry ) {
	/* translators: Commercial Add-On: Post it on Social Media */
	esc_attr__('Post on', 'gwolle-gb');

	// function gwolle_gb_entry_metabox_lines_email( $gb_metabox, $entry ) {
	/* translators: Commercial Add-On metabox line */
	esc_html__('Email author', 'gwolle-gb');
	/* translators: Commercial Add-On metabox line, email author */
	esc_html__('Email %s', 'gwolle-gb');

	//function gwolle_gb_entry_metabox_lines_like_ajax( $gb_metabox, $entry ) {
	/* translators: Commercial Add-On metabox line */
	esc_attr__('Like this entry', 'gwolle-gb');
	/* translators: Commercial Add-On metabox line */
	esc_attr__('Unlike this entry', 'gwolle-gb');

	// function gwolle_gb_addon_form_starrating( $output ) {
	/* translators: Commercial Add-On */
	esc_html__('Rating', 'gwolle-gb');

	// function gwolle_gb_entry_edit_javascript() {
	/* translators: Commercial Add-On metabox line for edit inline */
	esc_attr__('Edit entry:', 'gwolle-gb');

	// class GwolleGB_Widget_Av_Rating extends WP_Widget {
	/* translators: Commercial Add-On Widget */
	esc_html__('Displays the average star rating of a guestbook.','gwolle-gb');
	/* translators: Commercial Add-On Widget */
	esc_html__('Gwolle GB: Average Star Rating', 'gwolle-gb');
	/* translators: Commercial Add-On Widget */
	esc_html__('Average Star Rating', 'gwolle-gb');

	// function gwolle_gb_addon_deps_admin_notice() {
	/* translators: Commercial Add-On. %s is a link. */
	esc_html__( 'Gwolle Guestbook: The Add-On requires Gwolle Guestbook. Go to your %sPlugins%s page to install or activate Gwolle Guestbook.', 'gwolle-gb' );
	/* translators: Commercial Add-On. %s is a version number. */
	esc_html__( 'Gwolle Guestbook: The Add-On requires Gwolle Guestbook version %s. You have version %s. Go to your %sPlugins%s page to update Gwolle Guestbook.', 'gwolle-gb' );

	// function gwolle_gb_addon_page_settingstab_form() {
	/* translators: Commercial Add-On */
	esc_html_e('Configure the extra fields that you want.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('The slug of the field is where your data is attached to. Only change the slug if you know what you are doing.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('The name of the field is what you will see in the label in the form.', 'gwolle-gb');
	/* translators: Commercial Add-On. */
	esc_html_e('Required:', 'gwolle-gb');
	/* translators: Commercial Add-On. */
	esc_html_e('Top', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Slug:', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Name:', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('+ Add new field.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html__('Delete this meta field?', 'gwolle-gb' );
	/* translators: Commercial Add-On */
	esc_html__('Delete this string row?', 'gwolle-gb' );
	/* translators: Commercial Add-On */
	esc_html__('The slug %s is a reserved slug, please use a different slug.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Type:', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Text', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Checkbox', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Radio buttons', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Select dropdown', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Textarea', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Enter options for the radio buttons or select dropdown.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e( 'Use one option on each line. When the meta field is saved, it will be saved with this value.', 'gwolle-gb' );

	// function gwolle_gb_addon_page_settingstab_misc() {
	/* translators: Settings page, option for preview */
	esc_html_e('Show Preview button in Form.', 'gwolle-gb');
	/* translators: Settings page, option for preview */
	esc_html_e('Adds a button to the form where visitors can preview their entry before posting.', 'gwolle-gb');
	/* translators: Settings page, option for permalink */
	esc_html_e('Permalink', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show permalink in Metabox.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('A link to the single entry will be added to the metabox.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Author Email', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show author email in Metabox.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('The email address of the author will be added to the metabox.', 'gwolle-gb');
	/* translators: Settings page, option for uploading of images */
	esc_html_e('Upload Images', 'gwolle-gb');
	/* translators: Settings page, option for uploading of images */
	esc_html_e('Upload images through the form.', 'gwolle-gb');
	/* translators: Settings page, option for uploading of images */
	esc_html_e('Offer uploading of images. This will only be offered for users with the capability `upload_files`, which ususally is limited to Author, Editor and Administrator.', 'gwolle-gb');
	/* translators: Settings page, option for uploading of images */
	esc_html_e('Images can be added through the form and will be uploaded to the Media Library and added to the content of the entry.', 'gwolle-gb');
	/* translators: Settings page, option for likes */
	esc_html_e('Likes', 'gwolle-gb');
	/* translators: Settings page, option for likes */
	esc_html_e('Enable likes for entries.', 'gwolle-gb');
	/* translators: Settings page, option for likes */
	esc_html_e('This will enable likes, people can add a like to an entry and see the number of likes that were given.', 'gwolle-gb');
	/* translators: Settings page, option for delete link */
	esc_html_e('Delete link', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show delete link in Metabox for moderators.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show delete link in Metabox for author.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('A link to delete the entry will be added to the metabox. Only visible for moderators and the author.', 'gwolle-gb');
	/* translators: Commercial Add-On, option for auto anonymize */
	esc_html_e('Auto Anonymize', 'gwolle-gb');
	/* translators: Commercial Add-On, option for auto anonymize */
	esc_html_e('Auto Anonymize entries after a certain time.', 'gwolle-gb');
	/* translators: Commercial Add-On, option for auto anonymize */
	esc_html_e('This setting will enable automatic anonymization of entries older than a certain date.', 'gwolle-gb');
	/* translators: Commercial Add-On, option for auto anonymize */
	esc_html_e('Be very carefull with this option.', 'gwolle-gb');
	/* translators: Commercial Add-On, option for auto anonymize */
	esc_html_e('Auto Anonymize entries older than:', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Auto Delete', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Auto Delete entries after a certain time.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('This setting will enable automatic deletion of entries older than a certain date.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Auto Delete entries older than:', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('1 Day','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('2 Days','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('1 Week','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('2 Weeks','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('1 Month','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('6 Months','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('12 Months','gwolle-gb');

	// function gwolle_gb_addon_page_settingstab_reading() {
	/* translators: Commercial Add-On */
	esc_html_e('Configure where you want the extra fields displayed.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Above content.','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Under content.','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('In metabox.','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('None.','gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Which fields should be added to the guestbook widget.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('There are no Meta Fields saved yet. Please go to the Form tab, enter a field and save it.', 'gwolle-gb');


	// function gwolle_gb_addon_page_settingstab_social() {
	/* translators: Commercial Add-On */
	esc_html_e('Share on Social Media', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show share icons for Social Media in the metabox. Below you can select which ones and their order.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Preferably you choose 6 services, since the standard layout has space for 6 icons.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Sharing Services', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Select the Social Media services you want enabled for sharing.', 'gwolle-gb');
	/* translators: Commercial Add-On. Location of the display. */
	esc_html_e('Location for display', 'gwolle-gb');

	// function gwolle_gb_addon_page_settingstab_starrating() {
	/* translators: Commercial Add-On */
	esc_html_e('Use star rating so visitors can give a star rating for your website or post.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show Average', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Show Average Star Rating', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('The average will be shown above the list of entries.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Subject for Star Rating', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Subject for Average Star Rating, used by Search Engines.', 'gwolle-gb');

	// function gwolle_gb_addon_page_settingstab_strings() {
	/* translators: Commercial Add-On */
	esc_html_e('String Replacement', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Here you can replace text strings throughout the frontend form, the list of entries, and the messages that get displayed for the form.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Old String', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Example: Guestbook', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('New String', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Example: Review', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('+ Add new string.', 'gwolle-gb');

	// function gwolle_gb_addon_page_settingstab_abuse() {
	/* translators: Commercial Add-On */
	esc_html_e('Enable report abuse link in Metabox.', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html_e('Visitors can report abusive entries through a link in the metabox.', 'gwolle-gb');

	// gwolle_gb_addon_editor_metabox_meta()
	/* translators: Commercial Add-On, metabox on editor */
	esc_html__('There are no Meta Fields saved in settings yet.','gwolle-gb');
	/* translators: Commercial Add-On, metabox on editor */
	esc_html__('Abuse Reports', 'gwolle-gb');
	/* translators: Commercial Add-On, remove abuse reports and moderate entry */
	esc_html__('Remove and moderate', 'gwolle-gb');
	/* translators: Commercial Add-On */
	esc_html__('Already moderated', 'gwolle-gb');

	// function gwolle_gb_addon_starrating_average_html()
	/* translators: Commercial Add-On. %s is the value/number of votes. */
	__( 'Average Rating: <strong>%s out of %s</strong> (%s votes)', 'gwolle-gb' );

	// function gwolle_gb_addon_add_privacy_policy_content() {
	/* translators: Commercial Add-On. Text for privacy policy. */
	__( 'When visitors report an entry as abusive, the entry ID will be stored in a cookie in the browser. Also, the IP address will be saved temporarily in the database together with the number of reports.', 'gwolle-gb' );
	/* translators: Commercial Add-On. Text for privacy policy. */
	esc_html__( 'When visitors like an entry, the IP address will be saved in the database.', 'gwolle-gb' );

	/* translators: %s is the name of the meta field */
	esc_html__('The %s field was not filled in, even though it is mandatory.', 'gwolle-gb');

	// function gwolle_gb_addon_write_add_after_content_upload_media( $form_html )
	/* translators: Description with the button for uploading images */
	esc_html__('Upload images (Max %d MiB)', 'gwolle-gb');
	/* translators: Button text */
	esc_attr__('Upload and add image', 'gwolle-gb');

	// function gwolle_gb_addon_upload_media_handler() {
	/* translators: Return messages for uploading images */
	esc_html__('Your file is too large.', 'gwolle-gb');
	/* translators: Return messages for uploading images */
	esc_html__('Your file has the wrong mime type.', 'gwolle-gb');
	/* translators: Return messages for uploading images */
	esc_html__('Your file has the wrong file extension.', 'gwolle-gb');
	/* translators: Return messages for uploading images */
	esc_html__('Something went wrong. Please try again or contact an admin.', 'gwolle-gb');
	/* translators: Return messages for uploading images */
	esc_html__('File was uploaded successfully.', 'gwolle-gb');

	// function gwolle_gb_addon_enqueue_frontend()
	/* translators: Message for uploading images */
	esc_html__( 'Please select a file before clicking Upload.', 'gwolle-gb' );

}
