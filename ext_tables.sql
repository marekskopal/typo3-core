#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content
(
    section_hash tinytext,
    section_title tinytext,
    section_sorting INT(11) NOT NULL DEFAULT '1',
    frame_class tinytext,
);
