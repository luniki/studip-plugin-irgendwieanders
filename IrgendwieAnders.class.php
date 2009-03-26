<?php

# Copyright (c) 2007 Marcus Lunzenauer <mlunzena@uos.de>
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
 * The IrgendwieAnders homepage plugin replaces the usual 'head with question
 * marks' picture of people who did not provide a picture of them. Instead of
 * the generic picture an image of an combinatorial generated critter is used.
 *
 * Note that this plugin is really hackish and bloated.
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id$
 */

class IrgendwieAnders extends AbstractStudIPSystemPlugin {

  function getPluginname() {
    return 'IrgendwieAnders';
  }

  function getDisplaytitle() {
    return $this->getPluginname();
  }

  /**
   * A system plugin can do system tasks like logging in the background.
   * This function
   *
   * @return boolean    true - plugin should be called for background task
   *                    false - plugin has no background task
   */
  function hasBackgroundTasks() {
    return TRUE;
  }

  /**
   * abstract function for doing all background tasks
   *
   * @return void
   */
  function doBackgroundTasks() {
    ?>
    <script type="text/javascript" language="javascript">
      var path = "<?= $this->getPluginPath() ?>/critter_webservice.php";
      var replace = function() {
        $w("normal medium").each(function(size) {
          var avatars = $$("img[class~=avatar-" + size + "]");
          avatars.each(function(a) {
            if (!a.src.match(/nobody_(normal|medium).png$/)) {
              return;
            }
            var user = $w(a.className).find(function(c) {
              return c.startsWith("user-");
            });
            a.src = path + "/" + (size === "normal" ? "large" : "medium")  + "/" + user;
          });
        });
      };

      document.observe("dom:loaded", replace);
<?
/*
    if (nobody.size() == 1)
      nobody[0].src= "<?= $this->getPluginPath() ?>/critter_webservice.php/large/<?= $this->getRequestedUser()->username ?>";
*/
?>
    </script>
    <?
  }
}

