#!/bin/sh

rm -f pos
cat queue >> oldqueue
sh  photoselector/lsImages $(./feedqueue.sh) > newqueue
cat newqueue | grep -vf oldqueue > queue

cat queue | while read line ; do php tg.php ; done
