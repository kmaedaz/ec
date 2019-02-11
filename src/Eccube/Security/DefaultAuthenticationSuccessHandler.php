<?php

namespace Eccube\Security;

use Eccube\Application;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler as BaseDefaultAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\HttpUtils;

class DefaultAuthenticationSuccessHandler extends BaseDefaultAuthenticationSuccessHandler
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app, HttpUtils $httpUtils, array $options = array())
    {
        parent::__construct($httpUtils, $options);
        $this->app = $app;
    }

    public function onAuthenticationSuccess( Request $request, TokenInterface $token)
    {
        $responce = null;
        $canSecondLogin = false;
        $session = $request->getSession();
        $this->app->setCustomerType($this->app['config']['customer_type_normal']);
        if ($token->getUser()->getCustomerBasicInfo()) {
            if (in_array($token->getUser()->getCustomerBasicInfo()->getStatus()->getId(), $this->app['config']['can_second_login_status'])) {
                $canSecondLogin = true;
            }
        }
        if ($canSecondLogin) {
            // 古いsessionを削除
            $session->remove('eccube.second_login.form.frame');
            $session->remove('eccube.second_login.form.body');
            $session->remove('eccube.second_login.redirect.url');
            $session->remove('eccube.second_login.fail_redirect.url');
            if ($frame = $request->get('_second_login_frame', null, true)) {
                $session->set('eccube.second_login.form.frame', $frame);
            }
            if ($body = $request->get('_second_login_body', null, true)) {
                $session->set('eccube.second_login.form.body', $body);
            }
            $session->set('eccube.second_login.redirect.url', $this->determineTargetUrl($request));
            $session->set('eccube.second_login.fail_redirect.url', $this->options['login_path']);
            $responce = $this->httpUtils->createRedirectResponse($request, 'second_login');
        } else {
            $responce = parent::onAuthenticationSuccess($request, $token);
        }
        return $responce;
    }
}
