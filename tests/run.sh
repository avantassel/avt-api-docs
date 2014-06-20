#!/bin/bash

#cd to this directory since paths are relative
cd "$(dirname "$0")"

rm -rf features/*.feature

if [[ ! -f createtests.php ]]; then
    echo -e "\e[1;31mCreate tests file not found!\e[0m"
else
	echo -e "\e[1;34mCreating Behat test feature files\e[0m"
	php createtests.php

	if [[ ! -f ../vendor/behat/behat/bin/behat ]]; then
		echo -e "\e[1;31mBehat bin not found!  Run composer install\e[0m"
	else
		echo -e "\e[1;34mRunning Behat tests\e[0m"
		../vendor/behat/behat/bin/behat	
	fi
fi