<?php

/******************************************************************************
 *  
 *  PROJECT: Flynax Classifieds Software
 *  VERSION: 4.9.3
 *  LICENSE: FL99604NUYY8 - https://www.flynax.com/flynax-software-eula.html
 *  PRODUCT: General Classifieds
 *  DOMAIN: gmowin.store
 *  FILE: {file}
 *  
 *  The software is a commercial product delivered under single, non-exclusive,
 *  non-transferable license for one domain or IP address. Therefore distribution,
 *  sale or transfer of the file in whole or in part without permission of Flynax
 *  respective owners is considered to be illegal and breach of Flynax License End
 *  User Agreement.
 *  
 *  You are not allowed to remove this information from the file without permission
 *  of Flynax respective owners.
 *  
 *  Flynax Classifieds Software 2024 | All copyrights reserved.
 *  
 *  https://www.flynax.com
 ******************************************************************************/

/* define system variables */

define('RL_DS', DIRECTORY_SEPARATOR);

//debug manager, set true to enable, false to disable
define('RL_DEBUG', false);
define('RL_DB_DEBUG', false);
define('RL_MEMORY_DEBUG', false);
define('RL_AJAX_DEBUG', false);

// mysql credentials - Coolify External Database
define('RL_DBPORT', '3306');
define('RL_DBHOST', 'l8owc48k8kcsgkog4s0swsg0');
define('RL_DBUSER', 'root');
define('RL_DBPASS', 'mZPOoJmy6aeweznuV1ag9z19xiau99zaX1VUSMOV7EcCvr7sEgfEI7jczMitJcqg');
define('RL_DBNAME', 'gmoplus_store');
define('RL_DBPREFIX', 'fl_');

// system paths
define('RL_DIR', '');
define('RL_ROOT', '/var/www/html' . RL_DS . RL_DIR);
define('RL_INC', RL_ROOT . 'includes' . RL_DS);
define('RL_CLASSES', RL_INC . 'classes' . RL_DS);
define('RL_CONTROL', RL_INC . 'controllers' . RL_DS);
define('RL_LIBS', RL_ROOT . 'libs' . RL_DS);
define('RL_TMP', RL_ROOT . 'tmp' . RL_DS);
define('RL_UPLOAD', RL_TMP . 'upload' . RL_DS);
define('RL_FILES', RL_ROOT . 'files' . RL_DS);
define('RL_PLUGINS', RL_ROOT . 'plugins' . RL_DS);
define('RL_CACHE', RL_TMP . 'cache_1861012364' . RL_DS);

// system URLs
define('RL_URL_HOME', 'https://store.gmoplus.com/');
define('RL_FILES_URL', RL_URL_HOME . 'files/');
define('RL_LIBS_URL', RL_URL_HOME . 'libs/');
define('RL_PLUGINS_URL', RL_URL_HOME . 'plugins/');

//system admin paths
define('ADMIN', 'admin');
define('ADMIN_DIR', ADMIN . RL_DS);
define('RL_ADMIN', RL_ROOT . ADMIN . RL_DS);
define('RL_ADMIN_CONTROL', RL_ADMIN . 'controllers' . RL_DS);

//memcache server host and port
define('RL_MEMCACHE_HOST', '127.0.0.1');
define('RL_MEMCACHE_PORT', 11211);

//redis server name, password, host and port
define('RL_REDIS_USER', '');
define('RL_REDIS_PASS', '');
define('RL_REDIS_HOST', 'redis');
define('RL_REDIS_PORT', 6379);

/* YOU ARE NOT PERMITTED TO MODIFY THE CODE BELOW */
define('RL_SETUP', 'JGxpY2Vuc2VfZG9tYWluID0gInN0b3JlLmdtb3BsdXMuY29tIjskbGljZW5zZV9udW1iZXIgPSAiRkw5OTYwNE5VWVk4Ijs=');
/* END CODE */

/* define system variables end */
