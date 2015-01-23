<?php

namespace MyWebsite\WebBundle\Services;

class Layouter
{
	const LAYOUT_HOME = 'MyWebsiteWebBundle:Web:index.html.twig';
	
	const LAYOUT_PROFILE = 'MyWebsiteWebBundle:Profile:profile.html.twig';
	
	const LAYOUT_PROFILE_USER = 'MyWebsiteWebBundle:Profile:User/user.html.twig';
	const LAYOUT_PROFILE_USER_SIGNUP = 'MyWebsiteWebBundle:Profile:User/signup.html.twig';
	const LAYOUT_PROFILE_USER_LOGIN = 'MyWebsiteWebBundle:Profile:User/login.html.twig';
	
	const LAYOUT_PROFILE_ADMIN = 'MyWebsiteWebBundle:Profile:Admin/admin.html.twig';
	
	const LAYOUT_PROFILE_CLIENT = 'MyWebsiteWebBundle:Profile:Client/client.html.twig';
	
	const LAYOUT_ERROR = 'MyWebsiteWebBundle:Exception:error.html.twig';
	const LAYOUT_ERROR404 = 'MyWebsiteWebBundle:Exception:error404.html.twig';
}
