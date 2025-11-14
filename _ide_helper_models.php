<?php

/**
 * IDE Helper for Laravel Models
 * This file helps IDEs understand Laravel's dynamic properties and methods
 */

namespace Illuminate\Contracts\Pagination {
    /**
     * @method string links(string $view = null, array $data = [])
     */
    interface LengthAwarePaginator {}
}

namespace Illuminate\Pagination {
    /**
     * @method string links(string $view = null, array $data = [])
     */
    class LengthAwarePaginator {}
}
