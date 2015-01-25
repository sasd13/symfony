<?php

namespace MyWebsite\ProfileBundle\Services;

use MyWebsite\ProfileBundle\Entity\User;
use MyWebsite\ProfileBundle\Entity\Admin;
use MyWebsite\ProfileBundle\Entity\Client;

class Data
{
	const USER_PRIVACYLEVEL_LOW = User::PRIVACYLEVEL_LOW;
	const USER_PRIVACYLEVEL_MEDIUM = User::PRIVACYLEVEL_MEDIUM;
	const USER_PRIVACYLEVEL_HIGH = User::PRIVACYLEVEL_HIGH;
	
	const USER_CATEGORY_TITLE_INFO = 'Identité';
	const USER_CATEGORY_TITLE_CONTACT = 'Contact';
	const USER_CATEGORY_TAG_INFO = 'user_category_info';
	const USER_CATEGORY_TAG_CONTACT = 'user_category_contact';
	const USER_CONTENT_LABEL_FIRSTNAME = 'user_content_first_name';
	const USER_CONTENT_LABEL_LASTNAME = 'user_content_last_name';
	const USER_CONTENT_LABEL_EMAIL = 'user_email';
	const USER_CONTENT_LABELVALUE_FIRSTNAME = 'First name';
	const USER_CONTENT_LABELVALUE_LASTNAME = 'Last name';
	const USER_CONTENT_LABELVALUE_EMAIL = 'Email';
	
	const ADMIN_MENU_DISPLAY_WEB = 4;
	const ADMIN_MENU_DISPLAY_PROFILE = 5;
	
	const CLIENT_MENU_DISPLAY_WEB = 2;
	const CLIENT_MENU_DISPLAY_PROFILE = 3;
	const CLIENT_CATEGORY_TITLE_PICTURE = 'Photo de profil';
	const CLIENT_CATEGORY_TAG_PICTURE = 'client_category_picture';
}
