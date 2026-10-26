<?php
// ---------------------------------------------------------------------------
// Estate Sale — domain classes
// ---------------------------------------------------------------------------

// The object stored in the "remember me" cookie.
class RememberToken
{
    public $username;
    public $issued;

    public function __construct($username = '')
    {
        $this->username = $username;
        $this->issued   = time();
    }
}

// Helper used across the app to persist small cache artifacts (thumbnails,
// rendered price tables, etc.) to disk. Flushes on destruction.
class CacheWriter
{
    public $file;
    public $data;

    public function __construct($file = '', $data = '')
    {
        $this->file = $file;
        $this->data = $data;
    }

    public function __destruct()
    {
        if (!empty($this->file)) {
            file_put_contents($this->file, $this->data);
        }
    }
}
