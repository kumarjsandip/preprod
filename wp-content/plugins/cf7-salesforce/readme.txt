=== Contact Form 7 Salesforce ===
Contributors: crmperks, sbazzi, asif876
Tags: wordpress salesforce, contact form 7 salesforce, wordpress salesforce integration, salesforce,
contact form 7 salesforce web to lead, wordpress salesforce form plugin, wordpress and salesforce integration
Requires at least: 3.8
Tested up to: 5.2
Stable tag: 1.1.1
Version: 1.1.1
Requires PHP: 5.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Send Contact Form 7, Contact Form Entries Plugin and many other contact form submissions to salesforce.

== Description ==

Contact Form 7 salesforce Plugin sends form submissions from [Contact Form 7](https://wordpress.org/plugins/contact-form-7/), [Contact Form Entries Plugin](https://wordpress.org/plugins/contact-form-entries/) and many other popular contact form plugins to Salesforce when someone submits a form. Learn more about Contact Form Salesforce Plugin at [crmperks.com](https://www.crmperks.com/plugins/contact-form-plugins/contact-form-salesforce-plugin/?utm_source=wordpress&amp;utm_medium=directory&amp;utm_campaign=salesforce_readme)

== How to Setup ==

* Go to "Salesforce Accounts" tab then add new account.
* Go to "Salesforce Feeds" tab then create new feed.
* Map required salesforce fields to contact form 7 fields.
* Send your test entry to Salesforce.
* Go to "Salesforce Logs" tab and verify, if entry was sent to Salesforce.

**Connect salesforce account**

Connect any contact form 7 to salesforce account by safe and secure Oauth 2.0. You can use simple "Web2Lead", if API access is disabled for your salesforce account.

**Map salesforce fields**

First select any salesforce object then Map contact form fields to salesforce object fields. There is No limitation on number of fields. You can map unlimited fields.

**Filter contact form 7 submissions**

Either Send all contact form 7 submissions to salesforce or filter contact form submissions sent to salesforce based on user input. For example , send only those entries to salesforce which have work email address.

**Manually send to salesforce**

Send contact form 7 submissions to salesforce when someone submits a contact form. You can manually send contact form submissions to salesforce.

**salesforce logs**

View a detailed log of each contact form 7 submission whether sent (or not sent) to salesforce and easily resend any contact form submission to salesforce.

**Send Data As salesforce object Notes**

Send one to many contact form fields as salesforce object notes when anyone submits a form on your site.

== Why we built this plugin ==

Contact Form 7 and some other popular contact forms are good but you can not send contact form submissions to any crm including salesforce. You can send any contact form submissions from your wordpress site to salesforce with this free wordpress salesforce plugin.


<blockquote><strong>Premium Version Features.</strong>

This plugin has a Premium version which comes with several additional benifits <a href="https://www.crmperks.com/plugins/contact-form-plugins/contact-form-salesforce-plugin/?utm_source=wordpress&amp;utm_medium=directory&amp;utm_campaign=salesforce_readme">Contact Form Salesforce Pro</a>.
<ul>
 	<li>Custom fields of Salesforce.</li>
 	<li>Phone number fields of any Salesforce  Object.</li>
 	<li>Salesforce Custom Objects.</li>
 	<li>Upload attachments to "Files" section of Salesforce.</li>
 	<li>Assign account to a contact in salesforce.</li>
 	<li>Add a contact and lead to Salesforce Campaign.</li>
 	<li>Assign salesforce object(Contact, account etc) created by one feed to other objects.</li>
 	<li>Google Analytics Parameters and Geolocation of a visitor who submitted the form.</li>
 	<li>Lookup lead's email using email lookup apis.</li>
 	<li>Verify lead's phone number and get detailed information using phone lookup apis.</li>
 	<li>20+ premium addons</li>
</ul>
</blockquote>

== Need Salesforce Plugin for Woocommerce ? ==

We have Salesforce add-on for Woocommerce. [Woocommerce Salesforce Integration](https://wordpress.org/plugins/woo-salesforce-plugin-crm-perks/)


== Requirements ==

Salesforce API access is enabled by default for following editions

* Enterprise Edition
* Unlimited Edition
* Developer Edition
* Performance Edition

You will have to enable Salesforce API for following editions

* Contact Edition
* Group Edition
* Professional Edition

== Want to send data to other crm ==
We have Premium Extensions for 20+ CRMs.[View All CRM Extensions](https://www.crmperks.com/plugin-category/contact-form-plugins/?utm_source=wordpress&amp;utm_medium=directory&amp;utm_campaign=salesforce_readme)

== Screenshots ==

1. Connect salesforce Account.
2. Map salesforce fields.
3. salesforce logs.
4. Send Contact form entry from wordpress install to salesforce by Free Contact Form Entries Plugin.
5. Get email infomation from Full Contact(Premium feature).
6. Get Customer geolocation, browser and OS (Premium feature).


== Frequently Asked Questions ==

= Where can I get support? =

Our team provides free support at <a href="https://www.crmperks.com/contact-us/">https://www.crmperks.com/contact-us/</a>.

= How to integrate salesforce in contact form 7 =

You can easily integrate salesforce in contact form 7. Simply install Contact Form 7 Salesforce plugin , Connect salesforce account and finally map contact form fields to salesforce object(contact,lead,account etc) fields.

= Contact form 7 salesforce web to lead =

if API access is not enabled for your organization then simply use salesforce web to lead. Enter your organization id and map contact form fields to salesforce case or lead fields.

= Add custom data in web to lead form =

No need to add hidden fields in contact form 7. Easily add your custom data/modify form data with "crmperks_salesforce_post" hook.

= Contact form 7 salesforce integration =

Easily integrate conatct form 7 to salesforce with free Wordpress Contact Form 7 salesforce Plugin. Connect salesforce account and map already existing contact form fields to salesforce object fields.

= Can i use this plugin if Salesforce API is not enabled =

Yes, you can use web to lead for creating leads and web to case for creating a case in salesforce.

* Go to "Salesforce Account" and select integration method "Web-to-Lead or Web-to-Case"
* Enter Saleforce Organization id and Save it
* Go to "Salesforce Feeds" and map contact form 7 fields to salesforce lead or case fields.

== Changelog ==

= 1.1.1 =
* custom text option added in notes fields.
* added multiple primary keys feature.

= 1.1.0 =
* added file uploads support to "Files" section.

= 1.0.12 =
* fixed optin condition for checkboxes.

= 1.0.11 =
* fixed "single checkbox always sends 1 to salesforce".

= 1.0.10 =
* fixed checkbox field in salesforce web2lead.
* added files support.

= 1.0.9 =
* fixed array value in log detail.
* added support form mlti-select picklist field for web2lead.

= 1.0.8 =
* fixed feed id is undefined warning.

= 1.0.7 =
* fixed resend to salesforce feature.

= 1.0.6 =
* fixed salesforce addon fields.

= 1.0.5 =
* fixed salesforce web to lead log.

= 1.0.4 =
* added salesforce sandbox.

= 1.0.3 =
* fixed salesforce web to lead.

= 1.0.2 =
* fixed salesforce phone number fields.

= 1.0.1 =
* fixed contact form 7 only without contact form entries plugin.

= 1.0.0 =
* Initial release.