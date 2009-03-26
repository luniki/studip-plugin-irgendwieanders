<?php

# Copyright (c) 2007 Marcus Lunzenauer <mlunzena@uos.de>
# Copyright (c) 2007 Joe Gregorio <joe@bitworking.org>
#
# Permission is hereby granted, free of charge, to any person
# obtaining a copy of this software and associated documentation
# files (the "Software"), to deal in the Software without
# restriction, including without limitation the rights to use,
# copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the
# Software is furnished to do so, subject to the following
# conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
# OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
# HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
# WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
# FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
# OTHER DEALINGS IN THE SOFTWARE.


/**
 * Combinatorial Critter Generator which allows to create critters from
 * combinations of parts. This generator is a direct port of Joe Gregorio's
 * generator (see http://bitworking.org/projects/critters/)
 *
 * Example:
 *
 *   # creates a critter PNG
 *   $critter = new Critter('normal');
 *   $critter->compose(1, 4, 2, 5, 6);
 *
 *
 * @author    Marcus Lunzenauer <mlunzena@uos.de>
 * @author    Joe Gregorio <joe@bitworking.org>
 * @copyright (c) Authors
 * @version   $Id$
 */

class Critter {

  /**
   * @param string size of the critter image; choose one of small|medium|normal
   *
   * @return type <description>
   */
  function __construct($size) {
    $this->size = $size;
  }


  /**
   * @param string size of the critter image; choose one of small|medium|normal
   *
   * @return type <description>
   */
  function Critter($size) {
    $this->__construct($size);
  }


  /**
   * @access private
   */
  function size_name($size) {
    $sizes = array('small'  => '-small',
                   'medium' => '-medium',
                   'normal' => '-normal');
    return isset($sizes[$size]) ? $sizes[$size] : current($sizes);
  }


  /**
   * @access private
   */
  function get_part($part, $part_id) {
    $file = sprintf('images/%s%0d%s.png',
                    $part, $part_id, Critter::size_name($this->size));
    $part = imagecreatefrompng($file);
    if (!$part)
      Critter::error("Failed to load {$file}.");
    imageSaveAlpha($part, TRUE);
    return $part;
  }


  /**
   * @access private
   */
  function error($message) {
    $im    = imagecreate(400, 50);
    $white = imagecolorallocate($im, 255, 255, 255);
    $blue  = imagecolorallocate($im, 0, 0, 255);
    imagestring($im, 4, 0, 0, "ERROR: $message", $blue);
    header('Content-type: image/png');
    imagepng($im);
    exit();
  }


  /**
   * @access private
   */
  function etag($critterid) {
    return sha1($critterid .filemtime(__FILE__));
  }


  /**
   * Composes a critter of the given parts and produces a PNG with the correct
   * headers.
   *
   * @param type <description>
   * @param type <description>
   * @param type <description>
   * @param type <description>
   * @param type <description>
   *
   * @return void
   */
  function compose($foot, $face, $hair, $eyes, $mouth) {

    # add parts
    $f = $this->get_part('foot',  $foot);
    $a = $this->get_part('face',  $face);
    $h = $this->get_part('hair',  $hair);
    $e = $this->get_part('eyes',  $eyes);
    $m = $this->get_part('mouth', $mouth);

    $x = imagesx($f); $y = imagesy($f);

    imagecopy($f, $a, 0, 0, 0, 0, $x, $y);
    imagecopy($f, $h, 0, 0, 0, 0, $x, $y);
    imagecopy($f, $e, 0, 0, 0, 0, $x, $y);
    imagecopy($f, $m, 0, 0, 0, 0, $x, $y);

    header('Content-type: image/png');

    $args = func_get_args();
    header('ETag: ' . $this->etag(join('', $args)));
    imagepng($f);

    imagedestroy($f);
    imagedestroy($a);
    imagedestroy($h);
    imagedestroy($e);
    imagedestroy($m);
  }
}

