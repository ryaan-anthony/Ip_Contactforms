<?php
$this->startSetup();
$this->run("
    DROP TABLE IF EXISTS {$this->getTable('contactforms/forms')};
    CREATE TABLE {$this->getTable('contactforms/forms')} (
        `id` int(10) UNSIGNED NOT NULL auto_increment,
        `title` varchar(255) NOT NULL,
        `url_key` varchar(255) NOT NULL,
        `meta_title` varchar(255),
        `request_template` varchar(255),
        `response_template` varchar(255),
        `content` text,
        `updated_at` timestamp NOT NULL default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB;
");
$this->run("
        DROP TABLE IF EXISTS {$this->getTable('contactforms/fields')};
        CREATE TABLE {$this->getTable('contactforms/fields')} (
            `id` int(10) UNSIGNED NOT NULL auto_increment,
            `form_id` int(10) UNSIGNED NOT NULL,
            `required` int(1) NOT NULL default 0,
            `position` int(10) NOT NULL default 0,
            `type` varchar(50) NOT NULL,
            `code` varchar(50) NOT NULL,
            `label` varchar(50) NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (form_id) REFERENCES {$this->getTable('contactforms/forms')}(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");
$this->endSetup();