<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\EmailToken;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Model\AttendanceRecord;
use App\Model\Users_of_information;
use App\Model\Information;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
   public function index(Request $request)
   {
     $today = Carbon::now();
     $onemomthago = Carbon::now()->subMonth(1)->format('m-d');
     $login_user = Auth::user();
     $user = User::where('id', $login_user->id)->first();
     //homepage柱状图
     $att = AttendanceRecord::where('user_id',$user->id)
       ->where('attendance_date', '<=' , Carbon::today()->format('Y-m-d'))
       ->where('attendance_date', '>=' , Carbon::now()->subMonth(1)->format('Y-m-d'))
       ->whereNotNull('start_time')
       ->get()
       ->toArray();
    $date = [];
       foreach ($att as $v) {
         $date[] = date("Y-m-d", strtotime($v['attendance_date']));
       }
     //本月迟到次数
     $late = AttendanceRecord::whereNotNull('reason')
       ->where('attendance_date', '>=' , Carbon::now()->firstOfMonth()->format('Y-m-d'))
       ->where('attendance_date', '<=' , Carbon::now()->lastOfMonth()->format('Y-m-d'))
       ->where("user_id", Auth::user()->id)
       ->get()
       ->count();
     //本月请假次数
     $leave = AttendanceRecord::whereNotNull('leave_reason')
       ->where('attendance_date', '>=' , Carbon::now()->firstOfMonth()->format('Y-m-d'))
       ->where('attendance_date', '<=' , Carbon::today()->format('Y-m-d'))
       ->where("user_id", Auth::user()->id)

       ->get()
       ->count();
    if($user->email_verified_at == null){
        return redirect('/verified')->with('warning', "管理員に
            承認されていませんので、ログインできません。");
    }else{
        $days = $login_user->get_recent_days(30,'m-d');
        $start_time = $login_user->get_start_time_of_days(30);
        $end_time = $login_user->get_end_time_of_days(30);
        $infos = $login_user->get_all_unread_infos();
        return view('home',
            [
              'today' => $today,
              'late' => $late,
              'leave' => $leave,
              'atts' => $att,
              'onemomthago' => $onemomthago,
              'orderby_infors' => $infos,
              'days' => $days,
              't_date' => $date,
              'start_time' => $start_time,
              'end_time' => $end_time

            ])->with('message', "ログインできました。");
    }
  }


  public function showchart(Request $request)
  {
    $user = User::all();

    $att = AttendanceRecord::whereNotNull('reason')
    ->where('attendance_date', '<=' , Carbon::today()->format('Y-m-d'))
    ->where('attendance_date', '>=' , Carbon::now()->subMonth(1)->format('Y-m-d'))
    ->selectRaw('count(reason) as top,user_id')
    ->groupBy('user_id')
    ->get();

    $users = User::whereNotNull('email_verified_at')->get();
    $late_user_names = array();
    $leave_user_names = array();
    $late_times = array();
    $this_month_leave_times = array();
    $next_month_leave_times = array();
    $month_after_next_month_leave_times = array();
    foreach ($users as $user) {
        if($user->get_late_times() > 0){
            $late_user_names[] = $user->name;
            $late_times[] = $user->get_late_times();
        }
        if($user->get_leave_times(0) > 0 || $user->get_leave_times(1) > 0 || $user->get_leave_times(2) > 0){
            $leave_user_names[] = $user->name;
            $this_month_leave_times[] = $user->get_leave_times(0);
            $next_month_leave_times[] = $user->get_leave_times(1);
            $month_after_next_month_leave_times[] = $user->get_leave_times(2);
        }

    }
      return view('admin.chart',
       [
        'late_user_names' => $late_user_names,
        'leave_user_names' => $leave_user_names,
        'late_times' => $late_times,
        'this_month_leave_times' => $this_month_leave_times,
        'next_month_leave_times' => $next_month_leave_times,
        'month_after_next_month_leave_times' => $month_after_next_month_leave_times
       ]);
  }

  /**
   *
   *お知らせ一覧画面。
   *
   */
  public function info()
  {
    $login_user = Auth::user();
    $today = Carbon::now()->format('Y-m-d');
    $users_of_infors = $login_user->get_all_infos();

    return view('user_info',[
        "infos" => $users_of_infors
    ]);
  }

  /**
   *
   *お知らせの既読機能。
   *
   */
  public function readinfo(Request $request,$id)
  {
    //update read_at(既読)
    $read_infor = Users_of_information::find($id);
    $read_infor->read_at = Carbon::now();
    $read_infor->save();
    return redirect(route('home'));
  }
}
