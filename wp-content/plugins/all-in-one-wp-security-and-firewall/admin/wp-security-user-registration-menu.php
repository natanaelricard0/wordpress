<?php
if(!defined('ABSPATH')){
    exit;//Exit if accessed directly
}

class AIOWPSecurity_User_Registration_Menu extends AIOWPSecurity_Admin_Menu
{
    var $menu_page_slug = AIOWPSEC_USER_REGISTRATION_MENU_SLUG;
    
    /* Specify all the tabs of this menu in the following array */
    var $menu_tabs;

    var $menu_tabs_handler = array(
        'tab1' => 'render_tab1',
        'tab2' => 'render_tab2',
        'tab3' => 'render_tab3',
        );
    
    function __construct() 
    {
        $this->render_menu_page();
    }
    
    function set_menu_tabs() 
    {
        $this->menu_tabs = array(
        'tab1' => __('Manual Approval', 'all-in-one-wp-security-and-firewall'),
        'tab2' => __('Registration Captcha', 'all-in-one-wp-security-and-firewall'),
        'tab3' => __('Registration Honeypot', 'all-in-one-wp-security-and-firewall'),
        );
    }

    /*
     * Renders our tabs of this menu as nav items
     */
    function render_menu_tabs() 
    {
        $current_tab = $this->get_current_tab();

        echo '<h2 class="nav-tab-wrapper">';
        foreach ( $this->menu_tabs as $tab_key => $tab_caption ) 
        {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->menu_page_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
        }
        echo '</h2>';
    }
    
    /*
     * The menu rendering goes here
     */
    function render_menu_page() 
    {
        echo '<div class="wrap">';
        echo '<h2>'.__('User Registration','all-in-one-wp-security-and-firewall').'</h2>';//Interface title
        $this->set_menu_tabs();
        $tab = $this->get_current_tab();
        $this->render_menu_tabs();
        ?>        
        <div id="poststuff"><div id="post-body">
        <?php 
        //$tab_keys = array_keys($this->menu_tabs);
        call_user_func(array($this, $this->menu_tabs_handler[$tab]));
        ?>
        </div></div>
        </div><!-- end of wrap -->
        <?php
    }
    
    function render_tab1()
    {
        global $aiowps_feature_mgr;
        global $aio_wp_security;
        include_once 'wp-security-list-registered-users.php'; //For rendering the AIOWPSecurity_List_Table
        $user_list = new AIOWPSecurity_List_Registered_Users();

        if(isset($_POST['aiowps_save_user_registration_settings']))//Do form submission tasks
        {
            $nonce=$_REQUEST['_wpnonce'];
            if (!wp_verify_nonce($nonce, 'aiowpsec-user-registration-settings-nonce'))
            {
                $aio_wp_security->debug_logger->log_debug("Nonce check failed on save user registration settings!",4);
                die("Nonce check failed on save user registration settings!");
            }

            //Save settings
            $aio_wp_security->configs->set_value('aiowps_enable_manual_registration_approval',isset($_POST["aiowps_enable_manual_registration_approval"])?'1':'');

            //Commit the config settings
            $aio_wp_security->configs->save_config();
            
            //Recalculate points after the feature status/options have been altered
            $aiowps_feature_mgr->check_feature_status_and_recalculate_points();

            $this->show_msg_updated(__('Settings were successfully saved', 'all-in-one-wp-security-and-firewall'));
        }
        
        if(isset($_REQUEST['action'])) //Do list table form row action tasks
        {
            if($_REQUEST['action'] == 'approve_acct'){ //Approve link was clicked for a row in list table
                $user_list->approve_selected_accounts(strip_tags($_REQUEST['user_id']));
            }
            
            if($_REQUEST['action'] == 'delete_acct'){ //Delete link was clicked for a row in list table
                $user_list->delete_selected_accounts(strip_tags($_REQUEST['user_id']));
            }

            if($_REQUEST['action'] == 'block_ip'){ //Block IP link was clicked for a row in list table
                $user_list->block_selected_ips(strip_tags($_REQUEST['ip_address']));
            }
        }


        ?>
        <h2><?php _e('User Registration Settings', 'all-in-one-wp-security-and-firewall')?></h2>
        <form action="" method="POST">
        <?php wp_nonce_field('aiowpsec-user-registration-settings-nonce'); ?>            
        <div class="postbox">
        <h3 class="hndle"><label for="title"><?php _e('Manually Approve New Registrations', 'all-in-one-wp-security-and-firewall'); ?></label></h3>
        <div class="inside">
        <div class="aio_blue_box">
            <?php
            echo '<p>'.__('If your site allows people to create their own accounts via the WordPress registration form, then you can minimize SPAM or bogus registrations by manually approving each registration.', 'all-in-one-wp-security-and-firewall').
            '<br />'.__('This feature will automatically set a newly registered account to "pending" until the administrator activates it. Therefore undesirable registrants will be unable to log in without your express approval.', 'all-in-one-wp-security-and-firewall').
            '<br />'.__('You can view all accounts which have been newly registered via the handy table below and you can also perform bulk activation/deactivation/deletion tasks on each account.', 'all-in-one-wp-security-and-firewall').'</p>';
            ?>
        </div>
        <?php
        //Display security info badge
        $aiowps_feature_mgr->output_feature_details_badge("manually-approve-registrations");
        $blog_id = get_current_blog_id(); 
        if (is_multisite() && !is_main_site( $blog_id ))
        {
           //Hide config settings if MS and not main site
           AIOWPSecurity_Utility::display_multisite_message();
        }
        else
        {
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Enable manual approval of new registrations', 'all-in-one-wp-security-and-firewall')?>:</th>
                <td>
                <input id="aiowps_enable_manual_registration_approval" name="aiowps_enable_manual_registration_approval" type="checkbox"<?php if($aio_wp_security->configs->get_value('aiowps_enable_manual_registration_approval')=='1') echo ' checked="checked"'; ?> value="1"/>
                <label for="aiowps_enable_manual_registration_approval" class="description"><?php _e('Check this if you want to automatically disable all newly registered accounts so that you can approve them manually.', 'all-in-one-wp-security-and-firewall'); ?></label>
                </td>
            </tr>            
        </table>
        <?php } //End if statement ?>
        <input type="submit" name="aiowps_save_user_registration_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall')?>" class="button-primary" />
        </div></div>
        </form>
        <div class="postbox">
        <h3 class="hndle"><label for="title"><?php _e('Approve Registered Users', 'all-in-one-wp-security-and-firewall'); ?></label></h3>
        <div class="inside">
            <?php
            //Fetch, prepare, sort, and filter our data...
            $user_list->prepare_items();
            ?>
            <form id="tables-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
            <?php
            $user_list->search_box(__('Search', 'all-in-one-wp-security-and-firewall'), 'search_user_registration');
            if (isset($_REQUEST["tab"])) {
                echo '<input type="hidden" name="tab" value="' . esc_attr($_REQUEST["tab"]) . '" />';
            }
            ?>
            <!-- Now we can render the completed list table -->
            <?php $user_list->display(); ?>
            </form>
        </div></div>
        <?php
    }
    
    function render_tab2()
    {
        global $aio_wp_security;
        global $aiowps_feature_mgr;
        
        if(isset($_POST['aiowpsec_save_registration_captcha_settings']))//Do form submission tasks
        {
            $error = '';
            $nonce=$_REQUEST['_wpnonce'];
            if (!wp_verify_nonce($nonce, 'aiowpsec-registration-captcha-settings-nonce'))
            {
                $aio_wp_security->debug_logger->log_debug("Nonce check failed on registration captcha settings save!",4);
                die("Nonce check failed on registration captcha settings save!");
            }


            //Save all the form values to the options
            $random_20_digit_string = AIOWPSecurity_Utility::generate_alpha_numeric_random_string(20); //Generate random 20 char string for use during captcha encode/decode
            $aio_wp_security->configs->set_value('aiowps_captcha_secret_key', $random_20_digit_string);
            $aio_wp_security->configs->set_value('aiowps_enable_registration_page_captcha',isset($_POST["aiowps_enable_registration_page_captcha"])?'1':'');
            $aio_wp_security->configs->save_config();
            
            //Recalculate points after the feature status/options have been altered
            $aiowps_feature_mgr->check_feature_status_and_recalculate_points();
            
            $this->show_msg_settings_updated();
        }
        ?>
        <div class="aio_blue_box">
            <?php
            echo '<p>'.__('This feature allows you to add a captcha form on the WordPress registration page.', 'all-in-one-wp-security-and-firewall').'
            <br />'.__('Users who attempt to register will also need to enter the answer to a simple mathematical question - if they enter the wrong answer, the plugin will not allow them to register.', 'all-in-one-wp-security-and-firewall').'
            <br />'.__('Therefore, adding a captcha form on the registration page is another effective yet simple SPAM registration prevention technique.', 'all-in-one-wp-security-and-firewall').'
            </p>';
            ?>
        </div>
        <div class="postbox">
        <h3 class="hndle"><label for="title"><?php _e('Registration Page Captcha Settings', 'all-in-one-wp-security-and-firewall'); ?></label></h3>
        <div class="inside">
        <?php
        if (is_multisite() && get_current_blog_id() != 1)
        {
            //Hide config settings if MS and not main site
            $special_msg = '<div class="aio_yellow_box">';
            $special_msg .= '<p>'.__('The core default behaviour for WordPress Multi Site regarding user registration is that all users are registered via the main site.','all-in-one-wp-security-and-firewall').'</p>';
            $special_msg .= '<p>'.__('Therefore, if you would like to add a captcha form to the registration page for a Multi Site, please go to "Registration Captcha" settings on the main site.','all-in-one-wp-security-and-firewall').'</p>';
            $special_msg .= '</div>';
            echo $special_msg;
        }
        else
        {
            //Display security info badge
            global $aiowps_feature_mgr;
            $aiowps_feature_mgr->output_feature_details_badge("user-registration-captcha");
            ?>

            <form action="" method="POST">
        <?php wp_nonce_field('aiowpsec-registration-captcha-settings-nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Enable Captcha On Registration Page', 'all-in-one-wp-security-and-firewall')?>:</th>
                    <td>
                    <input id="aiowps_enable_registration_page_captcha" name="aiowps_enable_registration_page_captcha" type="checkbox"<?php if($aio_wp_security->configs->get_value('aiowps_enable_registration_page_captcha')=='1') echo ' checked="checked"'; ?> value="1"/>
                    <label for="aiowps_enable_registration_page_captcha" class="description"><?php _e('Check this if you want to insert a captcha form on the WordPress user registration page (if you allow user registration).', 'all-in-one-wp-security-and-firewall'); ?></label>
                    </td>
                </tr>            
            </table>
            <input type="submit" name="aiowpsec_save_registration_captcha_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall')?>" class="button-primary" />
            </form>
            </div></div>        
        <?php
        }
    }
 
    function render_tab3()
    {
        global $aio_wp_security;
        global $aiowps_feature_mgr;
        
        if(isset($_POST['aiowpsec_save_registration_honeypot_settings']))//Do form submission tasks
        {
            $error = '';
            $nonce=$_REQUEST['_wpnonce'];
            if (!wp_verify_nonce($nonce, 'aiowpsec-registration-honeypot-settings-nonce'))
            {
                $aio_wp_security->debug_logger->log_debug("Nonce check failed on registration honeypot settings save!",4);
                die("Nonce check failed on registration honeypot settings save!");
            }

            //Save all the form values to the options
            $aio_wp_security->configs->set_value('aiowps_enable_registration_honeypot',isset($_POST["aiowps_enable_registration_honeypot"])?'1':'');
            $aio_wp_security->configs->save_config();
            
            //Recalculate points after the feature status/options have been altered
            $aiowps_feature_mgr->check_feature_status_and_recalculate_points();
            
            $this->show_msg_settings_updated();
        }
        ?>
        <div class="aio_blue_box">
            <?php
            echo '<p>'.__('This feature allows you to add a special hidden "honeypot" field on the WordPress registration page. This will only be visible to robots and not humans.', 'all-in-one-wp-security-and-firewall').'
            <br />'.__('Since robots usually fill in every input field from a registration form, they will also submit a value for the special hidden honeypot field.', 'all-in-one-wp-security-and-firewall').'
            <br />'.__('The way honeypots work is that a hidden field is placed somewhere inside a form which only robots will submit. If that field contains a value when the form is submitted then a robot has most likely submitted the form and it is consequently dealt with.', 'all-in-one-wp-security-and-firewall').'
            <br />'.__('Therefore, if the plugin detects that this field has a value when the registration form is submitted, then the robot which is attempting to register on your site will be redirected to its localhost address - http://127.0.0.1.', 'all-in-one-wp-security-and-firewall').'
            </p>';
            ?>
        </div>
        <form action="" method="POST">
        <div class="postbox">
        <h3 class="hndle"><label for="title"><?php _e('Registration Form Honeypot Settings', 'all-in-one-wp-security-and-firewall'); ?></label></h3>
        <div class="inside">
        <?php
        //Display security info badge
        global $aiowps_feature_mgr;
        $aiowps_feature_mgr->output_feature_details_badge("registration-honeypot");
        ?>

        <?php wp_nonce_field('aiowpsec-registration-honeypot-settings-nonce'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Enable Honeypot On Registration Page', 'all-in-one-wp-security-and-firewall')?>:</th>
                <td>
                <input id="aiowps_enable_registration_honeypot" name="aiowps_enable_registration_honeypot" type="checkbox"<?php if($aio_wp_security->configs->get_value('aiowps_enable_registration_honeypot')=='1') echo ' checked="checked"'; ?> value="1"/>
                <label for="aiowps_enable_registration_honeypot" class="description"><?php _e('Check this if you want to enable the honeypot feature for the registration page', 'all-in-one-wp-security-and-firewall'); ?></label>
                </td>
            </tr>            
        </table>
        </div></div>        
     
        <input type="submit" name="aiowpsec_save_registration_honeypot_settings" value="<?php _e('Save Settings', 'all-in-one-wp-security-and-firewall')?>" class="button-primary" />
        </form>
        <?php
    }   
        
} //end class