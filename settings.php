<?php
/**
 * @file
 * Platform.sh settings.
 */

ini_set('arg_separator.output',     '&amp;');
ini_set('magic_quotes_runtime',     0);
ini_set('magic_quotes_sybase',      0);
ini_set('session.cache_expire',     200000);
ini_set('session.cache_limiter',    'none');
ini_set('session.cookie_lifetime',  2000000);
ini_set('session.gc_maxlifetime',   200000);
ini_set('session.save_handler',     'user');
ini_set('session.use_cookies',      1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid',    0);
ini_set('url_rewriter.tags',        '');
ini_set('pcre.backtrack_limit', 200000);
ini_set('pcre.recursion_limit', 200000);

ini_set('mbstring.http_input', 'pass');
ini_set('mbstring.http_output', 'pass');

// Configure the database.
if (isset($_ENV['PLATFORM_RELATIONSHIPS'])) {
  $relationships = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS']), TRUE);
  if (empty($databases['default']['default']) && !empty($relationships['database'])) {
    foreach ($relationships['database'] as $endpoint) {
      if (!empty($endpoint['query']['is_master'])) {
        $db_url = "{$endpoint['scheme']}://{$endpoint['username']}:{$endpoint['password']}@{$endpoint['host']}:{$endpoint['port']}/{$endpoint['path']}";
        break;
      }
    }
  }
}

// Configure private and temporary file paths.
if (isset($_ENV['PLATFORM_APP_DIR'])) {
  if (!isset($conf['file_private_path'])) {
    $conf['file_private_path'] = $_ENV['PLATFORM_APP_DIR'] . '/private';
  }
  if (!isset($conf['file_directory_temp'])) {
    $conf['file_directory_temp'] = $_ENV['PLATFORM_APP_DIR'] . '/tmp';
  }
}

// A variable named "drupal:example-setting" will be saved in
// $conf['example-setting'].
if (isset($_ENV['PLATFORM_VARIABLES'])) {
  $variables = json_decode(base64_decode($_ENV['PLATFORM_VARIABLES']), TRUE);
  foreach ($variables as $name => $value) {
    if (strpos($name, 'drupal:') === 0) {
      $conf[substr($name, 7)] = $value;
    }
  }
}
