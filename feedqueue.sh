#!/bin/bash

wget -O - www.barbearclassico.com/index.php?board=15.0 2>/dev/null | grep "span id=" | head -n1 | awk -F"?topic=" '{ print $2 }' | awk -F\" '{ print $1 }'
