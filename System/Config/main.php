<?php
$this->add('debug', array(
        'sys'       => true,
        'profiler'  => true,
        'DBstat'    => true,
        'DBqueries' => true
    )
);

$this->add('defaultModule', 'home');
$this->add('defaultLocale', 'ru');

// path definition
$this->add('path', array('sys' => ROOT.'System/'));
$this->add('path', array(
        'core'      => $this->get('path','sys').'Core/',
        'modules'   => $this->get('path','sys').'Modules/',
        'models'    => $this->get('path','sys').'Models/',
        'tables'    => $this->get('path','sys').'Tables/',
        'cache'     => $this->get('path','sys').'_cache/',
        'uploads'   => ROOT.'uploads/'
    )
);

// http url and path definition
$this->add('url', array('base' => 'http://ide.z.ict/'));
$this->add('url', array(
        'css'       => $this->get('url','base').'css/',
        'js'        => $this->get('url','base').'js/',
        'img'       => $this->get('url','base').'img/',
        'uploads'   => $this->get('url','base').'uploads/'
    )
);

// DB definition
$this->add('mysql', array(
        'main'      => array(
            'host'      => 'localhost',
            'db'        => 'zfw',
            'login'     => 'root',
            'password'  => '')
    )
);

// error page URL definition
$this->add('errorPage', array(
        '404'   => $this->get('url','base').'error/not_found',
        '500'   => $this->get('url','base').'error/server_error',
        '305'   => $this->get('url','base').'error/unauthorized'
    )
);

$this->add('captcha', array(
        'private'   => '',
        'public'    => ''
    )
);

$this->add('umask', array(
        'dir'   => 0777,
        'file'  => 0666
    )
);
