<?php

namespace MyWebsite\ProfileBundle\Services;

use MyWebsite\WebBundle\Services\WebMenuGenerator;

class ProfileMenuGenerator extends WebMenuGenerator
{
	/**
	 * @param null $mode
	 * @return mixed
     */
	public function setWebMenu() {
		$request = $this->container->get('request');
		$webData = $this->container->get('web_data');
		$profileData = $this->container->get('profile_data');

		$arrayDisplay[] = $webData::DEFAULT_MENU_DISPLAY_WEB;
		if($request->getSession()->has('mode')) {
			$mode = $request->getSession()->get('mode');
			switch($mode) {
				case 'admin':
					$arrayDisplay[] = $profileData::ADMIN_MENU_DISPLAY_WEB;
					break;
				case 'client':
					$arrayDisplay[] = $profileData::CLIENT_MENU_DISPLAY_WEB;
					break;
			}
		}
		else {
			$arrayDisplay[] = $profileData::CLIENT_MENU_DISPLAY_WEB;
		}

		$webMenu = $this->generateMenu($arrayDisplay);
		$request->getSession()->set('webMenu', $webMenu);
	}

	public function setProfileMenu() {
		$request = $this->container->get('request');
		$profileData = $this->container->get('profile_data');

		$arrayDisplay[] = null;
		$mode = $request->getSession()->get('mode');
		switch($mode) {
			case "admin" :
				$arrayDisplay[] = $profileData::ADMIN_MENU_DISPLAY_PROFILE;
				break;
			case "client" :
				$arrayDisplay[] = $profileData::CLIENT_MENU_DISPLAY_PROFILE;
				break;
		}

		$profileMenu = $this->generateMenu($arrayDisplay);
		$request->getSession()->set('profileMenu', $profileMenu);
	}
}
