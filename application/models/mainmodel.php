<?php
class mainmodel extends CI_model 
{
public function insert($a,$b)
	{
		$this->db->insert("login",$b);
		$loginid=$this->db->insert_id();
		$a['loginid']=$loginid;
		$this->db->insert("reg",$a);
	}
	public function ecps($pass)
	{
		return password_hash($pass, PASSWORD_BCRYPT);
	}
	public function selectpass($email,$pass)
	{
		$this->db->select('password');
		$this->db->from("login");
		$this->db->where("email",$email);
		$qry=$this->db->get()->row('password');
		return $this->verifypass($pass,$qry);
	}
	public function verifypass($pass,$qry)
	{
		return password_verify($pass,$qry);
	}
	public function getuserid($email)
	{
		$this->db->select('id');
		$this->db->from("login");
		$this->db->where("email",$email);
		return $this->db->get()->row('id');
	}
	public function getuser($id)
	{
		$this->db->select('*');
		$this->db->from("login");
		$this->db->where("id",$id);
		return $this->db->get()->row();
	}
	public function view()
	{
		$this->db->select('*');
		$this->db->join('login','login.id=reg.loginid','inner');
		$qry=$this->db->get("reg");
		return $qry;
	}
	public function singledata($id)
	{
		$this->db->select('*');
		$this->db->where("id",$id);
		$qry=$this->db->get("reg");
		return $qry;
	}
	public function singleselect()
	{
		$qry=$this->db->get("reg");
		return $qry;
	}

	public function approvedetails($id)
	{
		$this->db->set('status','1');
		$qry=$this->db->where("id",$id);
		$qry=$this->db->update("login");
		return $qry;
	}
	public function rejectdetails($id)
	{
		$this->db->set('status','2');
		$qry=$this->db->where("id",$id);
		$qry=$this->db->update("login");
		return $qry;
	}
	
}


