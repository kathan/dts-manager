#!/bin/bash
CUR_DIR=${0}
if [ -e "$CUR_DIR/phpmd_results.html" ]
 then
	rm phpmd_results.html
fi

./vendor/bin/phpmd . html 'unusedcode,design' --exclude log/,phpMyAdmin/,vendor/,templates/,templates_c/,includes/smarty/,includes/smarty-master/ >> phpmd_results.html