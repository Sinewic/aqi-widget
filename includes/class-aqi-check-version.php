<?php

/**
 * AQI Widget Check Version Class.
 *
 * @class CheckVersion
 */
class CheckVersion {

    function check_wp_version() {    
        /**
         * Check if the version of WordPress in use on the site is supported by AQI.
         */
        if ( version_compare( $GLOBALS['wp_version'], AQI_MINIMUM_WP_VERSION, '<' ) ) {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                    sprintf(
                        /* translators: Placeholders are numbers, versions of WordPress in use on the site, and required by WordPress. */
                        esc_html__( 'Your version of WordPress (%1$s) is lower than the version required by User Lists (%2$s). Please update WordPress to continue enjoying User Lists.', 'aqi-widget' ),
                        $GLOBALS['wp_version'],
                        AQI_MINIMUM_WP_VERSION
                    )
                );
            }

            /**
             * Outputs for an admin notice about running User Lists on outdated WordPress.
             *
             * @since 7.2.0
             */
            function userslists_admin_unsupported_wp_notice() { ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php esc_html_e( 'AQI Widget requires a more recent version of WordPress and has been paused. Please update WordPress to continue enjoying User Lists.', 'aqi-widget' ); ?></p>
                </div>
                <?php
            }

            add_action( 'admin_notices', 'userslists_admin_unsupported_wp_notice' );
            return;
        }
    }
}

?>