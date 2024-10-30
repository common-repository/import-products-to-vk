<?php defined( 'ABSPATH' ) || exit;
require_once IP2VK_PLUGIN_DIR_PATH . 'common-libs/icopydoc-useful-functions-1-1-9.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'common-libs/wc-add-functions-1-0-2.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'common-libs/class-icpd-feedback-1-0-3.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'common-libs/class-icpd-promo-1-1-0.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'common-libs/backward-compatibility.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'functions.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/pages/extensions-page/class-ip2vk-extensions-page.php';

require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk-interface-hocked.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk-data-arr.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk-debug-page.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk-error-log.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk-plugin-form-activate.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/class-ip2vk-plugin-upd.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/system/pages/settings-page/class-ip2vk-settings-page.php';

require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/traits/common/trait-ip2vk-t-common-get-catid.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/traits/common/trait-ip2vk-t-common-skips.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/traits/global/traits-ip2vk-global-variables.php';

require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/class-ip2vk-api.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/class-ip2vk-api-helper.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/class-ip2vk-api-helper-simple.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/class-ip2vk-api-helper-variable.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/class-ip2vk-api-helper-external.php';
require_once IP2VK_PLUGIN_DIR_PATH . 'classes/generation/class-ip2vk-generation-xml.php';