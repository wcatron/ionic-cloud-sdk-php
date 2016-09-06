<?php

namespace Ionic\Interfaces;

use Ionic\Helpers\Pagination;

interface Client {
    function __construct($config);
    function getUsers($page_size, $page);
}