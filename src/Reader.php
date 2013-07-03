<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mateu Adsuara Sabater
 * Date: 3/07/13
 * Time: 20:07
 */

interface Reader {
    function open($path);
    function isReady();
    function readRow();
}