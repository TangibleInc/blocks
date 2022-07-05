<?php

require_once __DIR__.'/admin-notice.php';

$plugin->register_settings([
  'css' => $plugin->assets_url . '/build/admin.min.css',
  'title_callback' => function() use ($plugin) {
    ?>
      <img class="plugin-logo"
        src="<? echo $plugin->assets_url; ?>/images/tangible-logo.png"
        alt="Tangible Logo"
        width="40"
      >
      <? echo $plugin->title; ?>
    <?php
  },
  'tabs' => [
    'welcome' => [
      'title' => 'Welcome',
      'callback' => require_once __DIR__.'/welcome.php'
    ]
  ],
]);
