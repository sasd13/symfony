<?php

namespace MyWebsite\WebBundle\Entity;

/**
 * DocumentInterface
 */
interface DocumentInterface
{
	public function getAbsolutePath();
	public function getWebPath();
    protected function getUploadRootDir();
    protected function getUploadDir();
	public function preUpload();
	public function upload();
    public function removeUpload();
}
