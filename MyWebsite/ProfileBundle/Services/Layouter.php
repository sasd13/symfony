<?php

namespace MyWebsite\ProfileBundle\Services;

class Layouter
{
	const LAYOUT_PROFILE = 'MyWebsiteProfileBundle::profile.html.twig';
	
	const LAYOUT_PROFILE_USER = 'MyWebsiteProfileBundle:User:user.html.twig';
	const LAYOUT_PROFILE_USER_SIGNUP = 'MyWebsiteProfileBundle:User:signup.html.twig';
	const LAYOUT_PROFILE_USER_LOGIN = 'MyWebsiteProfileBundle:User:login.html.twig';
	
	const LAYOUT_PROFILE_ADMIN = 'MyWebsiteProfileBundle:Admin:admin.html.twig';
	
	const LAYOUT_PROFILE_CLIENT = 'MyWebsiteProfileBundle:Client:client.html.twig';
}
