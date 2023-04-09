=== Events Made Easy ===  
Contributors: liedekef
Donate link: https://www.e-dynamics.be/wordpress
Tags: events, memberships, locations, bookings, calendars, maps, payment gateways, drip content
Tested up to: 6.0
Stable tag: 2.2.80
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and display events, memberships, recurring events, locations and maps, volunteers, widgets, RSVP, ICAL and RSS feeds, payment gateways support. SEO compatible.
             
== Description ==

Events Made Easy is a full-featured event and membership management solution for Wordpress. Events Made Easy supports public, private, draft and recurring events, membership and locations management, RSVP (+ optional approval), several payment gateways (Paypal, 2Checkout, FirstData, Mollie and others) and OpenStreetMap integration. With Events Made Easy you can plan and publish your event, let people book spaces for your weekly meetings or manage volunteers and memberships. You can add events list, calendars and description to your blog using multiple sidebar widgets or shortcodes; if you are a web designer you can simply employ the placeholders provided by Events Made Easy. 

Main features:
* Public, private, draft and recurring events with custom and dynamic fields in the RSVP form
* Membership management with custom and dynamic fields
* Volunteer management for events (using event tasks)
* Attendance reporting for events and memberships if the rsvp or member qrcode is scanned by someone with enough rights
* Page and post content protection through memberships or via shortcodes
* Drip content via memberships
* People and groups with custom fields per person
* PDF creation for membership, bookings and people info
* Membership card or booking ticket can be sent as PDF via mail automatically, with optional QR code to scan for attendance/payment info
* RSS and ICAL feeds
* Calendar management, with holidays integration
* Several widgets for event listings and calendar
* Location management, with optional OpenStreetMap integration
* RSVP bookings with custom fields and dynamic fields, payment tracking, optional approval, discounts
* Protection of forms with internal captcha, Google reCaptcha or hCaptcha
* Templating for mails, event lists, single events, feeds, RSVP forms, ... with specific placeholders for each
* Lots of shortcodes and options
* Payment gateways: Paypal, FirstData, 2CheckOut, Mollie, Payconiq, Worldpay, Stripe, Braintree, Instamojo, Mercado Pago
* Send mails to registered people, automatically send reminders for payments
* Automatically send reminders for memberships that are about to expire or have expired
* Mail queueing and newsletter functionality
* Mailings can be planned in the future, cancelled ... and can include extra attacments
* Multi-site compatible
* Several GDPR assistance features (request, view and edit personal info via link; delete old records for mailings, attendances, bookings)
* Fully localisable and already fully localised in German, Swedish, French and Dutch. Also fully compatible with qtranslate-xt (https://github.com/qtranslate/qtranslate-xt/): most of the settings allow for language tags so you can show your events in different languages to different people. The booking mails also take the choosen language into account. For other multi-lingual plugins, EME provides its own in-text language tags and takes the current chosen language into account.

For more information, documentation and support forum visit the [Official site](https://www.e-dynamics.be/wordpress/) .

== Installation ==

Always take a backup of your db before doing the upgrade, just in case ...  
1. Upload the `events-made-easy` folder to the `/wp-content/plugins/` directory  
2. Activate the plugin through the 'Plugins' menu in WordPress (make sure your configured database user has the right to create/modify tables and columns) 
3. Add events list or calendars following the instructions in the Usage section.  

= Usage =

After the installation, Events Made Easy add a top level "Events" menu to your Wordpress Administration.

*  The *Events* page lets you manage your events. The *Add new* page lets you insert a new event.
   Generic EME settings concerning RSVP mails and templates can be overriden per event.
*  The *Locations* page lets you add, delete and edit locations directly. Locations are automatically added with events if not present, but this interface lets you customise your locations data and add a picture.
*  The *Categories* page lets you add, delete and edit categories (if Categories are activated in the Settings page).
*  The *Holidays* page is used to define and manage holiday lists used in a calendar
*  The *Custom fields* page lets you manage custom fields that can be used for events, locations, people, members, memberships and RSVP definitions
*  The *Template* page lets you manage templates for events, memberships, mails, pdf creation, ...
*  The *Discounts* page lets you manage discounts and discount groups used in RSVP or membership definitions
*  The *People* page serves as a gathering point for the information about the people who booked a space for one of your events or for members personal info.
   It can also be used to add custom info for a person based on the group he's in, so as to reflect the structure of an organization or just store extra info
*  The *Groups* page
*  The *Pending bookings* page is used to manage bookings for events that require approval.
*  The *Change bookings* page is used to change bookings for events.
*  The *Members* page is used to manage all your members (e.g. membership status, custom member info).
*  The *Memberships* page is used to define and manage your memberships. 
*  The *Countries/states* page can be used to define countries and states (in different languages) for personal info in membership and RSVP forms
*  The *Send mails* page allows the planning, creation and management of mailings for events or generic info (many options possible)
*  The *Scheduled actions* page is used to plan automated EME tasks (like sending reminders, cancel unpaid bookings, newsletter)).
*  The *Cleanup actions* page
*  The *Settings* page is used to set generic EME defaults for events, payment gateways, mailserver info, mail templates, ...
*  Fine-grainded configurable access control (ACL) for managing events, locations, bookings, members, ...

Events list and calendars can be added to your blogs through widgets, shortcodes and placeholders. See the full documentation at the [Events Made Easy Support Page](https://www.e-dynamics.be/wordpress/).
 
== Frequently Asked Questions ==

See the FAQ section at [the documentation site](https://www.e-dynamics.be/wordpress).

== Changelog ==
= 2.2.80 (2022/05/27) =
* Security update: fix SQL injection with unescaped lang variable (reported by https://wpscan.com)
  Users are advised to update to the latest version immediately!

= 2.2.79 (2022/05/21) =
* Fix import of custom field answers for locations
* People birthday emails were being sent 2 times
* Added booking mailfilters userconfirmation_pending_subject/body

= 2.2.78 (2022/05/16) =
* Fix leftover php issue in ExpressiveDate.php so it works with php 8.1 and older

= 2.2.77 (2022/05/15) =
* Fix some php issues (trying to be ok with php 8.1 seems more daunting than expected)

= 2.2.76 (2022/05/15) =
* Add filter eme_wp_userdata_filter, which allows you to set extra info for the WP user being created after a booking (if that option is set)
  The current EME person is given as argument (array), the result should be an array that is accepted by wp_update_user
* Fix waitinglist management in case a booking is not paid for but booking approval is not required
* Check free seats just before the payment form is shown, in case pending bookings are considered as free we need to make sure at the moment of payment seats are actually available
* If the option is set to consider pending bookings as free seats, pending bookings younger than 5 minutes are considered as occupied seats as well as to avoid possible clashes with slow payments (only if online payment for that event is possible)
* Added the possibility to filter on category in bookings overview
* Added #_YOUNGPENDINGSEATS: gives the number of pending seats younger than 5 minutes for an event (those are counted as occupied too, even if pending seats are considered as free)
* Added #_YOUNGPENDINGSEATS{xx} gives the number of pending seats younger than 5 minutes for the xx-th seat category for a multi-seat event
* Include all member attachments in mails to the contact person

= 2.2.75 (2022/05/06) =
* Fix BCC mail sending
* Add option for newsletter sub/unsub per person too (the automatic mail for new events)
* Add extra SEO permalink prefixes for calendar and payments
* Added placeholder #_BOOKING_CONFIRM_URL (which gives a nicer link for a booker to confirm his booking if permalinks are active, not just the payment url)

= 2.2.74 (2022/04/25) =
* Allow filter on email settings, so you can e.g. change server/port/... based on the 'to'
  The filter is called eme_filter_mail_options and takes 1 array as argument:
     $mail_options=array(
           'fromMail',
           'fromName',
           'toMail',
           'toName',
           'replytoMail',
           'replytoName',
           'bcc_addresses',
           'mail_send_method', // smtp, mail, sendmail, qmail, wp_mail
           'send_html',        // true or false
           'smtp_host',        
           'smtp_encryption',  // none, tls or ssl
           'smtp_verify_cert', // true or false
           'smtp_port',        
           'smtp_auth',        // 0 or 1, false or true
           'smtp_username',
           'smtp_password', 
           'smtp_debug',      // true or false
   );
  The return should be the filtered (changed) array
* Optionally show event categories in bookings
* Make "#_SINGLE_EVENTPAGE_EVENTID" placeholder work as expected
* Add generic placeholder #_SINGLE_EVENTPAGE_LOCATIONID, returning the location id of the event currently being shown (so you can e.g. show a location map of the current event in a widget)
* Add generic placeholder #_SINGLE_LOCATIONPAGE_LOCATIONID, returning the location id of the location currently being shown (so you can e.g. show a location map of the current location in a widget)
* #_PAYMENT_URL placeholder (also used to have the user confirm a booking) was not showing the link if online payment for the event was not possible

= 2.2.73 (2022/04/15) =
* Leaflet JS will now also switch to dark mode if your browser is set that way
* Allow direct template input for memberships too, without needing to define separate templates
* Make add-tasks work again (javascript error sneaked in)

= 2.2.72 (2022/04/11) =
* Delete old answers for custom fields for people if appropriate (when the custom formfield is no longer present) if updating the person in the backend
* Allow 0/empty as value for planned actions "Schedule the automatic removal of unpaid pending bookings older than" and "Schedule the automatic removal of unconfirmed bookings older than"
* Correct Donate button

= 2.2.71 (2022/04/09) =
* Better plan cron (WP only takes 24 hours in a day, not taking summer/winter timezones into account)
* Birthday mails needs to be sent for people indicated to do so, despite the default setting for new people

= 2.2.70 (2022/04/08) =
* Update Stripe API to 7.121.0
* Update Braintree API to 6.8.0
* Update Fondy API to 1.0.9.1
* Update Mercadopago API to 2.4.5
* Update Mollie API to 2.41.0
* Remove Paymill and Sagepay payment gateways
* Implement Braintree refunding
* Show Worldpay callback url in the payment options page
* Add RSVP placeholder #_WAITINGLIST_POSITION, returning the position of a booking on the waiting list
* Implement birthday functionality with new people placeholder #_BIRTHDAY_EMAIL, can be used to show a form field (yes/no) to allow people to indicate they want an email (or not) or as text info (Yes/No text) 

Older changes can be found in changelog.txt
