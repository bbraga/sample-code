CREATE TABLE `books` (
    `id` int(11) NOT NULL auto_increment,
    `title` varchar(50) collate utf8_unicode_ci NULL default '',
    `isbn10` varchar(10) collate utf8_unicode_ci NULL default '',
    `isbn13` varchar(13) collate utf8_unicode_ci NULL default '',
    `author_name` varchar(255) collate utf8_unicode_ci NULL default '',
    `publication_type` varchar(20) collate utf8_unicode_ci NULL default '',
    `list_price` varchar(20) collate utf8_unicode_ci NULL default '',
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
