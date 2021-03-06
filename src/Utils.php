<?php
/**
 * Pagination utlities
 *
 * PHP Version 7.1
 *
 * @category  Pagination
 * @package   CorePHP_Slim_Eloquent
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2019 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-slim-eloquent/LICENSE Apache-2.0
 * @link      https://github.com/danteay/corephp-slim-eloquent
 */

namespace CorePHP\Slim\Dependency\Database;

/**
 * Utils
 *
 * @category  Class
 * @package   CorePHP_Slim_Eloquent
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2019 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-slim-eloquent/LICENSE Apache-2.0
 * @link      https://github.com/danteay/corephp-slim-eloquent
 */
class Utils
{
    /**
     * Generate the number link list of the pagination controls
     *
     * @param integer $page  Current page
     * @param integer $pages Number of pages
     * @param integer $links links number displayed before and after the active link
     *
     * @return array
     */
    public static function links($page, $pages, $links = 5)
    {
        $list = [];

        $start = (($page - $links) > 0) ? $page - $links : 1;
        $end = (($page + $links) < $pages) ? $page + $links : $pages;

        if ($start > 1) {
            $list[] = 1;
            $list[] = '...';
        }

        for ( $i = $start ; $i <= $end; $i++ ) {
            $list[] = $i;
        }

        if ($end < $pages) {
            $list[] = '...';
            $list[] = $pages;
        }

        return $list;
    }
}