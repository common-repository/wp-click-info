=== Plugin Name ===
Contributors: saquery.com
Donate link: http://saquery.com/wordpress/
Tags: Click Counter, Link Analytics
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 2.7.4

Easy to use Click Counter and external Link Click Analytics for Wordpress. This Plugin does not make use of an irritating redirect Page.

== Description ==

Counts User Clicks on all external links via Ajax. This plugin needs no annoying and irritating redirect Page to count the Clicks of your external links.
By default all external links will become the target="_blank" attribute and a nice Wiki Like Image via CSS to visualize your visitors that this is an external link.

In addition there are top reports available in the administration area.
Top Target urls, Top Referrer urls, Top Target by Referrer, Top Referrer by Target and a Top 10 Targets report in the Dashboard.

== Changelog ==
<ul>
	<span>2.7.4</span>
	<ul>
		<li>Does not add external link icon to linked images.</li> 
		<li>Fixed a bug when "Do not open a new window" option was enabled. The plugin tracked always "javascript:void(0);" instead of correct href.</li> 
		<li>Tested up to: 3.4.2</li> 
	</ul>

	<span>2.7.3</span>
	<ul>
		<li>Added autocomplete suggestion to reporting textfilter (Exclude) textbox!</li> 
	</ul>

	<span>2.7.1</span>
	<ul>
		<li>New function : Live Tracking!</li> 
		<li>New function : css class "igno" can be used to ignore special links.</li> 
	</ul>
	
	<span>2.6.1</span>
	<ul>
		<li>Front end reports. Please take a look at http://saquery.com/wordpress/wp-click-info/ for more infos.</li> 
	</ul>

	<span>2.5.8</span>
	<ul>
		<li>Improved report-listing by ranking number for a better overview.</li> 
		<li>Improved reportings by text-filter for inclusion and/or exclusion of matched items.</li> 
		<li>Added a custom limit of Items for report-listings.</li> 
	</ul>

	<span>2.4.7</span>
	<ul>
		<li>Added an optional Date-Range-Filter to top target/referer reports. You can now choose a start date and an enddate to external link click analytics.</li> 
	</ul>

	<span>2.3.6</span>
	<ul>
		<span>New User Options:</span>
		<li>Record Admin User Clicks.</li>
		<li>Record Registered User Clicks.</li>
	</ul>
</ul>

<ul>
	<span>2.2.5</span>
	<ul>
		<li>Finaly fixed postition of legend for Timeline.</li>
		<li>Improved Plugin Header with a link to the Changelog page.</li>
	</ul>
</ul>

<ul>
	<span>2.2.4</span>
	<ul>
		<li>Fixed postition of legend for Timeline.</li>
	</ul>
</ul>

<ul>
	<span>2.2.3</span>
	<ul>
		<li>Hide statistcs in external link titles.</li>
		<li>Donation in dashboard is now hidden by default.</li>
		<li>Sidebarmenuitem title fixed.</li>
	</ul>
</ul>

<ul>
	<span>2.1.3</span>
	<ul>
		<li>Improved title attribute of external links in front end with more informations.</li>
		<li>Date/Time info of last click.</li>
		<li>Number of total clicks.</li>
	</ul>
</ul>

<ul>
	<span>2.0.1</span>
	<ul>
		<li>Fixed SQL selector bug for timeline.</li>
		<li>Improvements for new timeline function.</li>
	</ul>
</ul>

<ul>
	<span>2.0</span>
	<ul>
		<li>Added a prototype of a WP-Click-Info Timeline. The first one shows the top targets within the last month. </li>
		<li>Zoom into view by select an area within the chart.</li>
		<li>Double Click to Zoom out.</li>
	</ul>
</ul>

<ul>
	<span>1.2.3</span>
	<ul>
		<span>New User Option:</span>
		<li>Reset Database Records.</li>
	</ul>
</ul>

<ul>
	<span>1.2.2</span>
	<ul>
		<span>New User Options:</span>
		<li>Do not open a new window - To avoid waiting times for your visitors it is recommended that this option is disabled.</li>
		<li>Hide external link icons</li>
	</ul>
</ul>
<ul>
	<span>1.2.1</span>
	<li>Fixed external link detection for Javascript links, # anchor and empty links.</li>
</ul>
<ul>
	<span>1.2</span>
	<li>Fixed wrong header text for click reportings in admin' s backend.</li>
	<li>Improved click reportings table layout for a better overview.</li>
</ul>
<ul>
	<span>1.1</span>
	<li>Fixed Documentation.</li>
</ul>

<ul>
	<span>1.0</span>
	<li>Initial Import.</li>
</ul>

== Frequently Asked Questions ==

= When I activate the plugin WP Click Info write the following error: "Fatal error: Allowed memory size of xyz bytes exhausted (tried to allocate xyz bytes) in .../wp-admin/includes/schema.php" =

Add a memora limit to the top of your wp-config.
ini_set("memory_limit",'64M');

== Installation ==

This section describes how to install the plugin and get it working.

   1. Upload wp-click-ckeck.php to the /wp-content/plugins/wp-click-info/ directory.
   2. Activate the plugin through the ‘Plugins’ menu in WordPress


== Screenshots ==

1. Backend
2. Frontend
3. Dashboard
4. Options
5. Timeline
6. Reportfilter by domain-name
7. Post Editor
8. Front-End
9. Live-Tracking