#!/bin/bash
find /Users/a/face -name "*.php" > ~/files.list
/usr/local/bin/ctags -L ~/files.list -f ~/.tags
echo "aa"
