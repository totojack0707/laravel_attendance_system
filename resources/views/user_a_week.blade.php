<html>
  <head>
    <title>user_a_week</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

@section("content")
  <link href="{{ asset('/css/tao.css') }}" rel="stylesheet">
  <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <h3>一週間の勤怠表</h3>
    <div class="row">
      <div class="col-sm-8"></div>
      <div class="col-sm-4 top1">名前:{{ Auth::user()->name }}</div>
    </div>
    <div id="cools"class="row">
      @for ($i=6; $i>=0; $i--)
        @php
        $this_date = \Carbon\Carbon::today()->subDay($i);
        $show_record = null;
        @endphp
        <table class="top table table-striped">
          <tr>
            <th>日付</th>
            <th>出勤時間</th>
            <th>退勤時間</th>
          </tr>
          <tr>
            @foreach ($attendance_records as $attendance_record)
                @if ($attendance_record->attendance_date == $this_date)
                  @php
                    $show_record = $attendance_record;
                  @endphp
                  @break
                @endif
            @endforeach

            @if ($show_record)
              <td id="cool">{{ $show_record->attendance_date->format('Y年n月j日') }}<br>
                <button id="cool" class="btn btn-primary btn-lg"  data-toggle="modal" data-target="#myModal">
                	休み情報
                </button></td>

                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                	<div class="modal-dialog">
                		<div class="modal-content">
                			<div class="modal-header">
                				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                					&times;
                				</button>
                				<h4 class="modal-title" id="myModalLabel">
                					模态框（Modal）标题
                				</h4>
                			</div>
                			<div class="modal-body">
                				在这里添加一些文本
                			</div>
                			<div class="modal-footer">
                				<button type="button" class="btn btn-default" data-dismiss="modal">关闭
                				</button>
                				<button type="button" class="btn btn-primary">
                					提交更改
                				</button>
                			</div>
                		</div><!-- /.modal-content -->
                	</div><!-- /.modal -->
                </div>

              <td id="cool">{{ $show_record->start_time }}</td>
              <td id="cool">{{ $show_record->end_time }}</td>
            @else
              <td id="cool">{{ $this_date->format('Y年n月j日') }}</td>
              <td id="cool">出勤していません。</td>
              <td id="cool">出勤していません。</td>
            @endif
          </tr>
        </table>
        @if($show_record && $show_record->leave_start_time)
        <table>
          <tr>
            <th>欠勤開始時間</th>
            <th>欠勤終了時間</th>
            <th>欠勤理由</th>
            <th>欠勤承認状態</th>
            <th>欠勤申請時間</th>
            <th>承認時間</th>
          </tr>
          <tr>
            <td id="cool">{{ $show_record->leave_start_time ??"出勤していません。" }}</td>
            <td id="cool">{{ $show_record->leave_end_time ??"出勤していません。" }}</td>
            <td id="cool">{{ $show_record->leave_reason ??"出勤していません。" }}</td>
            <td id="cool">
              @if ($show_record && $show_record->mtb_leave_check_status_id == 1)
              承認待ち
              @elseif ($show_record && $show_record->mtb_leave_check_status_id == 2)
              承認済
              @elseif ($show_record && $show_record->mtb_leave_check_status_id == 3)
              断り
              @elseif(!$show_record || !$show_record->mtb_leave_check_status_id)
              出勤していません。
              @endif

          @if ($show_record)
            <td>{{ $show_record->attendance_date->format('Y年n月j日') }}</td>
            <td>{{ $show_record->start_time }}</td>
            <td>{{ $show_record->end_time }}</td>
          @else
            <td>{{ $this_date->format('Y年n月j日') }}</td>
            <td>出勤していません。</td>
            <td>出勤していません。</td>
          @endif

        </tr>
      </table>
      <table>
        <tr>
          <th>欠勤開始時間</th>
          <th>欠勤終了時間</th>
          <th>欠勤理由</th>
          <th>欠勤承認状態</th>
          <th>欠勤申請時間</th>
          <th>承認時間</th>
        </tr>
        <tr>
          <td id="cool">{{ $show_record->leave_start_time ??"出勤していません。" }}</td>
          <td id="cool">{{ $show_record->leave_end_time ??"出勤していません。" }}</td>
          <td id="cool">{{ $show_record->leave_reason ??"出勤していません。" }}</td>
          <td id="cool">
            @if ($show_record['mtb_leave_check_status_id'] == 1)
            承認待ち
            @elseif ($show_record['mtb_leave_check_status_id'] == 2)
            承認済
            @elseif ($show_record['mtb_leave_check_status_id'] == 3)
            断り
            @elseif(!$show_record['mtb_leave_check_status_id'])
            出勤していません。
            @endif
          </td>
          <td id="cool">{{ $show_record->leave_applicate_time??"出勤していません。" }}</td>
          <td id="cool">{{ $show_record->leave_check_time??"出勤していません。" }}</td>
        </tr>
      </table>
      @endfor
    </div>
  </body>
</html>
