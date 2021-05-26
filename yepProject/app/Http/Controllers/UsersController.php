<?php

namespace App\Http\Controllers;

use App\Models\Academies;
use App\Models\Configurations;
use App\Models\LogUsers;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    public function __construct(){
        $this->middleware('adminPower');
    }

    public function index(){
        $optValues = Settings::where('set_code','=',Configurations::$SETTINGS_PAGE_LIMIT_CODE)->first();
        $limitCount = $optValues->set_value;

        $users = User::orderBy('name','asc')->paginate($limitCount);

        return view('pages.users',['data'=>$users]);
    }

    public function show($id){
        $user = User::find($id);

        return view('pages.userview',["data"=>$user]);
    }

    public function modify($id){
        $data = User::find($id);

        $powerArray = [
            ["name" => Configurations::$USER_POWER_ADMIN,"value" => Configurations::$USER_POWER_ADMIN],
            ["name" => Configurations::$USER_POWER_MANAGER,"value" => Configurations::$USER_POWER_MANAGER],
            ["name" => Configurations::$USER_POWER_TEACHER,"value" => Configurations::$USER_POWER_TEACHER]
        ];

        $liveArray = [
            ["name" => "Y", "value" => "Y"],["name" => "N","value" => "N"]
        ];

        $academies = Academies::orderBy('ac_name','asc')->get();

        return view('pages.usermodify',['data'=>$data,'powers' => $powerArray,'lives' => $liveArray,'academies'=>$academies]);
    }

    public function store(Request $request){
        $id = $request->get("up_id");

        $old = User::find($id);

        $user = Auth::user();

        $upName = $request->get("up_name");
        $upPower = $request->get("up_power");
        $upLive = $request->get("up_live");
        $upAcademyId = $request->get("up_academy_id");

        $targetId = $id;

        $logUserCtrl = new LogUsersController();

        if ($upName != $old->name){
            $fieldName = "name";
            $oldValue = $old->name;
            $newValue = $upName;

            $logUserCtrl->addLog($user->id,$targetId,$fieldName,$oldValue,$newValue);
        }

        if ($upPower != $old->power){
            $fieldName = "power";
            $oldValue = $old->power;
            $newValue = $upPower;

            $logUserCtrl->addLog($user->id,$targetId,$fieldName,$oldValue,$newValue);
        }

        if ($upLive != $old->live){
            $fieldName = "live";
            $oldValue = $old->live;
            $newValue = $upLive;

            $logUserCtrl->addLog($user->id,$targetId,$fieldName,$oldValue,$newValue);

            if ($old->live == "Y" && $upLive == "N"){
                $old->drop_date = now();
            }
        }

        if ($upAcademyId != $old->academy_id){
            $fieldName = "academy_id";
            $oldValue = $old->academy_id;
            $newValue = $upAcademyId;

            $logUserCtrl->addLog($user->id,$targetId,$fieldName,$oldValue,$newValue);
        }

        $old->name = $upName;
        $old->power = $upPower;
        $old->live = $upLive;
        $old->academy_id = $upAcademyId;

        try {
            $old->save();
            return redirect()->route('userView',['id'=>$id]);
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['msg'=>'FAIL_TO_UPDATE']);
        }
    }

    public function changePw(Request $request){
        $upId = $request->get("up_new_id");
        $pw = $request->get("up_new_password");

        $my = Auth::user();

        $user_id = $my->id;
        $target_id = $upId;
        $field_name = "password";
        $old_value = "";
        $new_value = "비밀번호변경";

        try{
            $user = User::find($upId);
            $user->password = Hash::make($pw);
            $user->save();

            $logUserCtrl = new LogUsersController();
            $logUserCtrl->addLog($user_id,$target_id,$field_name,$old_value,$new_value);

            return redirect()->route('userView',['id'=>$upId]);
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['msg'=>'NOT_CHANGE_PASSWORD']);
        }
    }
}
