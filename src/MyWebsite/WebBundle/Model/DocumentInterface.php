<?php

namespace MyWebsite\WebBundle\Model;

/**
 * DocumentInterface
 */
interface DocumentInterface
{
	public function getAbsolutePath();
	public function getWebPath();
    public function getUploadRootDir();
    public function getUploadDir();
}
