<div class="wrap sharelink">
    <h2><img src="<?php echo plugins_url('images/icon-sharelink-32px.png', __FILE__); ?>" /> Share Link for Wordpress > Install</h2>
    <div class="clear"></div>

    <p><strong>Thank you for downloading the Share Link Gold Wordpress Plugin!</strong></p>

<p>This plugin allows you to configure your Share Link subscription by controlling how you display ASX Announcements on your company’s website. In order to use the plugin you will require a valid Share Link gold subscription.</p>

<p>If you have already purchased a license please enter the provided key in the box below and click install.</p>

<p>No subscription? No worries! Simply visit www.sharelink.com.au and fill in your details and we can get you up and running in no time.</p>

    <form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
        <input type="hidden" name="install-plugin" />

        <fieldset>
            <legend>Share Link Settings</legend>
            <label>License Key</label>
            <input type="text" name="license-key" size="30" value="<?php if (isset($_POST["license-key"])) { echo $_POST["license-key"]; } ?>" /><br />

        </fieldset>
        <input type="submit" value="Install" class="button-primary" />
    </form>
</div>

