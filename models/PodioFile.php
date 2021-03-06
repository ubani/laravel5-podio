<?php

/**
 * @see https://developers.podio.com/doc/files
 */
class PodioFile extends PodioObject
{
    public function __construct($podio, $attributes = array())
    {
        parent::__construct($podio);
        $this->property('file_id', 'integer', array('id' => true));
        $this->property('link', 'string');
        $this->property('perma_link', 'string');
        $this->property('thumbnail_link', 'string');
        $this->property('hosted_by', 'string');
        $this->property('name', 'string');
        $this->property('description', 'string');
        $this->property('mimetype', 'string');
        $this->property('size', 'integer');
        $this->property('context', 'hash');
        $this->property('created_on', 'datetime');
        $this->property('rights', 'array');

        $this->has_one('created_by', 'ByLine');
        $this->has_one('created_via', 'Via');
        $this->has_many('replaces', 'File');

        $this->init($attributes);
    }

    /**
     * Returns the raw bytes of a file.
     * @param null $size
     * @return
     * @internal param $link
     * @internal param null $size
     */
    public function get_raw($size = null)
    {
        $link = $size ? ($this->link . '/' . $size) : $this->link;
        return $this->podio->get($link, [], ['file_download' => true])->body;
    }

    /**
     * @see https://developers.podio.com/doc/files/upload-file-1004361
     */
    public function upload($file_path, $file_name)
    {
        return $this->member($this->podio->post("/file/v2/", array('source' => '@' . realpath($file_path), 'filename' => $file_name), array('upload' => TRUE, 'filesize' => filesize($file_path))));
    }

    /**
     * @see https://developers.podio.com/doc/files/get-file-22451
     */
    public function get($file_id)
    {
        return $this->member($this->podio->get("/file/{$file_id}"));
    }

    /**
     * @see https://developers.podio.com/doc/files/get-files-on-app-22472
     */
    public function get_for_app($app_id, $attributes = array())
    {
        return $this->listing($this->podio->get("/file/app/{$app_id}/", $attributes));
    }

    /**
     * @see https://developers.podio.com/doc/files/get-files-on-space-22471
     */
    public function get_for_space($space_id, $attributes = array())
    {
        return $this->listing($this->podio->get("/file/space/{$space_id}/", $attributes));
    }

    /**
     * @see https://developers.podio.com/doc/files/attach-file-22518
     */
    public function attach($file_id, $attributes = array(), $options = array())
    {
        $url = $this->podio->url_with_options("/file/{$file_id}/attach", $options);
        return $this->podio->post($url, $attributes);
    }

    /**
     * @see https://developers.podio.com/doc/files/replace-file-22450
     */
    public function replace($file_id, $attributes = array())
    {
        return $this->podio->post("/file/{$file_id}/replace", $attributes);
    }

    /**
     * @see https://developers.podio.com/doc/files/copy-file-89977
     */
    public function copy($file_id)
    {
        return $this->member($this->podio->post("/file/{$file_id}/copy"));
    }

    /**
     * @see https://developers.podio.com/doc/files/get-files-4497983
     */
    public function get_all($attributes = array())
    {
        return $this->listing($this->podio->get("/file/", $attributes));
    }

    /**
     * @see https://developers.podio.com/doc/files/delete-file-22453
     */
    public function delete($file_id)
    {
        return $this->podio->delete("/file/{$file_id}");
    }

}
