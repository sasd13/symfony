<?php

namespace MyWebsite\ProfileBundle\Services;

class Router
{
	const ROUTE_PROFILE_USER = 'profile_user';
	const ROUTE_PROFILE_USER_SIGNUP = 'profile_user_signup';
	const ROUTE_PROFILE_USER_LOGOUT = 'profile_user_logout';
	const ROUTE_PROFILE_USER_UPGRADE = 'profile_user_upgrade';
	const ROUTE_PROFILE_USER_DOWNGRADE = 'profile_user_downgrade';
	
	const ROUTE_PROFILE_ADMIN = 'profile_admin';
	const ROUTE_PROFILE_ADMIN_INFO = 'profile_admin_info';
	
	const ROUTE_PROFILE_CLIENT = 'profile_client';
	const ROUTE_PROFILE_CLIENT_INFO = 'profile_client_info';
	const ROUTE_PROFILE_CLIENT_PICTURE = 'profile_client_picture';
	const ROUTE_PROFILE_CLIENT_PICTURE_DELETE = 'profile_client_picture_delete';
	const ROUTE_PROFILE_CLIENT_DELETE = 'profile_client_delete';
}
