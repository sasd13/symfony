<?php

namespace MyWebsite\WebBundle\Services;

class Layouter
{
	const LAYOUT_HOME = 'MyWebsiteWebBundle:Web:index.html.twig';
	const LAYOUT_PROFILE = 'MyWebsiteWebBundle:Profile:profile.html.twig';
	const LAYOUT_PROFILE_SIGNUP = 'MyWebsiteWebBundle:Profile:SignUp/signup.html.twig';
	const LAYOUT_PROFILE_LOGIN = 'MyWebsiteWebBundle:Profile:Login/login.html.twig';
	const LAYOUT_ERROR = 'MyWebsiteWebBundle:Exception:error.html.twig';
	const LAYOUT_ERROR404 = 'MyWebsiteWebBundle:Exception:error404.html.twig';
}
