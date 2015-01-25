<?php

namespace MyWebsite\ProfileBundle\Services;

class Layouter
{
	const LAYOUT_PROFILE = 'MyWebsiteProfileBundle::profile.html.twig';
	
	const LAYOUT_PROFILE_USER_SIGNUP = 'MyWebsiteProfileBundle:User:signup.html.twig';
	const LAYOUT_PROFILE_USER_LOGIN = 'MyWebsiteProfileBundle:User:login.html.twig';
	
	const SUBLAYOUT_PROFILE_USER = 'User:user';
	
	const SUBLAYOUT_PROFILE_ADMIN = 'Admin:admin';
	const SUBLAYOUT_PROFILE_ADMIN_EDIT = 'Admin:admin-edit';
	
	const SUBLAYOUT_PROFILE_CLIENT = 'Client:client';
	const SUBLAYOUT_PROFILE_CLIENT_EDIT = 'Client:client-edit';
	const SUBLAYOUT_PROFILE_CLIENT_PICTURE_EDIT = 'Client:client-picture-edit';
}
