<?php

class AuthLib extends \Slim\Middleware
{
	
	public function call(){
		// no auth header
        if(!isset($this->headers['Authorization'])) {
            $this->app->getLog()->error("No authorization header");
            $view = $this->app->view();
            $view->setData("data", array("message" => "Access credentials not supplied"));
            $view->render('error.php', 400);
        } else {
             try {
                $authHeader = $this->headers['Authorization'];
                $auth = new \Service\Mysql\AuthService($this->mysql, $this->config);
                $validated_user_id = $auth->verifyOAuth($authHeader);
                $this->app->user_id = $validated_user_id;
             } catch (\Exception $e) {
                $view = $this->app->view();
                $view->setData("data", array("message" => $e->getMessage()));
                $view->render('error.php', $e->getCode());
             }
           }

        // this line is required for the application to proceed
        $this->next->call();
	}
}