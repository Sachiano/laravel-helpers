<?php

/**
 * This file was created whilst working with Laravel 5.2.22 and may - in future -
 * depend on some newer Laravel features or components.
 *
 * Not all functions are Laravel specific.
 *
 * @author Sacha Corazzi <sacha.corazzi@gmail.com>
 *
 * @copyright MIT License 2016 Sacha Corazzi
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:*
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.*
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
 
/**
 * Transform a database column name to a more human-readable name
 *
 * @param  string
 *
 * @return string
 */
function humanify($string)
{
    $studly = studly_case($string);

    $split = preg_split("/(?<=[a-z])(?![a-z])/", $studly, -1, PREG_SPLIT_NO_EMPTY);

    return implode($split, ' ');
}

/**
 * Echo out a 'selected' attribute
 *
 * @param  string   $element        The element key, e.g., vendor_id
 * @param  mixed    $attribute      The value to check
 * @param  string   $type           The form element type
 * @param  mixed    $checkAgainst   The value to check the attribute against
 *
 * @return string|null
 */
function selected($element, $attribute, $type, $checkAgainst = null)
{
    switch ($type) {
        case 'select':
            $selected = in_array($checkAgainst, (array) $attribute) ||
                        request()->input($element) == $checkAgainst ||
                        request()->old($element) == $checkAgainst;
            break;
        case 'checkbox':
            $selected = request()->has($element);
            break;
        default:
            $selected = false;
            break;
    }

    return $selected ? 'selected checked' : null;
}

/**
 * Get the directory's modified time based on when the last file within it was updated
 *
 * @param  string   Which directory to look within
 * @param  string   A regular expression to match files
 *
 * @return int      The timestamp
 */
function dirmtime($path = '', $regex = null)
{
    $directory = new RecursiveDirectoryIterator($path);
    $iterator  = new RecursiveIteratorIterator($directory);

    if ($regex) {
        $iterator = new RegexIterator($iterator, $regex, RecursiveRegexIterator::GET_MATCH);
    }

    $mtimes = [];

    foreach ($iterator as $file) {
        if (is_array($file)) {
            foreach ($file as $f) {
                $mtimes[] = filemtime($f);
            }

            continue;
        }

        $mtimes[] = filemtime($file);
    }

    return empty($mtimes) ? null : max($mtimes);
}

/**
 * Return the current time as a Carbon date Object
 *
 * @return \Carbon\Carbon
 */
function now()
{
    return \Carbon\Carbon::now();
}
