<?php
	class Friend extends MY_Controller {
		public $user_id;
		private $join;
		function __construct() {
			parent::__construct();
			$this->load->model('User_model');
			$this->load->model('Post_model');
		}
		
		function index() {
			if($this->session->userdata('type') == 'guest') {
				$this->_guest();
			} elseif($this->session->userdata('type') == 'admin') {
				$str = '<p>你好管理员！</p><p>你或许需要'  . anchor('admin/co_request', '管理申请') . '</p>';
				$str .= '<p>或者' . anchor('admin/list_all_user', '查看所有注册用户') . '</p>';
				static_view($str, '首页');
			} else {
				$data['title'] = '首页';
				$data['posts'] = $this->Post_model->post_string($this->session->userdata('id'));
				$join = array(
					'school' => array('school_id', 'id'),
					'province' => array('province_id', 'id')
				);
				$data['info'] = $this->User_model->get_info($this->session->userdata('id'), $join);
				$followers = $this->User_model->get_followers($this->session->userdata('id'));
				$following = $this->User_model->get_following($this->session->userdata('id'));
				$data['followers_num'] = $followers ? count($followers) : 0;
				$data['following_num'] = $following ? count($following) : 0;
				$data['js'] = array('post.js');
				$data['main_content'] = 'friend/list_view';
				$this->load->view('includes/template_view', $data);
			}
		}
	}