<?php
namespace Ionic\Helpers;

class Pagination {
    var $page = 0;
    var $pageSize = 10;
    var $currentPage = 0;
    var $currentSize = 0;
    function isEnd() {
        return ($this->currentSize < $this->pageSize);
    }
}