#!/bin/bash

[ -d "./release" ] && rm -r -f ./release
[ ! -d "./release" ] && mkdir ./release

cp *.php ./release
mkdir ./release/src
cp ./src/*.php ./release/src
cp -r ./src/Cache ./release/src
cp -r ./src/Data ./release/src
mkdir ./release/src/Settings
cp ./src/Settings/*.php ./release/src/Settings
cp -r ./vendor ./release
mkdir ./release/src/Settings/admin-menu/
mkdir ./release/src/Settings/admin-menu/public/
mkdir ./release/src/Settings/admin-menu/public/build
cp -r ./src/Settings/admin-menu/public/build/*.css ./release/src/Settings/admin-menu/public/build/
cp -r ./src/Settings/admin-menu/public/build/*.js ./release/src/Settings/admin-menu/public/build/
