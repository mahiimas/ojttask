<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class main extends CI_Controller {
	

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
	}
	public function userform()
	{
		$this->load->view('userform');
	}
	public function insert()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules("firstname","firstname",'required|max_length[25]');
		$this->form_validation->set_rules("lastname","lastname",'required|max_length[25]');
		$this->form_validation->set_rules("username","username",'required|max_length');
		$this->form_validation->set_rules("phone","phone",'required');
		$this->form_validation->set_rules("email","email",'required');
		$this->form_validation->set_rules("password","password",'required');
		if($this->form_validation->run())
		{
			$this->load->model('mainmodel');
			$pass=$this->input->post("password");
			$ep=$this->mainmodel->ecps($pass);
			$a=array("firstname"=>$this->input->post("firstname"),"lastname"=>$this->input->post("lastname"),"username"=>$this->input->post("username"),"phone"=>$this->input->post("phone"));
			$b=array("email"=>$this->input->post("email"),"password"=>$ep,"usertype"=>'2');
		$this->mainmodel->insert($a,$b);
		redirect(base_url().'main/userform');
	    }
	}
	
	public function login()
	{
		$this->load->view('login');
	}
	
	public function logns()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules("email","email",'required');
		$this->form_validation->set_rules("password","password",'required');
		if($this->form_validation->run())
		{
			$this->load->model('mainmodel');
			$email=$this->input->post("email");
			$pass=$this->input->post("password");
			$rslt=$this->mainmodel->selectpass($email,$pass);
				if ($rslt)
				 {
				 	$id=$this->mainmodel->getuserid($email);

				 	$user=$this->mainmodel->getuser($id);
				 	$this->load->library(array('session'));
				 	$this->session->set_userdata(array
				 		('id'=>(int)$user->id,
				 		'usertype'=>$user->usertype,'status'=>$user->status));
				 	if($_SESSION['usertype']=='2' && $_SESSION['status']=='1')
				 	{
				 		redirect(base_url().'main/userhome');
				 	}
				 	elseif($_SESSION['usertype']=='1' && $_SESSION['status']=='1')
				 	{
				 		redirect(base_url().'main/admin');
				 	}
				 	else
				 	{
				 		echo "Waiting for approval";
				 	}
				 }
			     else
			     {
			     	echo "invalid user";
			     }
			 }
			 else
			 {
			 	redirect('main/login','refresh');
			 }
				 
}
public function admin()
	{
		$this->load->view('admin');
	}
	public function aview()
	{
		$this->load->model('mainmodel');
	$data['n']=$this->mainmodel->view();
		$this->load->view('aview',$data);
	}
	public function approvedetails()
	{
		$this->load->model('mainmodel');
		$id=$this->uri->segment(3);
		$this->mainmodel->approvedetails($id);
		redirect('main/aview','refresh');
	}
	public function rejectdetails()
	{
		$this->load->model('mainmodel');
		$id=$this->uri->segment(3);
		$this->mainmodel->rejectdetails($id);
		redirect('main/aview','refresh');
	}
	public function userhome()
	{
		$this->load->view('userhome');
	}
}
	