@extends('layouts.app')
@section('title','休暇申込')

@section('content')

<div class="container">
  <script>
      $(document).ready(function(){
          $("#start_day").datepicker();
      });
  </script>
  <form action="{{ route('post_leave_request') }}" method="post">
    @csrf
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
           <div class="card-header">欠勤申込</div>
           <div class="card-body">
             <ul class="list-group">
               <li class="list-group-item" style="border:0px">
                 <span class="person-info-title">欠勤日</span>
                 <input id="start_day" class="form-control" name="attendance_date" autocomplete="off" type="text" value="{{ old('attendance_date') }}" placeholder="欠勤日を選択してください">
               </li>
               <li class="list-group-item" style="border:0px">
                 <span class="person-info-title">欠勤開始時間</span>
                 <div class="form-inline">
                   <select class="form-control" name="leave_start_hour" style="width:310px">
                     <option value="">時を選択してください</option>
                     @for($i = 8;$i <=31; $i++)
                      @if($i < 24)
                       <option value="{{ $i < 10 ? "0".$i : $i }}"
                         @if(old('leave_start_hour') && old('leave_start_hour') == ($i < 10 ? "0".$i : $i) )
                           selected
                         @endif>{{ $i < 10 ? "0".$i : $i }}</option>
                      @else
                       <option value="{{ "0".($i-24)  }}"
                         @if(old('leave_start_hour') && old('leave_start_hour') == "0".($i-24))
                           selected
                         @endif>{{ "0".($i-24) }}</option>
                      @endif
                     @endfor
                   </select>&nbsp:&nbsp
                   <select class="form-control" name="leave_start_minute" style="width:325px">
                     <option value="">分を選択してください</option>
                     @for($i = 0;$i <= 45; $i += 15)
                       <option value="{{ $i ==0 ? "0".$i : $i }}"
                         @if(old('leave_start_minute') && old('leave_start_minute') == ($i ==0 ? "0".$i : $i) )
                           selected
                         @endif>{{ $i ==0 ? "0".$i : $i }}</option>
                     @endfor
                   </select>
                 </li>
                 <li class="list-group-item" style="border:0px">
                   <span class="person-info-title">欠勤終了時間</span>
                   <div class="form-inline">
                     <select class="form-control" name="leave_end_hour" style="width:310px">
                       <option value="">時を選択してください</option>
                       @for($i = 8;$i <= 31; $i++)
                        @if($i < 24)
                         <option value="{{ $i < 10 ? "0".$i : $i }}"
                           @if(old('leave_end_hour') && old('leave_end_hour') == ($i < 10 ? "0".$i : $i) )
                             selected
                           @endif>{{ $i < 10 ? "0".$i : $i }}</option>
                        @else
                         <option value="{{ "0".($i-24)  }}"
                           @if(old('leave_end_hour') && old('leave_end_hour') == "0".($i-24))
                             selected
                           @endif>{{ "0".($i-24) }}</option>
                        @endif
                       @endfor
                     </select>&nbsp:&nbsp
                     <select class="form-control" name="leave_end_minute" style="width:325px">
                       <option value="">分を選択してください</option>
                       @for($i = 0;$i <= 45; $i += 15)
                         <option value="{{ $i ==0 ? "0".$i : $i }}"
                           @if(old('leave_end_minute') && old('leave_end_minute') == ($i ==0 ? "0".$i : $i) )
                             selected
                           @endif>{{ $i == 0 ? "0".$i : $i }}</option>
                       @endfor
                     </select>
                 </div>
               </li>
               <li class="list-group-item" style="border:0px">
                 <span class="person-info-title">申請理由</span>
                 <textarea class="form-control" name="leave_reason" rows="5" placeholder="申請理由を入力してください">{{ old('leave_reason') }}</textarea>
               </li>
               <li class="list-group-item" style="text-align:center;border:0px">
                   <input type="submit" class="btn btn-primary" value="申請">
                   <input type="reset" class="btn btn-primary"value="リセット">
               </li>
             </ul>
           </div>
         </div>
       </div>
     </div>
   </div>
 </form>
</div>

@endsection
