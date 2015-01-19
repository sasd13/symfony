<?php

namespace MyWebsite\WebBundle\Model;

/**
 * LifeCycleInterface
 */
interface LifeCycleInterface
{
	public function prePersist();
	public function postPersist();
	public function preRemove();
}
