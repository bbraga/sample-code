CREATE TABLE `sendspace_members` (
    `id` int(11) NOT NULL auto_increment,
    `username` varchar(32) collate utf8_unicode_ci NOT NULL default '',
    `pass` varchar(32) collate utf8_unicode_ci NOT NULL default '',
    `email` varchar(255) collate utf8_unicode_ci NOT NULL default '',
    `registration_ip` varchar(15) collate utf8_unicode_ci NOT NULL default '',
    `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
