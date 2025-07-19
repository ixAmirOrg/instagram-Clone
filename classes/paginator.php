<?php
class Paginator {
    public $limit;
    public $offset;
    public $previous;
    public $next;

    public function __construct($page, $records_per_page, $total_records) {
        $this->limit = $records_per_page;
        $this->offset = $records_per_page * ($page - 1);

        $total_pages = ceil($total_records / $records_per_page);

        $this->previous = ($page > 1) ? $page - 1 : null;
        $this->next = ($page < $total_pages) ? $page + 1 : null;
    }
}
