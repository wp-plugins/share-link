<div class="wrap sharelink">
    <h2><img src="<?php echo plugins_url('images/icon-sharelink-32px.png', __FILE__); ?>" /> Share Link for Wordpress > Help</h2>
    <div class="clear"></div>

    <h2>Share/Commodity/Currencies Short Tags and Widgets</h2>

    <h3>Overview</h3>

    <p>You can place the share, commodity and currency using a short tag directly within your content. The content can then be styled as per the instructions provided to you
    at purchase time or <a href="http://sharelink.com.au/download/Share-Link-Install-Instructions.pdf" target="_new">downloaded here</a>.</p>

    <h3>Short Tags</h3>

    <p>To include Share Link data anywhere in your website pages or posts simply edit your pages as normal and then insert one or more of the following tags at the point where you want the corresponding data to appear.</p>
    <br /><br />

    <table width="100%">
    <tr>
        <td><strong>Short Tag</strong></td>
        <td><strong>Description</strong></td>
        <td><strong>Example</strong></td>
    </tr>
    <tr>
        <td>*[sharelink-box]</td>
        <td>Displays the stock code, price and movement in the standard Share Link box/vertical style</td>
        <td><img src="<?php echo plugins_url('images/sl-box-example.png', __FILE__); ?>" /></td>
    </tr>
    <tr>
        <td>[sharelink-strip]</td>
        <td>Displays the stock code, price and movement in a horizontal or strip format</td>
        <td><img src="<?php echo plugins_url('images/sl-strip-example.png', __FILE__); ?>" /></td>
    </tr>
    <tr>
        <td>[sharelink-table]</td>
        <td>Displays a complete list of all stock prices, commodities and exchange rates in a table</td>
        <td><img src="<?php echo plugins_url('images/sl-multi-example.png', __FILE__); ?>" /></td>
    </tr>
    <tr>
        <td>[sharelink-graph CODE]</td>
        <td>Displays a graph of the share price of the code selected. <br />
            <i>Usage: [sharelink-graph CODE] - Displays the graph for code CODE</i>
        </td>
        <td><img src="<?php echo plugins_url('images/sl-graph-example.png', __FILE__); ?>" /></td>
    </tr>
    <tr>
        <td>[sharelink-graph-3 CODE]</td>
        <td>Displays a graph of the share price of the code selected. Uses the version 3 renderer. <br />
            <i>Usage: [sharelink-graph-3] - Displays the graph for code CODE</i>
        </td>
        <td><img src="<?php echo plugins_url('images/sl-graph-3-example.png', __FILE__); ?>" /></td>
    </tr>
    </table>

    <h2>ASX Announcements</h2>

    <h3>Overview</h3>

    <p>The Share Link Gold plugin allows you to configure where and how your ASX Announcements will appear within your Wordpress enabled website. These instructions cover the use of this plugin only, for instructions styling your share price use the PDF document supplied to you at purchase time or <a href="http://sharelink.com.au/download/Share-Link-Install-Instructions.pdf" target="_new">download here</a>.</p>

    <h3>Displaying ASX Announcements – Short Tag</h3>

    <p>The short tag technique is best used when you wish to display all ASX Announcements for a company this is usually the case on an Investors or dedicated ASX Announcements page. For a home page summary box style display we suggest using the included widget (documentation below).</p>

    <p>To add a list of ASX Announcements to a page simply use the Wordpress short-tag  [sharelink-asx] on the page or post where you want the details to occur:</p>

    <img src="<?php echo plugins_url('images/short-code-screen.png', __FILE__); ?>" />


    <p>This will result in a list of  ASX Announcements appearing in place of the short tag.</p>

    <img src="<?php echo plugins_url('images/asx-announcements-screen.png', __FILE__); ?>" />

    <h3>Displaying ASX Announcements – Widget</h3>

    <p>The Share Link plugin also includes a sidebar widget that can be included in your Wordpress Theme’s sidebar simply by dragging and dropping it in from the Appearance -> Widgets section of the Wordpress administration console.</p>

    <p>The widget has one configuration value, the number of ASX Announcements to display. The result will be an unordered list of the latest x announcements ordered from most to least recent with the title being a link to the PDF. This can be further styled using the CSS techniques described below.</p>

    <h3>Configuring how announcements are displayed</h3>

    <p>The plugin allows you to control how Share Link displays ASX Announcements on your website and further fine-grain control can be done using CSS. To start simply click on the “Share Link” menu item on the left hand side of the Wordpress admin window. From here you will see a number of configuration options:</p>

    <img src="<?php echo plugins_url('images/settings-screen.png', __FILE__); ?>" />

    <p>Details of each option are available below:</p>

    <table>
        <tr>
            <td><strong>Display Type:</strong></td><td>Allows you to select the HTML elements used to display ASX Announcements. This can be set to a table element with a row for each announcement, or an unordered list with a list item for each individual announcement. The choice is purely preferential.</td></tr>
        <tr>
            <td><strong>Date Format:</strong></td><td>Choose how you wish the publication date of the announcement to be displayed (eg. dd/mm/yyyy) </td></tr>
        <tr>
            <td><strong>Display Announcements by Year:</strong></td><td>Groups ASX Announcements into their individual years and allows users to filter the list via a drop down menu for all available years.</td></tr>
        <tr>
            <td><strong>Display by Month:</strong></td><td>Similar to “Display By Year” this option allows further grouping of announcements on a month by month basis (eg. November 2012, December 2012). It is recommended only when a company has a lot of announcements each month.</td></tr>
        <tr>
            <td><strong>Display by Month Header:</strong></td><td>Choose the format of the display by month header (only displayed if “Display by Month” is set to yes.</td></tr>
        <tr>
            <td><strong>Include Pagination:</strong></td><td>Breaks announcements down so that a limited number show per page. This is additional to any grouping/pagination that occurs from selecting “Display By Year”. It is highly recommended to have pagination turned on.</td></tr>
        <tr>
            <td><strong>Announcements per page:</strong></td><td>If pagination is turned on this value will be the number of announcements shown on a page. Defaults to 12 if no value entered.</td></tr>
    </table>

    <h3>Updating Announcement display with CSS</h3>

    <p>Share Link uses standard HTML and CSS to format its data and as such you can use CSS to change the appearance of the announcements to match your site.</p>

    <p>Based on the “display type” setting set on the main configuration page the table or unordered list containing the ASX announcements will  be wrapped in a DIV with an ID of “sl-asx-list” which means you can uniquely apply styles to this element and all sub elements as follows:</p>

    <p><i>Table</i></p>
    <code>
        # sl-asx-list table {
        /* your css code goes here */
        }
    </code>
    <br /><br />
    <code>
        # sl-asx-list table tr {
        /* your css code to customize the look of a table row goes here */
        }
    </code>

    <p><i>Unordered List</i></p>
    <code>
        # sl-asx-list ul {
        /* your css code goes here */
        }</code>
    <br /><br />
    <code>
        # sl-asx-list ul li {
        /* your css code to customize the look of a table row goes here */
        }
    </code>
    <p>There are a number of other CSS elements which are included based on the configuration options selected including month headers and pagination elements all of which have class names. We suggest using a tool such as <a href="https://getfirebug.com/" target="_blank">firebug</a> to examine the code generated by Share Link and customizing the CSS code to suit.</p>

    <h3>Managing ASX Announcements</h3>
    <p>ASX Announcements should always reflect those posted to the ASX and as such cannot be managed from within the Share Link plugin. You can however access the ASX Announcement PDF files Share Link creates via Wordpress’s standard media library to link to the documents throughout your site.</p>

    <p><strong>Note:</strong> If you delete an ASX Announcement from the current year it will automatically be added next time Share Link updates. Any documents deleted from previous years will need to be retrieved manually and a re-establishment fee will be charged.</p>

    <h3>Further reading</h3>
    <p>Got a query that wasn't answered here? Try the <a href="http://sharelink.com.au" target="_blank">Share Link website</a>.</p>

    <p><a href="http://sharelink.com.au" target="_blank">Share Link</a> is a product of <a href="http://harmonicnewmedia.com" target="_blank">Harmonic New Media</a></p>
</div>