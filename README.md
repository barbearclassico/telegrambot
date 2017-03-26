Telegram Bot
============


Preflight
---------

    . "$HOME/.martim_moniz" ;  echo $tgbotpass
    sed -i "s/BCBOTPASSWORD/$bcbotpass/g" tg.php 
    git submodule add git@github.com:barbearclassico/photoselector.git

Run
---

    sh burst.sh


How it works
-------------


1. Fetch the the specific page, and generates a list of images.

1. Exclude the already sent items from the current list

1. Send all remain images
