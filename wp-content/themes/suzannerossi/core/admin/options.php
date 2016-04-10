<?php

$gt3_tabs_admin_theme = new Tabs_admin_theme();

$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('General', 'theme_localization'),
    'desc' => '',
    'icon' => 'general.png',
    'icon_active' => 'general_active.png',
    'icon_hover' => 'general_hover.png'
), array(
    new UploadOption_admin_theme(array(
        'name' => __('Header logo', 'theme_localization'),
        'id' => 'logo',
        'desc' => 'Default: 190px x 90px',
        'default' => THEMEROOTURL . '/img/logo.png'
    )),
    new UploadOption_admin_theme(array(
        'name' => __('Logo (Retina)', 'theme_localization'),
        'id' => 'logo_retina',
        'desc' => 'Default: 380px x 180px',
        'default' => THEMEROOTURL . '/img/retina/logo.png'
    )),
    new textOption_admin_theme(array(
        'name' => __('Header logo width', 'theme_localization'),
        'id' => 'header_logo_standart_width',
        'not_empty' => true,
        'width' => '100px',
        'textalign' => 'center',
        'default' => '190'
    )),
    new textOption_admin_theme(array(
        'name' => __('Header logo height', 'theme_localization'),
        'id' => 'header_logo_standart_height',
        'not_empty' => true,
        'width' => '100px',
        'textalign' => 'center',
        'default' => '90'
    )),
    new UploadOption_admin_theme(array(
        'type' => 'upload',
        'name' => __('Favicon', 'theme_localization'),
        'id' => 'favicon',
        'desc' => 'Icon must be 16x16px or 32x32px',
        'default' => THEMEROOTURL . '/img/favicon.ico'
    )),
    new UploadOption_admin_theme(array(
        'type' => 'upload',
        'name' => __('Apple touch icon (57px)', 'theme_localization'),
        'id' => 'apple_touch_57',
        'desc' => 'Icon must be 57x57px',
        'default' => THEMEROOTURL . '/img/apple_icons_57x57.png'
    )),
    new UploadOption_admin_theme(array(
        'type' => 'upload',
        'name' => __('Apple touch icon (72px)', 'theme_localization'),
        'id' => 'apple_touch_72',
        'desc' => 'Icon must be 72x72px',
        'default' => THEMEROOTURL . '/img/apple_icons_72x72.png'
    )),
    new UploadOption_admin_theme(array(
        'type' => 'upload',
        'name' => __('Apple touch icon (114px)', 'theme_localization'),
        'id' => 'apple_touch_114',
        'desc' => 'Icon must be 114x114px',
        'default' => THEMEROOTURL . '/img/apple_icons_114x114.png'
    )),
    new TextareaOption_admin_theme(array(
        'name' => __('Google analytics or any other code<br>(before &lt;/head&gt;)', 'theme_localization'),
        'id' => 'code_before_head',
        'default' => ''
    )),
    new TextareaOption_admin_theme(array(
        'name' => __('Any code <br>(before &lt;/body&gt;)', 'theme_localization'),
        'id' => 'code_before_body',
        'default' => ''
    )),
    new TextareaOption_admin_theme(array(
        'name' => __('Copyright', 'theme_localization'),
        'id' => 'copyright',
        'default' => 'Copyright &copy; Black Diamond. <br>All Rights Reserved'
    )),
    new AjaxButtonOption_admin_theme(array(
        'title' => 'Import Sample Data',
        'id' => 'action_import',
        'name' => __('Import demo content', 'theme_localization'),
        'confirm' => TRUE,
        'data' => array(
            'action' => 'ajax_import_dump'
        )
    ))
)));

$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('Sidebars', 'theme_localization'),
    'desc' => '',
    'icon' => 'sidebars.png',
    'icon_active' => 'sidebars_active.png',
    'icon_hover' => 'sidebars_hover.png'
), array(
    new SelectOption_admin_theme(array(
        'name' => __('Default sidebar layout', 'theme_localization'),
        'id' => 'default_sidebar_layout',
        'desc' => '',
        'default' => 'right-sidebar',
        'options' => array(
            'left-sidebar' => __('Left sidebar', 'theme_localization'),
            'right-sidebar' => __('Right sidebar', 'theme_localization'),
            'no-sidebar' => __('Without sidebar', 'theme_localization')
        )
    )),
    new SidebarManager_admin_theme(array(
        'name' => __('Sidebar manager', 'theme_localization'),
        'id' => 'sidebar_manager',
        'desc' => ''
    ))
)));

$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('Fonts', 'theme_localization'),
    'desc' => '',
    'icon' => 'fonts.png',
    'icon_active' => 'fonts_active.png',
    'icon_hover' => 'fonts_hover.png'
), array(
    new FontSelector_admin_theme(array(
        'name' => __('Content font', 'theme_localization'),
        'id' => 'main_font',
        'desc' => '',
        'default' => 'Roboto',
        'options' => get_fonts_array_only_key_name()
    )),
    new textOption_admin_theme(array(
        'name' => __('Main font parameters', 'theme_localization'),
        'id' => 'google_font_parameters_main_font',
        'not_empty' => true,
        'default' => ':400',
        'width' => '100%',
        'textalign' => 'left',
        'desc' => 'Google font. Click <a href="https://developers.google.com/webfonts/docs/getting_started" target="_blank">here</a> for help.'
    )),
    new FontSelector_admin_theme(array(
        'name' => __('Main menu font', 'theme_localization'),
        'id' => 'main_menu_font',
        'desc' => '',
        'default' => 'Roboto',
        'options' => get_fonts_array_only_key_name()
    )),
    new textOption_admin_theme(array(
        'name' => __('Main menu font parameters', 'theme_localization'),
        'id' => 'google_font_parameters_main_font',
        'not_empty' => true,
        'default' => ':400',
        'width' => '100%',
        'textalign' => 'left',
        'desc' => 'Google font. Click <a href="https://developers.google.com/webfonts/docs/getting_started" target="_blank">here</a> for help.'
    )),
    new FontSelector_admin_theme(array(
        'name' => __('Headers', 'theme_localization'),
        'id' => 'text_headers_font',
        'desc' => '',
        'default' => 'Muli',
        'options' => get_fonts_array_only_key_name()
    )),
    new textOption_admin_theme(array(
        'name' => __('Headers font parameters', 'theme_localization'),
        'id' => 'google_font_parameters_headers_font',
        'not_empty' => true,
        'default' => ':400',
        'width' => '100%',
        'textalign' => 'left',
        'desc' => 'Google font. Click <a href="https://developers.google.com/webfonts/docs/getting_started" target="_blank">here</a> for help.'
    )),
    new textOption_admin_theme(array(
        'name' => __('Content font weight', 'theme_localization'),
        'id' => 'content_weight',
        'not_empty' => true,
        'default' => '400',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('Headings font weight', 'theme_localization'),
        'id' => 'headings_weight',
        'not_empty' => true,
        'default' => '400',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('Main menu font size', 'theme_localization'),
        'id' => 'menu_font_size',
        'not_empty' => true,
        'default' => '11px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('H1 font size', 'theme_localization'),
        'id' => 'h1_font_size',
        'not_empty' => true,
        'default' => '26px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('H2 font size', 'theme_localization'),
        'id' => 'h2_font_size',
        'not_empty' => true,
        'default' => '22px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('H3 font size', 'theme_localization'),
        'id' => 'h3_font_size',
        'not_empty' => true,
        'default' => '19px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('H4 font size', 'theme_localization'),
        'id' => 'h4_font_size',
        'not_empty' => true,
        'default' => '16px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('H5 font size', 'theme_localization'),
        'id' => 'h5_font_size',
        'not_empty' => true,
        'default' => '14px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('H6 font size', 'theme_localization'),
        'id' => 'h6_font_size',
        'not_empty' => true,
        'default' => '13px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('Content font size', 'theme_localization'),
        'id' => 'main_content_font_size',
        'not_empty' => true,
        'default' => '12px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
    new textOption_admin_theme(array(
        'name' => __('Content line height', 'theme_localization'),
        'id' => 'main_content_line_height',
        'not_empty' => true,
        'default' => '20px',
        'width' => '100px',
        'textalign' => 'center',
        'desc' => ''
    )),
)));


$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('Socials', 'theme_localization'),
    'icon' => 'social.png',
    'icon_active' => 'social_active.png',
    'icon_hover' => 'social_hover.png'
), array(
    new TextOption_admin_theme(array(
        'name' => __('Facebook', 'theme_localization'),
        'id' => 'social_facebook',
        'default' => 'http://facebook.com',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Flickr', 'theme_localization'),
        'id' => 'social_flickr',
        'default' => 'http://flickr.com',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Tumblr', 'theme_localization'),
        'id' => 'social_tumblr',
        'default' => 'http://tumblr.com',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Instagram', 'theme_localization'),
        'id' => 'social_instagram',
        'default' => 'http://instagram.com',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Twitter', 'theme_localization'),
        'id' => 'social_twitter',
        'default' => 'http://twitter.com',
        'desc' => 'Please specify http:// to the URL'
    )),

    new TextOption_admin_theme(array(
        'name' => __('Youtube', 'theme_localization'),
        'id' => 'social_youtube',
        'default' => 'https://www.youtube.com/',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Dribbble', 'theme_localization'),
        'id' => 'social_dribbble',
        'default' => 'http://dribbble.com/',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Google+', 'theme_localization'),
        'id' => 'social_gplus',
        'default' => 'https://plus.google.com/',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Vimeo', 'theme_localization'),
        'id' => 'social_vimeo',
        'default' => 'https://vimeo.com/',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Delicious', 'theme_localization'),
        'id' => 'social_delicious',
        'default' => 'https://delicious.com/',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Linked In', 'theme_localization'),
        'id' => 'social_linked',
        'default' => 'https://www.linkedin.com/',
        'desc' => 'Please specify http:// to the URL'
    )),
    new TextOption_admin_theme(array(
        'name' => __('Pinterest', 'theme_localization'),
        'id' => 'social_pinterest',
        'default' => 'http://pinterest.com',
        'desc' => 'Please specify http:// to the URL'
    )),
)));


$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('Contacts', 'theme_localization'),
    'icon' => 'contacts.png',
    'icon_active' => 'contacts_active.png',
    'icon_hover' => 'contacts_hover.png'
), array(
    new TextOption_admin_theme(array(
        'name' => __('Send mails to', 'theme_localization'),
        'id' => 'contacts_to',
        'default' => get_option("admin_email")
    )),
    new TextOption_admin_theme(array(
        'name' => __('Phone number', 'theme_localization'),
        'id' => 'phone',
        'default' => '+1 800 789 50 12'
    )),
)));


$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('View Options', 'theme_localization'),
    'icon' => 'layout.png',
    'icon_active' => 'layout_active.png',
    'icon_hover' => 'layout_hover.png'
), array(
    new SelectOption_admin_theme(array(
        'name' => __('Responsive', 'theme_localization'),
        'id' => 'responsive',
        'desc' => '',
        'default' => 'on',
        'options' => array(
            'on' => __('On', 'theme_localization'),
            'off' => __('Off', 'theme_localization')
        )
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Theme color', 'theme_localization'),
        'id' => 'theme_color1',
        'desc' => '',
        'not_empty' => 'true',
        'default' => '00d8ff'
    )),	
    new UploadOption_admin_theme(array(
        'type' => 'upload',
        'name' => __('Default background image', 'theme_localization'),
        'id' => 'bg_img',
        'desc' => '',
        'default' => THEMEROOTURL . '/img/def_bg.jpg'
    )),
    new UploadOption_admin_theme(array(
        'type' => 'upload',
        'name' => __('404 page background image', 'theme_localization'),
        'id' => 'bg_404',
        'desc' => '',
        'default' => THEMEROOTURL . '/img/404_bg.jpg'
    )),
    new textOption_admin_theme(array(
        'name' => __('Fullscreen blog Items per page', 'theme_localization'),
        'id' => 'fw_posts_per_page',
        'not_empty' => true,
        'width' => '100px',
        'textalign' => 'center',
        'default' => '14'
    )),
    new textOption_admin_theme(array(
        'name' => __('Fullscreen Portfolio Items per page', 'theme_localization'),
        'id' => 'fw_port_per_page',
        'not_empty' => true,
        'width' => '100px',
        'textalign' => 'center',
        'default' => '20'
    )),
    new SelectOption_admin_theme(array(
        'name' => __('Related Posts', 'theme_localization'),
        'id' => 'related_posts',
        'desc' => '',
        'default' => 'on',
        'options' => array(
            'on' => __('On', 'theme_localization'),
            'off' => __('Off', 'theme_localization')
        )
    )),
    new SelectOption_admin_theme(array(
        'name' => __('Default portfolio posts style', 'theme_localization'),
        'id' => 'default_portfolio_style',
        'desc' => '',
        'default' => 'fw-portfolio-post',
        'options' => array(
            'fw-portfolio-post' => __('Fullscreen Slider', 'theme_localization'),
			'ribbon-portfolio-post' => __('Fullscreen Ribbon', 'theme_localization'),
            'simple-portfolio-post' => __('Simple', 'theme_localization')
        )
    )),	
    new SelectOption_admin_theme(array(
        'name' => __('Portfolio comments', 'theme_localization'),
        'id' => 'portfolio_comments',
        'desc' => '',
        'default' => 'disabled',
        'options' => array(
            'disabled' => __('Disabled', 'theme_localization'),
            'enabled' => __('Enabled', 'theme_localization')
        )
    )),
    new SelectOption_admin_theme(array(
        'name' => __('Page comments', 'theme_localization'),
        'id' => 'page_comments',
        'desc' => '',
        'default' => 'disabled',
        'options' => array(
            'disabled' => __('Disabled', 'theme_localization'),
            'enabled' => __('Enabled', 'theme_localization')
        )
    )),
    new TextareaOption_admin_theme(array(
        'name' => __('Custom CSS', 'theme_localization'),
        'id' => 'custom_css',
        'default' => ''
    )),
)));

$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('Gallery Options', 'theme_localization'),
    'icon' => 'landing.png',
    'icon_active' => 'landing_active.png',
    'icon_hover' => 'landing_hover.png'
), array(
    new SelectOption_admin_theme(array(
        'name' => __('Fit Style', 'theme_localization'),
        'id' => 'default_fit_style',
        'desc' => '',
        'default' => 'no_fit',
        'options' => array(
            'no_fit' => __('Cover Slide', 'theme_localization'),
			'fit_always' => __('Fit Always', 'theme_localization'),
            'fit_width' => __('Fit Horizontal', 'theme_localization'),
			'fit_height' => __('Fit Vertical', 'theme_localization')
        )
    )),	
    new SelectOption_admin_theme(array(
        'name' => __('Show Controls', 'theme_localization'),
        'id' => 'default_controls',
        'desc' => '',
        'default' => 'on',
        'options' => array(
            'on' => __('Yes', 'theme_localization'),
            'off' => __('No', 'theme_localization')
        )
    )),
    new SelectOption_admin_theme(array(
        'name' => __('Autoplay', 'theme_localization'),
        'id' => 'default_autoplay',
        'desc' => '',
        'default' => 'on',
        'options' => array(
            'on' => __('Yes', 'theme_localization'),
            'off' => __('No', 'theme_localization')
        )
    )),
    new textOption_admin_theme(array(
        'name' => __('Slide Interval In Milliseconds', 'theme_localization'),
        'id' => 'gallery_interval',
        'not_empty' => true,
        'width' => '100px',
        'textalign' => 'center',
        'default' => '3000'
    ))
)));

$gt3_tabs_admin_theme->add(new Tab_admin_theme(array(
    'name' => __('Color options', 'theme_localization'),
    'icon' => 'colors.png',
    'icon_active' => 'colors_active.png',
    'icon_hover' => 'colors_hover.png'
), array(
    new ColorOption_admin_theme(array(
        'name' => __('Header background color', 'theme_localization'),
        'id' => 'header_bg_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => '000000'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Header text color', 'theme_localization'),
        'id' => 'header_text_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Main menu text color', 'theme_localization'),
        'id' => 'main_menu_text_color_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Sub-menu text color', 'theme_localization'),
        'id' => 'submenu_text_color_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Sub-menu border', 'theme_localization'),
        'id' => 'submenu_border_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => '393b3b'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Body background color', 'theme_localization'),
        'id' => 'body_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Main text color', 'theme_localization'),
        'id' => 'main_text_color_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Heading color', 'theme_localization'),
        'id' => 'header_text_color_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Content block background color', 'theme_localization'),
        'id' => 'block_bg_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => '000000'
    )),
    new ColorOption_admin_theme(array(
        'name' => __('Footer copyright color', 'theme_localization'),
        'id' => 'footer_text_dark',
        'desc' => '',
        'not_empty' => 'true',
        'default' => 'ffffff'
    ))
)));

?>