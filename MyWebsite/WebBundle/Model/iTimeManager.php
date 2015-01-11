<?php

namespace MyWebsite\WebBundle\Model;

/**
 * iTimeManager
 */
interface iTimeManager
{
	public function getCreatedAt();
	public function getUpdatedAt();
	public function update();	
}
