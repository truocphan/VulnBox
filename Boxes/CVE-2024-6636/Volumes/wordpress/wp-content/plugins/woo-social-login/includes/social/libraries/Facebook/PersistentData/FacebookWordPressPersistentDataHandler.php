<?php
/**
 * Copyright 2017 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */

namespace Facebook\PersistentData;

/**
 * Class FacebookMemoryPersistentDataHandler
 *
 * @package Facebook
 */
class FacebookWordPressPersistentDataHandler implements PersistentDataInterface {

    private $uniqueID;

    public function __construct($id) {
        $this->uniqueID = $id;
    }

    /**
     * @inheritdoc
     */
    public function get($key) {
        return get_site_transient($this->uniqueID . $key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value) {
        set_site_transient($this->uniqueID . $key, $value, 3600);
    }
}
