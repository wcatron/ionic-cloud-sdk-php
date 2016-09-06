<?php
namespace Ionic\Helpers;

class Pagination {
    var $page = 0;
    var $pageSize = 10;
    var $currentPage = 0;
    /**
     * @var null|int Current size of the results. Null at beginning when there is no current size.
     */
    var $currentSize = null;
    function isEnd() {
        return ($this->currentSize === null) ? false : ($this->currentSize < $this->pageSize);
    }
}