<?php

namespace MyWebsite\WebBundle\Services;

class Router
{
	private static $home = 'web_home';
	private static $signup = 'web_signup';
	private static $profile = 'web_profile';
	private static $profileEdit = 'web_profile_edit';
	private static $profilePicture = 'web_profile_picture';
	private static $profilePictureDelete = 'web_profile_picture_delete';
	private static $profileUser = 'web_profile_user';
	private static $profileLogout = 'web_profile_logout';
	private static $error = 'web_error';
	private static $error404 = 'web_error404';
	
	public function toHome() { return self::$home; }
	public function toSignup() { return self::$signup; }
	public function toProfile() { return self::$profile; }
	public function toProfileEdit() { return self::$profileEdit; }
	public function toProfilePicture() { return self::$profilePicture; }
	public function toProfilePictureDelete() { return self::$profilePictureDelete; }
	public function toProfileUser() { return self::$profileUser; }
	public function toProfileLogout() { return self::$profileLogout; }
	public function toError() { return self::$error; }
	public function toError404() { return self::$error404; }
}
