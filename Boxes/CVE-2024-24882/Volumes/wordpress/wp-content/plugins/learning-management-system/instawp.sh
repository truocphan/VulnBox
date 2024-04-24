#!/bin/sh
/usr/bin/php7.4 /usr/local/bin/composer install
mkdir -p $PWD/.npm-global
mkdir -p $PWD/.npm-cache

export npm_config_prefix="$PWD/.npm-global/"
export npm_config_cache="$PWD/.npm-cache"
export PATH=$PWD/.npm-global/bin:$PATH

# Install npm and yarn locally, not globally
npm install -g npm@9.6.5
npm install --global yarn

# Now that npm and yarn are installed, we can set the variables

yarn && yarn build
yarn build
rm -rf node_modules
rm -rf .npm-global
rm -rf .npm-cache
