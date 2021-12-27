<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;

/**
 * This class is copied in every Composer installed project and available to all
 *
 * To require it's presence, you can require `composer-runtime-api ^2.0`
 */
class InstalledVersions
{
    private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => 'e16709b95a16f6486fde8b26f3e0679274c5f400',
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => 'e16709b95a16f6486fde8b26f3e0679274c5f400',
    ),
    'cocur/slugify' => 
    array (
      'pretty_version' => 'v4.0.0',
      'version' => '4.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '3f1ffc300f164f23abe8b64ffb3f92d35cec8307',
    ),
    'composer/package-versions-deprecated' => 
    array (
      'pretty_version' => '1.11.99',
      'version' => '1.11.99.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c8c9aa8a14cc3d3bec86d0a8c3fa52ea79936855',
    ),
    'doctrine/annotations' => 
    array (
      'pretty_version' => '1.10.4',
      'version' => '1.10.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bfe91e31984e2ba76df1c1339681770401ec262f',
    ),
    'doctrine/cache' => 
    array (
      'pretty_version' => '1.10.2',
      'version' => '1.10.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '13e3381b25847283a91948d04640543941309727',
    ),
    'doctrine/collections' => 
    array (
      'pretty_version' => '1.6.7',
      'version' => '1.6.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '55f8b799269a1a472457bd1a41b4f379d4cfba4a',
    ),
    'doctrine/common' => 
    array (
      'pretty_version' => '3.0.2',
      'version' => '3.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a3c6479858989e242a2465972b4f7a8642baf0d4',
    ),
    'doctrine/dbal' => 
    array (
      'pretty_version' => '2.10.3',
      'version' => '2.10.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '03ca23afc2ee062f5d3e32426ad37c34a4770dcf',
    ),
    'doctrine/doctrine-bundle' => 
    array (
      'pretty_version' => '2.1.2',
      'version' => '2.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f5153089993e1230f5d8acbd8e126014d5a63e17',
    ),
    'doctrine/doctrine-migrations-bundle' => 
    array (
      'pretty_version' => '3.0.1',
      'version' => '3.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '96e730b0ffa0bb39c0f913c1966213f1674bf249',
    ),
    'doctrine/event-manager' => 
    array (
      'pretty_version' => '1.1.1',
      'version' => '1.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '41370af6a30faa9dc0368c4a6814d596e81aba7f',
    ),
    'doctrine/inflector' => 
    array (
      'pretty_version' => '1.4.3',
      'version' => '1.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '4650c8b30c753a76bf44fb2ed00117d6f367490c',
    ),
    'doctrine/instantiator' => 
    array (
      'pretty_version' => '1.3.1',
      'version' => '1.3.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f350df0268e904597e3bd9c4685c53e0e333feea',
    ),
    'doctrine/lexer' => 
    array (
      'pretty_version' => '1.2.1',
      'version' => '1.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e864bbf5904cb8f5bb334f99209b48018522f042',
    ),
    'doctrine/migrations' => 
    array (
      'pretty_version' => '3.0.1',
      'version' => '3.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '69eaf2ca5bc48357b43ddbdc31ccdffc0e2a0882',
    ),
    'doctrine/orm' => 
    array (
      'pretty_version' => 'v2.7.3',
      'version' => '2.7.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd95e03ba660d50d785a9925f41927fef0ee553cf',
    ),
    'doctrine/persistence' => 
    array (
      'pretty_version' => '2.0.0',
      'version' => '2.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1dee036f22cd5dc0bc12132f1d1c38415907be55',
    ),
    'doctrine/reflection' => 
    array (
      'pretty_version' => '1.2.1',
      'version' => '1.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '55e71912dfcd824b2fdd16f2d9afe15684cfce79',
    ),
    'doctrine/sql-formatter' => 
    array (
      'pretty_version' => '1.1.1',
      'version' => '1.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '56070bebac6e77230ed7d306ad13528e60732871',
    ),
    'easycorp/easyadmin-bundle' => 
    array (
      'pretty_version' => 'v3.1.9',
      'version' => '3.1.9.0',
      'aliases' => 
      array (
      ),
      'reference' => '9904c14d589c65dae5fff0a2925856a09ba64a8e',
    ),
    'egulias/email-validator' => 
    array (
      'pretty_version' => '2.1.20',
      'version' => '2.1.20.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f46887bc48db66c7f38f668eb7d6ae54583617ff',
    ),
    'jms/metadata' => 
    array (
      'pretty_version' => '2.4.0',
      'version' => '2.4.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '491917b66b44deff7d1c320d35c1b92237083f67',
    ),
    'knplabs/knp-components' => 
    array (
      'pretty_version' => 'v3.0.0',
      'version' => '3.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '246b700fdc40020f1db1c887cd462b9b9d3b79b0',
    ),
    'knplabs/knp-paginator-bundle' => 
    array (
      'pretty_version' => 'v5.4.2',
      'version' => '5.4.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '8a7255062dd9f6ff8a3a4712a4b34bdbbc9f31f7',
    ),
    'laminas/laminas-code' => 
    array (
      'pretty_version' => '3.4.1',
      'version' => '3.4.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '1cb8f203389ab1482bf89c0e70a04849bacd7766',
    ),
    'laminas/laminas-eventmanager' => 
    array (
      'pretty_version' => '3.3.0',
      'version' => '3.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1940ccf30e058b2fd66f5a9d696f1b5e0027b082',
    ),
    'laminas/laminas-zendframework-bridge' => 
    array (
      'pretty_version' => '1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4939c81f63a8a4968c108c440275c94955753b19',
    ),
    'monolog/monolog' => 
    array (
      'pretty_version' => '2.1.1',
      'version' => '2.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f9eee5cec93dfb313a38b6b288741e84e53f02d5',
    ),
    'nikic/php-parser' => 
    array (
      'pretty_version' => 'v4.9.1',
      'version' => '4.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '88e519766fc58bd46b8265561fb79b54e2e00b28',
    ),
    'ocramius/package-versions' => 
    array (
      'replaced' => 
      array (
        0 => '1.11.99',
      ),
    ),
    'ocramius/proxy-manager' => 
    array (
      'pretty_version' => '2.8.0',
      'version' => '2.8.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ac1dd414fd114cfc0da9930e0ab46063c2f5e62a',
    ),
    'paragonie/random_compat' => 
    array (
      'replaced' => 
      array (
        0 => '2.*',
      ),
    ),
    'php-http/async-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '*',
      ),
    ),
    'php-http/client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '*',
      ),
    ),
    'phpdocumentor/reflection-common' => 
    array (
      'pretty_version' => '2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '1d01c49d4ed62f25aa84a747ad35d5a16924662b',
    ),
    'phpdocumentor/reflection-docblock' => 
    array (
      'pretty_version' => '5.2.1',
      'version' => '5.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd870572532cd70bc3fab58f2e23ad423c8404c44',
    ),
    'phpdocumentor/type-resolver' => 
    array (
      'pretty_version' => '1.3.0',
      'version' => '1.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e878a14a65245fbe78f8080eba03b47c3b705651',
    ),
    'psr/cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd11b50ad223250cf17b86e38383413f5a6764bf8',
    ),
    'psr/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
    ),
    'psr/container-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/event-dispatcher' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dbefd12671e8a14ec7f180cab83036ed26714bb0',
    ),
    'psr/event-dispatcher-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/link' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'eea8e8662d5cd3ae4517c9b864493f59fca95562',
    ),
    'psr/link-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.3',
      'version' => '1.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '0f73288fd15629204f9d42b7055f72dacbe811fc',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0.0',
        1 => '1.0',
      ),
    ),
    'psr/simple-cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'sensio/framework-extra-bundle' => 
    array (
      'pretty_version' => 'v5.6.1',
      'version' => '5.6.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '430d14c01836b77c28092883d195a43ce413ee32',
    ),
    'symfony/apache-pack' => 
    array (
      'pretty_version' => 'v1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '3aa5818d73ad2551281fc58a75afd9ca82622e6c',
    ),
    'symfony/asset' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '2f07b5993f1607c1c489bac3e15a000c33668b4b',
    ),
    'symfony/browser-kit' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b9545e08790be2d3d7d92306e339bbcd79f461e4',
    ),
    'symfony/cache' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c31bdd71f30435baff03693e684469c7ecb3ca1a',
    ),
    'symfony/cache-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8034ca0b61d4dd967f3698aaa1da2507b631d0cb',
    ),
    'symfony/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'symfony/config' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '22f961ddffdc81389670b2ca74a1cc0213761ec0',
    ),
    'symfony/console' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '186f395b256065ba9b890c0a4e48a91d598fa2cf',
    ),
    'symfony/css-selector' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e544e24472d4c97b2d11ade7caacd446727c6bf9',
    ),
    'symfony/debug-bundle' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '3f4bcea52678eedf19260973217f5ae7b835edf5',
    ),
    'symfony/dependency-injection' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '48d6890e12ce9cd8e68aaa4fb72010139312fd73',
    ),
    'symfony/deprecation-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5fa56b4074d1ae755beb55617ddafe6f5d78f665',
    ),
    'symfony/doctrine-bridge' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '31cb4cfe6b9452cc1916022e995a08de4005299a',
    ),
    'symfony/dom-crawler' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '3ac31ffbc596e41ca081037b7d78fc7a853c0315',
    ),
    'symfony/dotenv' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '42d2a18597f4c7cafc0e25b1ad6a1cbb4f2caf05',
    ),
    'symfony/error-handler' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '525636d4b84e06c6ca72d96b6856b5b169416e6a',
    ),
    'symfony/event-dispatcher' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '94871fc0a69c3c5da57764187724cdce0755899c',
    ),
    'symfony/event-dispatcher-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '0ba7d54483095a198fa51781bc608d17e84dffa2',
    ),
    'symfony/event-dispatcher-implementation' => 
    array (
      'provided' => 
      array (
        0 => '2.0',
      ),
    ),
    'symfony/expression-language' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '6675d937852379a251017db6e5144dd57506fbbd',
    ),
    'symfony/filesystem' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f7b9ed6142a34252d219801d9767dedbd711da1a',
    ),
    'symfony/finder' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '2b765f0cf6612b3636e738c0689b29aa63088d5d',
    ),
    'symfony/flex' => 
    array (
      'pretty_version' => 'v1.9.3',
      'version' => '1.9.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '1eab1e85f5eb66dac138d6240cc0ceffd6e3ae34',
    ),
    'symfony/form' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '236d88a28671f9b13860789eabe28e8c4c8bba86',
    ),
    'symfony/framework-bundle' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '0607ca3cb7b79461a2e6a7c5d05e5cd6d2c14015',
    ),
    'symfony/http-client' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '21c4372e9cd2305313f4d4792d7b9fa7c25ade53',
    ),
    'symfony/http-client-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '3a5d0fe7908daaa23e3dbf4cee3ba4bfbb19fdd3',
    ),
    'symfony/http-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.1',
      ),
    ),
    'symfony/http-foundation' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '41a4647f12870e9d41d9a7d72ff0614a27208558',
    ),
    'symfony/http-kernel' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '3e32676e6cb5d2081c91a56783471ff8a7f7110b',
    ),
    'symfony/intl' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b6886c43cc1ae3367c569c5a8d4182555dd694fb',
    ),
    'symfony/mailer' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f728de093c1f5360c52a6a929e70852a6c3fb2dc',
    ),
    'symfony/maker-bundle' => 
    array (
      'pretty_version' => 'v1.21.1',
      'version' => '1.21.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'da629093c7bf9abd9a6a0f232a43bbb1b88de68d',
    ),
    'symfony/mime' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '89a2c9b4cb7b5aa516cf55f5194c384f444c81dc',
    ),
    'symfony/monolog-bridge' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c312bf3de53a5e2b784224045d6e7d5c0abfe1c0',
    ),
    'symfony/monolog-bundle' => 
    array (
      'pretty_version' => 'v3.5.0',
      'version' => '3.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dd80460fcfe1fa2050a7103ad818e9d0686ce6fd',
    ),
    'symfony/notifier' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '19699652eaa69b0389bc985853f29b8e9177b1cf',
    ),
    'symfony/options-resolver' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '9ff59517938f88d90b6e65311fef08faa640f681',
    ),
    'symfony/phpunit-bridge' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e7d37c91486a0f9eed58a8c23822e1870ea36db5',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-iconv' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b740103edbdcc39602239ee8860f0f45a8eb9aa5',
    ),
    'symfony/polyfill-intl-icu' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '4e45a6e39041a9cc78835b11abc47874ae302a55',
    ),
    'symfony/polyfill-intl-idn' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '5dcab1bc7146cf8c1beaa4502a3d9be344334251',
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '37078a8dd4a2a1e9ab0231af7c6cb671b2ed5a7e',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a6977d63bf9a0ad4c65cd352709e230876f9904a',
    ),
    'symfony/polyfill-php56' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php70' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php71' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php72' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fffa1a52a023e782cdcc221d781fe1ec8f87fcca',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.18.1',
      'version' => '1.18.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd87d5766cbf48d72388a9f6b85f280c8ad51f981',
    ),
    'symfony/polyfill-uuid' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '7095799250ff244f3015dc492480175a249e7b55',
    ),
    'symfony/process' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '1864216226af21eb76d9477f691e7cbf198e0402',
    ),
    'symfony/property-access' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c2a95da4d3b88523d00903a3d04636717766d674',
    ),
    'symfony/property-info' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a10524dfdadc418c2e4b52667be7d6421b7da26f',
    ),
    'symfony/routing' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '47b0218344cb6af25c93ca8ee1137fafbee5005d',
    ),
    'symfony/security-bundle' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c761866b9aa13add16e6f7dec5f40ab85c2a3ad7',
    ),
    'symfony/security-core' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '0b965da49ccf070764baa2a23bbb926036b6c6b2',
    ),
    'symfony/security-csrf' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '962323e4db4458d731d5006f14019a22a2f84b06',
    ),
    'symfony/security-guard' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '85c368be963e9f0df9e93d830f966fc0af531703',
    ),
    'symfony/security-http' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '7741021221548e2b5768ec0cf502c91b6c55b209',
    ),
    'symfony/sendgrid-mailer' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '5c0fd0290b7f25c2cce8ec8fa2f6e7c85c43e260',
    ),
    'symfony/serializer' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bcda9e50d058db10ef149c987edff06c142d07d1',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd15da7ba4957ffb8f1747218be9e1a121fd298a1',
    ),
    'symfony/service-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'symfony/stopwatch' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '0f7c58cf81dbb5dd67d423a89d577524a2ec0323',
    ),
    'symfony/string' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '0de4cc1e18bb596226c06a82e2e7e9bc6001a63a',
    ),
    'symfony/translation' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '917b02cdc5f33e0309b8e9d33ee1480b20687413',
    ),
    'symfony/translation-contracts' => 
    array (
      'pretty_version' => 'v2.2.0',
      'version' => '2.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '77ce1c3627c9f39643acd9af086631f842c50c4d',
    ),
    'symfony/translation-implementation' => 
    array (
      'provided' => 
      array (
        0 => '2.0',
      ),
    ),
    'symfony/twig-bridge' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '4534dbfd47745a924beaa6e7c9d5462993bbd0a5',
    ),
    'symfony/twig-bundle' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '8898ef8aea8fa48638e15ce00c7c6318ce570ce1',
    ),
    'symfony/uid' => 
    array (
      'pretty_version' => 'v5.1.9',
      'version' => '5.1.9.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd6e033ed0eca26277084f76287c3322dc1d825c2',
    ),
    'symfony/validator' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'db6acf2a0ea02fdb89b561c7bba3db2c0eaecd9a',
    ),
    'symfony/var-dumper' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b43a3905262bcf97b2510f0621f859ca4f5287be',
    ),
    'symfony/var-exporter' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'eabaabfe1485ca955c5b53307eade15ccda57a15',
    ),
    'symfony/web-link' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ba2554887e34e693e3888f23f83c72d5ce04bfb2',
    ),
    'symfony/yaml' => 
    array (
      'pretty_version' => 'v5.1.5',
      'version' => '5.1.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a44bd3a91bfbf8db12367fa6ffac9c3eb1a8804a',
    ),
    'twig/extra-bundle' => 
    array (
      'pretty_version' => 'v3.0.5',
      'version' => '3.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a7c5799cf742ab0827f5d32df37528ee8bf5a233',
    ),
    'twig/twig' => 
    array (
      'pretty_version' => 'v3.0.5',
      'version' => '3.0.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '9b76b1535483cdf4edf01bb787b0217b62bd68a5',
    ),
    'vich/uploader-bundle' => 
    array (
      'pretty_version' => '1.16.1',
      'version' => '1.16.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '781dd64bf278e924971a35d38ef6c690e35de6e1',
    ),
    'webimpress/safe-writer' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5cfafdec5873c389036f14bf832a5efc9390dcdd',
    ),
    'webmozart/assert' => 
    array (
      'pretty_version' => '1.9.1',
      'version' => '1.9.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bafc69caeb4d49c39fd0779086c03a3738cbb389',
    ),
    'zendframework/zend-code' => 
    array (
      'replaced' => 
      array (
        0 => '3.4.1',
      ),
    ),
    'zendframework/zend-eventmanager' => 
    array (
      'replaced' => 
      array (
        0 => '^3.2.1',
      ),
    ),
  ),
);
    private static $canGetVendors;
    private static $installedByVendor = array();

    /**
     * Returns a list of all package names which are present, either by being installed, replaced or provided
     *
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = array_keys($installed['versions']);
        }


        if (1 === \count($packages)) {
            return $packages[0];
        }

        return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
    }

    /**
     * Checks whether the given package is installed
     *
     * This also returns true if the package name is provided or replaced by another package
     *
     * @param  string $packageName
     * @return bool
     */
    public static function isInstalled($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['versions'][$packageName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the given package satisfies a version constraint
     *
     * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
     *
     *   Composer\InstalledVersions::satisfies(new VersionParser, 'foo/bar', '^2.3')
     *
     * @param VersionParser $parser      Install composer/semver to have access to this class and functionality
     * @param string        $packageName
     * @param string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
     *
     * @return bool
     */
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));

        return $provided->matches($constraint);
    }

    /**
     * Returns a version constraint representing all the range(s) which are installed for a given package
     *
     * It is easier to use this via isInstalled() with the $constraint argument if you need to check
     * whether a given version of a package is installed, and not just whether it exists
     *
     * @param  string $packageName
     * @return string Version constraint usable with composer/semver
     */
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            $ranges = array();
            if (isset($installed['versions'][$packageName]['pretty_version'])) {
                $ranges[] = $installed['versions'][$packageName]['pretty_version'];
            }
            if (array_key_exists('aliases', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
            }
            if (array_key_exists('replaced', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
            }
            if (array_key_exists('provided', $installed['versions'][$packageName])) {
                $ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
            }

            return implode(' || ', $ranges);
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['pretty_version'])) {
                return null;
            }

            return $installed['versions'][$packageName]['pretty_version'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
     */
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['versions'][$packageName])) {
                continue;
            }

            if (!isset($installed['versions'][$packageName]['reference'])) {
                return null;
            }

            return $installed['versions'][$packageName]['reference'];
        }

        throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
    }

    /**
     * @return array
     * @psalm-return array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}
     */
    public static function getRootPackage()
    {
        $installed = self::getInstalled();

        return $installed[0]['root'];
    }

    /**
     * Returns the raw installed.php data for custom implementations
     *
     * @return array[]
     * @psalm-return array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>}
     */
    public static function getRawData()
    {
        return self::$installed;
    }

    /**
     * Lets you reload the static array from another file
     *
     * This is only useful for complex integrations in which a project needs to use
     * this class but then also needs to execute another project's autoloader in process,
     * and wants to ensure both projects have access to their version of installed.php.
     *
     * A typical case would be PHPUnit, where it would need to make sure it reads all
     * the data it needs from this class, then call reload() with
     * `require $CWD/vendor/composer/installed.php` (or similar) as input to make sure
     * the project in which it runs can then also use this class safely, without
     * interference between PHPUnit's dependencies and the project's dependencies.
     *
     * @param  array[] $data A vendor/composer/installed.php data set
     * @return void
     *
     * @psalm-param array{root: array{name: string, version: string, reference: string, pretty_version: string, aliases: string[]}, versions: list<string, array{pretty_version: ?string, version: ?string, aliases: ?string[], reference: ?string, replaced: ?string[], provided: ?string[]}>} $data
     */
    public static function reload($data)
    {
        self::$installed = $data;
        self::$installedByVendor = array();
    }

    /**
     * @return array[]
     */
    private static function getInstalled()
    {
        if (null === self::$canGetVendors) {
            self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
        }

        $installed = array();

        if (self::$canGetVendors) {
            // @phpstan-ignore-next-line
            foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
                if (isset(self::$installedByVendor[$vendorDir])) {
                    $installed[] = self::$installedByVendor[$vendorDir];
                } elseif (is_file($vendorDir.'/composer/installed.php')) {
                    $installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
                }
            }
        }

        $installed[] = self::$installed;

        return $installed;
    }
}
