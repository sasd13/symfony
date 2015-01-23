<?php

namespace MyWebsite\WebBundle\Services;

class Router
{
	const ROUTE_HOME = 'web_home';
	
	const ROUTE_PROFILE_USER = 'web_profile_user';
	const ROUTE_PROFILE_USER_SIGNUP = 'web_profile_user_signup';
	const ROUTE_PROFILE_USER_LOGOUT = 'web_profile_user_logout';
	const ROUTE_PROFILE_USER_UPGRADE = 'web_profile_user_upgrade';
	const ROUTE_PROFILE_USER_DOWNGRADE = 'web_profile_user_downgrade';
	
	const ROUTE_PROFILE_ADMIN = 'web_profile_admin';
	const ROUTE_PROFILE_ADMIN_INFO = 'web_profile_admin_info';
	const ROUTE_PROFILE_ADMIN_DELETE = 'web_profile_admin_delete';
	
	const ROUTE_PROFILE_CLIENT = 'web_profile_client';
	const ROUTE_PROFILE_CLIENT_INFO = 'web_profile_client_info';
	const ROUTE_PROFILE_CLIENT_PICTURE = 'web_profile_client_picture';
	const ROUTE_PROFILE_CLIENT_PICTURE_DELETE = 'web_profile_client_picture_delete';
	const ROUTE_PROFILE_CLIENT_DELETE = 'web_profile_client_delete';
	
	const ROUTE_ERROR = 'web_error';
	const ROUTE_ERROR404 = 'web_error404';
}
