<?php

class GeneralController extends Controller
{
    public function contactAction()
    {
        return $this->render(array(
            'user_name' => '',
            'password'  => '',
            '_token'    => $this->generateCsrfToken('account/signup'),
        ));
    }

    public function aboutAction()
    {
        return $this->render(array(
            'user_name' => '',
            'password'  => '',
            '_token'    => $this->generateCsrfToken('account/signup'),
        ));
    }

    //ユーザの投稿一覧
    public function postListAction($params)
    {
        $user = $this->db_manager->get('User')
                ->fetchByUserName($params['user_name']);
        if (!$user) {
            $this->forward404();
        }
    
        $statuses = $this->db_manager->get('Status')
                ->fetchAllByUserId($user['id']);

        return $this->render(array(
                'user'      => $user,
                'statuses'  => $statuses,
            ));
    }
}
