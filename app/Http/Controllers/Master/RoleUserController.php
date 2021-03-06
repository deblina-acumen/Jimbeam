<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use  App\Model\User;
use App\Model\Module_master;
use App\Model\Designation;
use Auth;
use App\Model\Role;
use DB;
use Mail;

class RoleUserController extends Controller
{
    public function user_list()
    {
        $data['title']="User Management";
        
       // $data['resource'] = Input::get('resource') ;
        $data['info'] = DB::table('users')
		->select('users.name as first_name','users.lastname as last_name','users.useId','profile_pic','users.email as email','users.is_deleted as is_deleted','users.is_active as is_active','users.id as userid','user_role.name as rolename')->join('user_role','user_role.id','=','users.role_id','left')
		->where('users.is_deleted','No')->where('user_role.type','=','user')
		->orderBy('users.id','desc')
		->get()->toArray();

        return view('master.roleUser.lists',$data);
    }

    public function add_user()
    {
		$user_id = Auth::user()->id;
        $data['title']="User Management";
        $data['roleList']=Role::where('is_active','Yes')->where('is_deleted','No')->where('type','=','user')->get();
       // $data['info1']= Role::where('is_active','Yes')->where('is_deleted','No')->get();
		//$data['designation']=$doc_list = Module_master::where('mod_type','department')->get();
		//$data['user']= $user = User::where('fl_archive','N')->where('id','!=',$user_id)->get();
		//t($user,1);
        return view('master.roleUser.add',$data);
    }

    
    public function save_user_data(Request $request)
    {
        
		$posted = $request->all();
		if(isset($posted['userId']) && $posted['userId']!='')
		{
			$have_user_id = User::where('useId',$posted['userId'])->get();
			if(!empty($have_user_id) && count($have_user_id)>0)
			{
				 return redirect('add-role-user')->with('error-msg', 'User Id already added');
			}
			$insert_data['name'] = isset($posted['name'])?$posted['name']:'';
			$insert_data['email'] = $to_email = isset($posted['email'])?$posted['email']:'';
			//$insert_data['description'] = isset($posted['description'])?$posted['description']:'';
			$insert_data['lastname'] = isset($posted['lastname'])?$posted['lastname']:'';
			$insert_data['useId'] = $userId = isset($posted['userId'])?$posted['userId']:'';
			$insert_data['password'] = isset($posted['password'])?bcrypt($posted['password']):bcrypt(123456);
			
			$insert_data['role_id'] = isset($posted['role'])?$posted['role']:0;
			$insert_data['created_by'] = Auth::user()->id;
			 $password =  isset($posted['password'])?$posted['password']:123456;
			//$id = User::insertGetId($insert_data);
			$profile_pic = $request->file('profile_pic');
			
			if($profile_pic !='')
			{
				
					$cat_image_pic_name = upload_file_single_with_name($profile_pic, 'RoleUserPic','RoleUserPic',$posted['name']);	
					if($cat_image_pic_name!='')
					{
						$insert_data['profile_pic'] = $cat_image_pic_name;
					}
				
			}
			$id = User::insertGetId($insert_data);
			if($id!='')
			{
			 $data2 = [
			        'userid'=>$userId,
					'password'=>$password,  
                ];
			   
              $template = 'master.user.NewUserAddMailSend'; // resources/views/mail/xyz.blade.php
        Mail::send($template, $data2, function ($message) use ($userId, $to_email) {
            $message->to($to_email, $userId)
                ->subject('New User Add');
            $message->from('no-repl@gmail.com', 'New user Add');
        });
					return redirect('role-user-list')->with('success-msg', 'User added successfully');
			}
			else
			{
			 return redirect('role-user-list')->with('error-msg', 'Please try after some time');
			}
		}
		else
		{
		 return redirect('role-user-list')->with('error-msg', 'Please Provide Uer Id');
		}			
		
    }
    
    public function user_edit($id)
    {
       if (base64_decode($id, true)) 
       {
		   $user_id = Auth::user()->id;
		
            $id = base64_decode($id);
            $data['title']="User Management";
			$data['roleList']=Role::where('is_active','Yes')->where('is_deleted','No')->where('type','=','user')->get();
            $data['info']=User::where('id',$id)->get(); 
            return view('master.roleUser.edit',$data);
       }
       else
            abort(404);
    }

    public function update_user_data(Request $request)
    {
       $posted = $request->all();// t($posted,1);
		$have_user_id = User::where('useId',$posted['id'])->get();
		if(isset($have_user_id[0]->id) && $have_user_id[0]->id != $posted['id'])
		{
			 return redirect('role-user-edit/'.base64_encode($posted['id']))->with('error-msg', 'User Id already added');
		}
		$insert_data['name'] = isset($posted['name'])?$posted['name']:'';
			$insert_data['email'] = isset($posted['email'])?$posted['email']:'';
			$insert_data['lastname'] = isset($posted['lastname'])?$posted['lastname']:'';
			$insert_data['useId'] = isset($posted['userId'])?$posted['userId']:'';
		if(isset($posted['password']) && $posted['password']!='')
		{
		 $insert_data['password'] = isset($posted['password'])?bcrypt($posted['password']):'';
		}
		
		$profile_pic = $request->file('profile_pic');
			
			if($profile_pic !='')
			{
				
					$cat_image_pic_name = upload_file_single_with_name($profile_pic, 'RoleUserPic','RoleUserPic',$posted['name']);	
					if($cat_image_pic_name!='')
					{
						$insert_data['profile_pic'] = $cat_image_pic_name;
					}
				
			}
			
			User::where('id',$posted['id'])->update($insert_data);
			return redirect('role-user-list')->with('success-msg', 'User updated successfully');
        
    }
	
	
	public function delete_user($id)
	{
		 $id= base64_decode($id);
		 $update_data['is_deleted'] = 'Yes';
		 $updated=User::where('id',$id)->update($update_data);
        if($updated)
            return redirect('role-user-list')->with('success-msg', 'User successfully deleted');
        else
        {
            return redirect('role-user-list')->with('error-msg', 'Please try after some time');    
        }
	}
	public function changeStatus($id,$status)
	{
		$id= base64_decode($id);
		$update_data['is_active'] = $status;
		$updated=User::where('id',$id)->update($update_data);
		if($updated)
            return redirect('role-user-list')->with('success-msg', 'Status successfully changed');
        else
        {
            return redirect('role-user-list')->with('error-msg', 'Please try after some time');    
        }
	}

    // sending mail when an new user is created
    public function send_mail($email="",$name="")
    {
        $to_name = $name;
        $to_email = $email;
        $data = [
                    'name' => $name,
                    'data' => $email,
                    'password' => '123456'
                ];
        $template = 'master.user.test_mail'; // resources/views/mail/xyz.blade.php
        Mail::send($template, $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Century Testing Mail');
            $message->from('salma.cyber.swift@gmail.com', 'Credentials');
        });
    }

    public function test_mail()
    {
        return view('master.user.test_mail');
    }
}