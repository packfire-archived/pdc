#!/usr/bin/env php
<?php

/**
 * PDC
 * By Sam-Mauris Yong
 * 
 * Released open source under New BSD 3-Clause License.
 * Copyright (c) Sam-Mauris Yong Shan Xian <sam@mauris.sg>
 * All rights reserved.
 */

Phar::mapPhar('pdc.phar');
require 'phar://pdc.phar/bin/pdc';

__HALT_COMPILER();