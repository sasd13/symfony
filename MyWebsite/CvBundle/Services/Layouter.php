<?php

namespace MyWebsite\CvBundle\Services;

class Layouter
{
	const LAYOUT_CV_LIST = 'MyWebsiteCvBundle:Web:cv-list.html.twig';
	const LAYOUT_CV_LOAD = 'MyWebsiteCvBundle:Web:cv.html.twig';
	
	const LAYOUT_CV_PROFILE = 'MyWebsiteProfileBundle::profile.html.twig';
	
	//Les subLayout list sont pareils
	//Les subLayout new sont pareils
	//Les subLayout edit sont pareils
	
	const SUBLAYOUT_CV_PROFILE_LIST = 'cv_profile_list';
	const SUBLAYOUT_CV_PROFILE_NEW = 'cv_profile_new';
	const SUBLAYOUT_CV_PROFILE_EDIT = 'cv_profile_edit';
	
	const SUBLAYOUT_CV_PROFILE_MODEL_LIST = 'cv_profile_model_list';
	const SUBLAYOUT_CV_PROFILE_MODEL_NEW = 'cv_profile_model_new';
	const SUBLAYOUT_CV_PROFILE_MODEL_EDIT = 'cv_profile_model_edit';
	
	const SUBLAYOUT_CV_PROFILE_MODEL_CATEGORY_NEW = 'cv_profile_model_category_new';
	const SUBLAYOUT_CV_PROFILE_MODEL_CATEGORY_EDIT = 'cv_profile_model_category_edit';
	
	const SUBLAYOUT_CV_PROFILE_MODEL_CATEGORY_CONTENT_NEW = 'cv_profile_model_category_content_new';
	const SUBLAYOUT_CV_PROFILE_MODEL_CATEGORY_CONTENT_EDIT = 'cv_profile_model_category_content_edit';
}
