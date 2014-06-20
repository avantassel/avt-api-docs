#!/bin/bash

#cd to this directory since paths are relative
cd "$(dirname "$0")"

echo -e "$(tput bold)$(tput setaf 5) AVT Docs Tests"

rm -rf features/*.feature

if [[ ! -f createtests.php ]]; then
    echo -e "$(tput setaf 1) Create tests file not found!"
else
	echo -e "$(tput setaf 4) Creating Behat test feature files"
  #set color to white
	echo -e $(tput setaf 7)
  php createtests.php

	if [[ ! -f ../vendor/behat/behat/bin/behat ]]; then
		echo -e "\n$(tput setaf 1) Behat bin not found! Run composer install"
	else
		echo -e "\n$(tput setaf 4) Running Behat tests"
    #reset color
    echo -e $(tput sgr0)
		../vendor/behat/behat/bin/behat
	fi
  #reset color
  echo -e $(tput sgr0)
fi
