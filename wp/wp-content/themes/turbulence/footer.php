<?php
    $options = get_option( 'turbulence_theme_options' );
?>


</div><!--wrap-->

<footer class="footer">
    <div class="container">
        <div class="nav-support footer__nav-item">
            <div class="nav-title"><?php _e('We Support')?></div>

            <ul class="drop-list">
                <?php wp_nav_menu(array(
                    'theme_location'  => '',
                    'menu'            => 'we_support_nav',
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '%3$s',
                    'depth'           => 0,
                    'walker'          => ''
                ));?>
            </ul>
        </div>

        <div class="nav-publish footer__nav-item">
            <div class="nav-title"><?php _e('We Publish')?></div>

            <ul class="drop-list">
                <?php wp_nav_menu(array(
                    'theme_location'  => '',
                    'menu'            => 'we_publish_nav',
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '%3$s',
                    'depth'           => 0,
                    'walker'          => ''
                ));?>
            </ul>
        </div>

        <div class="nav-organize footer__nav-item">
            <div class="nav-title"><?php _e('We Organize')?></div>

            <ul class="drop-list">
                <?php wp_nav_menu(array(
                    'theme_location'  => '',
                    'menu'            => 'we_organize_nav',
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '%3$s',
                    'depth'           => 0,
                    'walker'          => ''
                ));?>
            </ul>
        </div>

        <div class="nav-socialize footer__nav-item">
            <div class="nav-title"><?php _e('We Socialize')?></div>

            <ul class="drop-list">
                <?php if($options['email_link']):?>
                    <li><a href="mailto:<?php echo $options['email_link']?>"><i class="icon-mail_small"></i> <?php _e('Email us')?></a></li>
                <?php endif;?>
                <?php if($options['facebook_link']):?>
                    <li><a href="<?php echo $options['facebook_link']?>"><i class="icon-fb_small"></i> <?php _e('Like us')?></a></li>
                <?php endif;?>
                <?php if($options['twitter_link']):?>
                    <li><a href="<?php echo $options['twitter_link']?>"><i class="icon-tw_small"></i> <?php _e('Follow us')?></a></li>
                <?php endif;?>
                <?php if($options['github_link']):?>
                    <li><a href="<?php echo $options['github_link']?>"><i class="icon-github_small"></i> <?php _e('Fork us')?></a></li>
                <?php endif;?>
            </ul>
        </div>
    </div>

    <div class="footer__f"><?php echo $options['site_copyright']?></div>
</footer>

</div><!--wrapper-->
<?php if($color = get_field('color')):?>
    <div class="border-frame" style="color: <?php echo $color?>">
        <div class="border-frame__left"></div>
        <div class="border-frame__right"></div>
    </div>
<?php endif;?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri()?>/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="<?php echo get_template_directory_uri()?>/js/plugins.js"></script>
    <script src="<?php echo get_template_directory_uri()?>/js/jquery-ui.min.js"></script>
    <script src="<?php echo get_template_directory_uri()?>/js/main.js"></script>

	<?php wp_footer(); ?>
</body>
</html>