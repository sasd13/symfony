<?php

namespace MyWebsite\WebBundle\Model;

/**
 * TimeManagerInterface
 */
interface TimeManagerInterface
{
	public function getCreatedAt();
	public function getUpdatedAt();
	public function update();
}
