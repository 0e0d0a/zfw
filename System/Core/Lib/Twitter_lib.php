<?php
require_once(ROOT.'System/Core/Lib/jmathai-twitter-async/EpiCurl.php');
require_once(ROOT.'System/Core/Lib/jmathai-twitter-async/EpiOAuth.php');
require_once(ROOT.'System/Core/Lib/jmathai-twitter-async/EpiTwitter.php');

class twitter extends Core {

    private $_etw;

    /**
     * Constructor
     */
    function __construct() {
        $this->cfg()->load('twitter');
        // Require Facebook app keys to be configured
        if (!$this->cfg()->get('twitter', 'twitter_consumer_key') || !$this->cfg()->get('twitter', 'twitter_consumer_secret')) {
            echo 'Visit ' . anchor('http://dev.twitter.com/apps', 'http://dev.twitter.com/apps') . ' to register your app.';
            die;
        }

        // Create EpiTwitter object
        $this->_etw = new EpiTwitter($this->cfg()->get('twitter', 'twitter_consumer_key'), $this->cfg()->get('twitter', 'twitter_consumer_secret'));

        // Complain loudly if base url contains "://localhost"
        if (strpos($this->cfg()->get('twitter', 'base_url'), '://localhost') !== FALSE) {
            echo 'Erm... Twitter doesn\'t like your base URL to start with "http://localhost/".<br />';
            die;
        }
    }
    

    function index() {
        if ($this->req()->get('oauth_token')) {
            try {
                // Perform token exchange
                $this->_etw->setToken($this->req()->get('oauth_token'));
                $twitter_token = $this->_etw->getAccessToken();
                $this->_etw->setToken($twitter_token->oauth_token, $twitter_token->oauth_token_secret);

                // Get account credentials
                $twitter_info = $this->_etw->get_accountVerify_credentials()->response;
            } catch (Exception $e) {
                $this->authentication->is_signed_in() ?
                                redirect('account/account_linked') :
                                redirect('account/sign_up');
            }

            // Check if user has connect twitter to a3m
            if ($user = $this->account_twitter_model->get_by_twitter_id($twitter_info['id'])) {
                // Check if user is not signed in on a3m
                if (!$this->authentication->is_signed_in()) {
                    // Run sign in routine
                    $this->authentication->sign_in($user->account_id);
                }
                $user->account_id === $this->session->userdata('account_id') ?
                                $this->session->set_flashdata('linked_error', sprintf(lang('linked_linked_with_this_account'), lang('connect_twitter'))) :
                                $this->session->set_flashdata('linked_error', sprintf(lang('linked_linked_with_another_account'), lang('connect_twitter')));
                redirect('account/account_linked');
            }
            // The user has not connect twitter to a3m
            else {
                // Check if user is signed in on a3m
                if (!$this->authentication->is_signed_in()) {
                    // Store user's twitter data in session
                    $this->session->set_userdata('connect_create', array(
                        array(
                            'provider' => 'twitter',
                            'provider_id' => $twitter_info['id'],
                            'username' => $twitter_info['screen_name'],
                            'token' => $twitter_token->oauth_token,
                            'secret' => $twitter_token->oauth_token_secret
                        ),
                        array(
                            'fullname' => $twitter_info['name'],
                            'picture' => $twitter_info['profile_image_url']
                        )
                    ));

                    // Create a3m account
                    redirect('account/connect_create');
                } else {
                    // Connect twitter to a3m
                    $this->account_twitter_model->insert($this->session->userdata('account_id'), $twitter_info['id'], $twitter_token->oauth_token, $twitter_token->oauth_token_secret);
                    $this->session->set_flashdata('linked_info', sprintf(lang('linked_linked_with_your_account'), lang('connect_twitter')));
                    redirect('account/account_linked');
                }
            }
        }

        // Redirect to authorize url
        header("Location: " . $this->_etw->getAuthenticateUrl());
    }

}