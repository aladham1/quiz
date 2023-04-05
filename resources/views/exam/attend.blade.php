@extends('dashboard.layouts.master')

@section('title')
	{{ $exam->title }}
@endsection

@section('css')
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon"><!--title icon-->
	<link href="{{ asset('css/style_exam.css?vrand(10,1000)') }}" rel="stylesheet" type="text/css" />
	<style>
		.pushed {
		  transition: margin-left 200ms ease 100ms;
		  height: 0px;
		}

		.pulled {
		  transition: margin-left 200ms ease 100ms;
		  height: 0px;
		}

		.trns {
		  transition: left 100ms ease, height 20ms ease ;
		}

		.relaxed {
		  transition: margin-left 200ms ease 100ms;
		  height: 100%;
		}

		.neutral-bg {
		  min-height: 100px;
		  background-color: #c3c3c3;
		  border: 1px solid #b6b6b6;
		  border-radius: 4px;
		}
		.no-bg {
		  background-color: none;
		  border: none;
		}
		button{
		  border: 0;
		  outline: @border;
		  padding: 1.5rem 2rem;
		  font-family: monospace;
		  box-shadow: 2px 2px 4px 0px black !important;
		  transition: all .3s;
		  cursor: pointer;
		  border-radius: 5px;
		  border-bottom: 4px solid lighten(gray, 70%);
		  font-size: 16px !important;
		}

		button:hover{
			box-shadow: 2px 2px 4px 0px black !important;
			transform: scale(1.03);
		}

		button:focus{
			box-shadow: none !important;
		}

		button:active{
			box-shadow: 0px 4px 8px rgba(darken(dodgerblue, 30%));
			transform: scale(.98);
		}

	</style>
	<script src="https://unpkg.com/konva@7.0.3/konva.min.js"></script>
	@if(isset($ans))
		<style>
			.right {
				background:#badc58;
				border: 4px solid #badc58;
			}
			.wrong{
				background:#ff7979;
				border-bottom: 4px solid #ff7979;
			}
		</style>
	@endif
@endsection

@section('content')

@php
	$englsh_array=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$arabic_array=array('ء','ؤ','ي','و','ه','ن','م','ل','ك','ق','ف','غ','ع','ظ','ط','ض','ص','ش','س','ز','ر','ذ','د','خ','ح','ج','ث','ت','ب','ا');
	$turkish_array = array('A','B','C','Ç','D','E','F','G','Ğ','H','I','İ','J','K','L','M','N','O','Ö','P','R','S','Ş','T','U','Ü','V','Y','Z');
	$number_array = array('1','2','3','4','5','6','7','8','9','0','1','2','3','4','5','6','7','8','9');
	$option_letter = ['A', 'B', 'C', 'D'];
	$arabic_range ='[\x{0600}-\x{06FF}]';
	$puzzle_piece_full_url_parser = function ($piece) {
		if (isset($piece['image'])) {
			$piece['image'] = Storage::url($piece['image']);
		}
		return $piece;
	}
@endphp
<header class="clrhdr">
    <div class="leftIcn">
    </div>

    <div class="pgnme p-0">
        {{ $exam->title }}
    </div>

    <div class="rgtIcn">
    </div>
</header>
<div class="pgebg1">

	<div class="hdr2">
		<div class="qpgHdr">
			@if (isset($exam->time_limit) && $exam->time_limit != -1)<span class="prgBr" style="width:100%"></span>@endif
			<span class="prgTxt">السؤال <span class="index">1</span> من {{ count($questions) }}</span>
		</div>
		@if (isset($exam->time_limit) && $exam->time_limit != -1)<div class="tmrTxt" id="showTimer">00:00</div>@endif
	</div>
	<canvas id="drawingCnvs" style="width: 0px; height: 0px;"></canvas>
	<form method="POST" id="exam_form" enctype="multipart/form-data" action="{{ route('exams.mark', ['exam' => $exam->id]) /*Auth::check() ? route('exams.mark', ['exam' => $exam->id]) : route('guest.exams.mark', ['exam' => $exam->id])*/ }}">
		@foreach ($questions as $order_with_type => $q)
			@php
				$qtitle = '<div class="qsTxt1">' . $q['question'] . '</div>';
				$qsn_style = 'height: 190px;';
				$type = $q['type'] ?? $order_with_type;
				if(!isset($q['image']) && !isset($q['video'])){ $qsn_style = 'height: 50px;'; }

			@endphp

			<div class="qsn_section qsn_{{ $loop->iteration }} serial_{{ $loop->iteration }}" id='tid_{{ $q['id']."_".$type }}' style="@if($loop->iteration!=1)display: none;@endif">

				<section class="boxB tcenter">
					@if ( Str::contains($type, 'Puzzle') )
						{!! $qtitle !!}
						@php
							$q['pieces'] = json_decode($q['pieces'], true);
							$q['pieces'] = array_map($puzzle_piece_full_url_parser, $q['pieces']);
							$student_answer = null;
							if(isset($ans)) {
								$student_answer = $wrong_questions[$order_with_type] ?? $q['pieces'];
							}
						@endphp
						@include('puzzle.play', ["puzzle" => Storage::url($q['puzzle_image']), "i" => $loop->iteration, "pieces" => json_encode($q['pieces']), 'id' => $q['id'], 'student_answer' => json_encode($student_answer), 'ans' => $ans ?? false])

					@else
						@php
							$q['image'] = json_decode($q['image'], true) ?? $q['image'] ;
							$q['video'] = json_decode($q['video'], true) ?? $q['video'] ;
							$q['audio'] = json_decode($q['audio'], true) ?? $q['audio'] ;
						@endphp
						@if (isset($q['image']))
							<div class="@if(is_array($q['image']))qsimg2 @else qsimg1 @endif" style="height: 190px;">

								@if( is_array($q['image']) /*|| (Str::contains($type, Str::snake('WordGame')) || Str::contains($q['type'], Str::snake('WordGame')))*/ )
									@foreach ($q['image'] as $img)
										<div class="qsimg4">
											<img src="{{ Storage::url($img) }}"/>
										</div>
									@endforeach

								@else
									<img src="{{ Storage::url($q['image']) }}"/>

								@endif
							</div>

						@elseif (isset($q['video']))
							@if ( is_array($q['audio']))
								@foreach ($q['video'] as $video)
									<div class="qsimg1" style="height: 190px;">
										<iframe src="https://www.youtube.com/embed/{{ $video['video_id'] ?? $video }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $video['width'] ?? '100%' }}' height='{{ $video['height'] ?? '' }}' allowfullscreen></iframe>
									</div>
								@endforeach
							@else
								<div class="qsimg1" style="height: 190px;">
									<iframe src="https://www.youtube.com/embed/{{ $q['video']['video_id'] ?? $q['video'] }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $q['video']['width'] ?? '100%' }}' height='{{ $q['video']['height'] ?? '' }}' allowfullscreen></iframe>
								</div>
							@endif


						@elseif (isset($q['audio']))
							@if ( is_array($q['audio']))
								@foreach ($q['audio'] as $track)
									<div class="qsimg1" style="height: 50px;">
										<audio controls>
											<source src="{{ Storage::url($track) }}">
											Your browser does not support the audio element.
										</audio>
									</div>
								@endforeach
							@else
								<div class="qsimg1" style="height: 50px;">
									<audio controls>
										<source src="{{ Storage::url($q['audio']) }}">
										Your browser does not support the audio element.
									</audio>
								</div>
							@endif
						@endif

						{!! $qtitle !!}

						@if ( Str::contains($type, 'WordGame') )
							@php
								$identifier_lang = 0; // 0 = english,1=arabic, 2=number, 3= floating number
								$answ_array=[];

								shuffle($englsh_array);
								shuffle($arabic_array);
								shuffle($number_array);
								shuffle($turkish_array);

								$length_ans = mb_strlen($q['answer'],"UTF-8");
								//if space is there counting that
								$space_index=[];
								$ans_bx = '';
								if ( !isset($ans) )
								{

									if($q['answer']!=null && $q['answer']!=''){
										if( mb_ereg('[\x{0600}-\x{06FF}]', $q['answer']) > 0 ){
											$q['answer'] = implode('' ,array_reverse(mb_str_split($q['answer']))); // reversing because answer boxes are right to left
										}
										for($m=0;$m<$length_ans;$m++){
											if(mb_substr($q['answer'], $m, 1, 'utf8')==' '){
												$space_index[]=$m;
											}else{
												$answ_array[] = mb_strtoupper( str_replace(['i', 'ı'], ['İ', 'I'], mb_substr($q['answer'], $m, 1, 'utf8')) );
											}
										}
									}

									//print_r($answ_array); echo count($answ_array);
									for($j=0;$j<$length_ans;$j++){
										if(in_array($j,$space_index)){
											$ans_bx .= '<div class="w1Ltr whitespace" style=" display: inline-block; width: 8.33%; padding: 0 1px 2px; box-sizing: border-box;"><span style="border:none; " class="usr_ans_'.$loop->iteration.'"> </span></div>';
										}else{
											$ans_bx .= '<div class="w1Ltr"><span class="usr_ans_'.$loop->iteration.'" onclick="resetWord(this,'.$loop->iteration.')"></span></div>';
										}
									}
									$ans_bx .= '<div class="rershBtn" onclick="resetAnsArea('.$loop->iteration.')"></div>';

									$filter_ans = implode('', $answ_array);

									$needed_extra = (12 - count($answ_array));
									if(is_numeric($q['answer'])){
										for($k=0;$k<$needed_extra;$k++){
											$answ_array[] = $number_array[$k];
										}
										$identifier_lang = 2;
									}else{ //this is string or a floating number

										//check floating number
										$split_ans=[];
										if($q['answer']!=''){
											$split_ans=explode('.',$q['answer']);
										}
										if(count($split_ans)>=2){
											if(is_numeric($split_ans[0]) && is_numeric($split_ans[1])){
												//this is a float number
												for($k=0;$k<$needed_extra;$k++){
													$answ_array[] = $number_array[$k];
												}
												$answ_array[] = '.';
												$identifier_lang=3;
											}
										}else{ //this is a string
											$len_answ = mb_strlen($q['answer'],"UTF-8");
											if(mb_ereg($arabic_range, $q['answer'])){  // arabic string
												for($k=0;$k<$needed_extra;$k++){ //echo $arabic_array[$k].' - '.$needed_extra;
													$answ_array[] = $arabic_array[$k];
												}
												$identifier_lang = 1;
											}else if(strlen($q['answer']) == mb_strlen($q['answer'], 'utf-8')){// english text
												for($k=0;$k<$needed_extra;$k++){
													$answ_array[] = $englsh_array[$k];
												}
											}else{
												for($k=0;$k<$needed_extra;$k++){
													//$identifier_lang = 1; // turkish write like english
													$answ_array[] = $turkish_array[$k];
												}
											}
										}
									}
									shuffle($answ_array);
								}
								else
								{
									$user_ans = $wrong_questions[$order_with_type] ?? $q['answer'];

									$ans = $q['answer'];
									if( mb_ereg('[\x{0600}-\x{06FF}]', $q['answer']) > 0 ){
										$ans= implode('' ,array_reverse(mb_str_split($ans)));
										$user_ans= implode('' ,array_reverse(mb_str_split($user_ans)));
									}
									$right_ans_html = '';
									for($j=0;$j<$length_ans;$j++){
										if(in_array($j,$space_index)){
											$ans_bx .= '<div class="w1Ltr whitespace" style=" display: inline-block; width: 8.33%; padding: 0 1px 2px; box-sizing: border-box;"><span style="border:none; " class="usr_ans_'.$loop->iteration.'"> </span></div>';
											$right_ans_html .= '<div class="w1Ltr whitespace right" style=" display: inline-block; width: 8.33%; padding: 0 1px 2px; box-sizing: border-box;"><span style="border:none; " class="usr_ans_'.$loop->iteration.'"> </span></div>';
										}else{
											$ans_bx .= '<div class="w1Ltr txtin"><span class="usr_ans_'.$loop->iteration.'">'.mb_substr($user_ans, $j, 1, 'utf8').'</span></div>';
											$right_ans_html .= '<div class="w1Ltr right mr-1 rounded"><span class="usr_ans_'.$loop->iteration.'">'.mb_substr($ans, $j, 1, 'utf8').'</span></div>';
										}
									}
								}
							@endphp

							@if ( !isset($ans) )
								<div class="wgBx3">
									<input type="hidden" name='{{ $q['id'] }}_WordGame' id='{{ $loop->iteration }}_WordGame' value="">
									<div class="wrd1">
										{!! $ans_bx !!}
									</div>
								</div>
								<div class="wgBx4">
									<div class="wrd2">
										@foreach ($answ_array as $k => $item)
											<div class="ltrBtn" onclick="showAnsInBox(this,{{ $loop->parent->iteration }},'{{ $answ_array[$k] }}',{{ $identifier_lang }})"><span>{{ $answ_array[$k] }}</span></div>
										@endforeach
									</div>
								</div>
							@else
								<div class="wgBx3">
									<div class="wrd1">
									@if(
									 	(($exam->review_type == 0 || $exam->review_type == 2 ) && $q['answer'] == $option['index'])
										|| (($exam->review_type == 1 || $exam->review_type == 2 ) && $q['answer'] != $option['index'])
									)
										{!! $ans_bx !!}
									@endif

									</div>
									<br>
									<div class="wrd1">
										{!! $right_ans_html !!}
									</div>
								</div>
							@endif

						@elseif ( Str::contains($type, 'MultipleChoiceQuestion') )
							<aside class="ansrlst">
								@php
									$q['options'] = is_array($q['options']) ? $q['options'] : json_decode($q['options'], true);
									shuffle($q['options']);
								@endphp
								<input class="ansin" type="radio" id="xans_{{ $q['id'] }}_{{ 'default' }}" value="none" style="display: none" checked name="{{ $q['id'] }}_MultipleChoiceQuestion">
								@foreach ($q['options'] as $option)
									@php
										$className = '';
										if ( isset($ans) ) {
											if( ($exam->review_type == 0 || $exam->review_type == 2 ) && $q['answer'] == $option['index'] ) {
												$className = 'right';
											}
											else if( ($exam->review_type == 1 || $exam->review_type == 2 ) && $q['answer'] != $option['index'] ) {
												$className = 'wrong';
											}
										}
									@endphp
									@if (isset($option[$option['type']]))
									<input class="ansin" type="radio" id="xans_{{ $q['id'] }}_{{ $option['index'] }}" value="{{ $option['index'] }}" name="{{ $q['id'] }}_MultipleChoiceQuestion">
									<label for="xans_{{ $q['id'] }}_{{ $option['index'] }}" class="ansbx @if (isset($ans)) {{ $className }} @endif">

										<span class="ansnmbr">{{ $option_letter[$loop->index] }}</span>

										@if ($option['type'] == 'text')
											<aside class="anstxt">{{ $option['text'] ?? $option['index'] }}</aside>

										@elseif ($option['type'] == 'image')
											<aside class="anstxt">&nbsp;</aside>
											@if(isset($option['image']))
												<div class="ansPhvw"><img src="{{ Storage::url($option['image']) }}"></div>
											@else
												<aside class="anstxt">{{ $option['text'] ?? $option['index'] }}</aside>
											@endif
										@elseif ($option['type'] == 'audio')
											<aside class="anstxt">&nbsp;</aside>
											@if(isset($option['audio']))
												<audio controls>
													<source src="{{ Storage::url($option['audio']) }}" type="audio/wav">
													Your browser does not support the audio element.
												</audio>
											@else
												<aside class="anstxt">{{ $option['text'] ?? $option['index'] }}</aside>
											@endif
										@endif

									</label>
									@endif
								@endforeach

							</aside>
						@endif

					@endif
				</section>

				<section class="exmFtr py-1" style="width:100%">
					<div class="btnBr1 pt-1">
						<div class="backBtn" onclick="goprevious({{ $loop->iteration }})">Back</div>
						<div class="quesnmbr"><span>{{ $loop->iteration }}</span> / {{ count($questions) }}</div>
						<div class="nextBtn" onclick="gotonext({{ $loop->iteration }},{{ count($questions) }})">Next</div>
					</div>
					<div class="set8 tcenter mt-1">Powered by Questanya.com</div>
				</section>
			</div>
		@endforeach
			@csrf
	</form>
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function(){
		base_url = $('.base_url').val();
		var exam_start_time = new Date().getTime();
        localStorage.setItem("exam_start_time",exam_start_time);
		$(document).on("keydown", disableF5);
		$('.qsimg1').on("touchend", function(e) {
            var offset = $(this).offset();
            var X = (e.pageX - offset.left);
            var Y = (e.pageY - offset.top);
            console.log('X: ' + X + ', Y: ' + Y);
        });

		@if (isset($exam->time_limit) && $exam->time_limit != -1)
			var count_time = {{ $exam->time_limit *60 }};
			display = document.querySelector('#showTimer');
			startTimer(count_time, display,{{ count($questions) }});
		@endif
	});

 	function gotonext(index,total){
		var next = parseInt(parseInt(index) + 1);

		if(next<=total){
			$('.qsn_section').hide();
			$('.qsn_'+next).show();
			$('.index').text(next);
		}else{
			//var connf = connfirm("Are you sure to submit the exam?");
			//if(connf==true){
				showLoader();
				$('#exam_form').submit();
				/*
				$('.qsn_section').each(function(i) {
					var qid = $(this).attr('id');
					if( qid.indexOf('WordGame') != -1 ){
							var selected_ans='';
							$('.usr_ans_'+qsn_id).each(function(){
								selected_ans += $(this).text();
							});

							if(typeof selected_ans !='undefined'){
								store_response = store_response+';'+qsn_id+':'+selected_ans;
								var arabic = /[\u0600-\u06FF]/;
									console.log("selected ans=",selected_ans);
								if((selected_ans!=null) && (selected_ans!='')){
									console.log(selected_ans.toLowerCase()+" = "+list.records[i].word_answer.toLowerCase());
									if(selected_ans.toLowerCase() == list.records[i].word_answer.toLowerCase()){
										total_correct++;
									}
								}
							}
							console.log("ans stored="+store_response);
					}else if( qid.indexOf('Puzzle') != -1 ) {

							var dd_ans_correct = get_ans_poses(i + 1);
							console.log(dd_ans_correct);
					} else if( qid.indexOf('MultipleChoiceQuestion') != -1 ) {
						var selected_ans = $('input[type=radio][name=answlct_'+(i + 1)+']:checked').val() ?? null;
						store_response = store_response+';'+qsn_id+':'+selected_ans;
						console.log("qsn_id = "+qsn_id);
						console.log(store_response);
					}

				})
				$.post(base_url+"getQsnList",{exam_id:{{ $exam->id }}},function(rs){ console.log(rs); hideLoader();
					var list = $.parseJSON(rs);
					console.log(list);
					var total_correct = 0;
					var store_response='';
					for(var i=0;i<list.records.length;i++){
						var qsn_id = list.records[i].quest_id; console.log("qsn id="+qsn_id);



						localStorage.setItem("correct",total_correct);
 	       				localStorage.setItem("user_response",store_response);

						console.log("total correct="+total_correct);
					}
					var exam_end_time = new Date().getTime();
					//total time taken to complete the exam
					var start_time = localStorage.getItem("exam_start_time");
					var difference_time = (exam_end_time - start_time);
					//alert(store_response);
					var percentage_got = ((parseInt(total_correct) * 100) / parseInt(list.exam_info['count']));
						$.post(base_url+"saveExamData",{difference_time:difference_time,percentage_got:percentage_got,total_correct:total_correct,exam_started:1,exam_id:{{ $exam->id }},store_response:store_response},function(rss){
							if(rss==1){
								window.location.href=base_url+"thankyou";
							}else{
								console.log(rss);
								//alert("Something went wrong!");
								//location.reload();
							}

						});
				});*/
			//}
		}
 	}

 	function goprevious(index){
		 var prev = parseInt(parseInt(index) - 1);
		 if(prev!=0){
			$('.qsn_section').hide();
			$('.qsn_'+prev).show();
			$('.index').text(prev);
		 }
 	}

	function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

	function showAnsInBox(obj,index,value,identifier){ console.log("index="+index);

			if(identifier==1){ //arabic text
				$($('.serial_'+index).find('.w1Ltr').get().reverse()).each(function(i){ console.log($(this).find('span').text());
					if($(this).hasClass('whitespace') == false && ($(this).find('span').text()=='' || $(this).find('span').text()==' ') ){
							$(this).find('span').text(value);
							var inpt = $('#'+index+'_WordGame');
							//$(this).prev().hasClass('whitespace') ? i++ : false;
							inpt.val(inpt.val().slice(0, i) + value + inpt.val().slice(i));
							$(this).prev().hasClass('whitespace') ? inpt.val(inpt.val() + ' ') : false;
							$(this).addClass('txtin');
							$(obj).find('span').text('');
							$(obj).removeAttr('onclick');
							return false;
					}else{
						console.log("this situation");
					}
				});

			}else{
				$('.serial_'+index).find('.w1Ltr').each(function(i){
					if($(this).hasClass('whitespace') == false && $(this).find('span').text()==''){
						$(this).find('span').text(value);
						var inpt = $('#'+index+'_WordGame');
						//$(this).prev().hasClass('w1Ltr') == false ? i++ : false;
						inpt.val(inpt.val().slice(0, i) + value + inpt.val().slice(i));
						$(this).next().hasClass('whitespace')? inpt.val(inpt.val() + ' ') : false;
						$(this).addClass('txtin');
						$(obj).find('span').text('');
						$(obj).removeAttr('onclick');
						return false;
					}
				});
			}
	}

	function resetWord(obj,qsn_id){ console.log("reset single wrd");
		var arabic = /[\u0600-\u06FF]/;
		var ltr=$(obj).text(); console.log("resetting letter"+qsn_id);
		var index=qsn_id;
		var xi;
		var yi;
		xi = $(obj).offset().left;
		yi = $(obj).offset().top;
		var inpt = $('#'+index+'_WordGame');
		var chindx = $('.w1Ltr.txtin .usr_ans_'+qsn_id+', .w1Ltr.whitespace .usr_ans_'+qsn_id).index($(obj));
		console.log(chindx);
		if(arabic.test(ltr)){
			console.log("arabic");
			chindx=$('.w1Ltr.txtin .usr_ans_'+qsn_id+', .w1Ltr.whitespace .usr_ans_'+qsn_id).get().reverse().indexOf(obj);
		}
		console.log(chindx);
		inpt.val(inpt.val().slice(0, chindx) + inpt.val().slice(chindx + 1));
		inpt.val(inpt.val().trim());
		$(obj).parent().removeClass('txtin');
		if(ltr!=''){
			var x;
			var y;
			var theAnsTxt = ltr;
			//reset the tex
			$(obj).text('');
			$('.serial_'+index).find('.ltrBtn').each(function(){
				if($(this).find('span').text()==''){
					x=$(this).offset().left;
					y=$(this).offset().top;
					$(this).css('left', xi).css('top', yi);
					console.log("x:"+x+" Y:"+y);
					console.log("xi:"+xi+" Yi:"+yi);
					$(obj).animate({
						left:x,
						top: y
					},"slow");
					$(this).find('span').text(theAnsTxt);

					if(arabic.test(theAnsTxt)){
						identifier_lang=1;
					}else{
						identifier_lang=0;
					}
					$(this).attr("onclick","showAnsInBox(this,"+index+",'"+theAnsTxt+"',"+identifier_lang+")");
					return false;
				}
			});

		}
	}

	function resetAnsArea(index){
		var inpt = $('#'+index+'_WordGame');
		inpt.val('');
		$('.serial_'+index).find('.w1Ltr').each(function(){
			if($(this).find('span').text()!='' && $(this).hasClass('whitespace') == false){
				var theAnsTxt = $(this).find('span').text();
				//reset the tex
				$(this).removeClass('txtin');
				$(this).find('span').text('');
				$('.serial_'+index).find('.ltrBtn').each(function(){
					if($(this).find('span').text()==''){
						$(this).find('span').text(theAnsTxt);
						var arabic = /[\u0600-\u06FF]/;
						if(arabic.test(theAnsTxt)){
							identifier_lang=1;
						}else{
							identifier_lang=0;
						}
						$(this).attr("onclick","showAnsInBox(this,"+index+",'"+theAnsTxt+"',"+identifier_lang+")");
						return false;
					}
				});
			}
		});
	}

</script>
<script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
</script>
<script>
	function calibrate(id) {
      var canvas = window['canvas_'+id];
      var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
      var original_piece_coordintes = window['original_piece_coordintes_'+id];

      var scaleW = canvas.offsetWidth / window['original_size_'+id]['width'];
      //console.log(scaleW);
      for (const key in piecesOfPuzzle) {
        piecesOfPuzzle[key]['X'] = original_piece_coordintes[key]['X'] * scaleW;
        piecesOfPuzzle[key]['Y'] = original_piece_coordintes[key]['Y'] * scaleW;
        piecesOfPuzzle[key]['width'] = original_piece_coordintes[key]['width'] * scaleW;
        piecesOfPuzzle[key]['height'] = original_piece_coordintes[key]['height'] * scaleW;
      }

      window['canvas_'+id] = canvas;
      window['piecesOfPuzzle_'+id] = piecesOfPuzzle;
      window['original_piece_coordintes_'+id] = original_piece_coordintes;
    }

    function showCnvs(id) {
	  var elId = "ddgame_canvas_"+id;
      var canvas = window['canvas_'+id];
      var stage = window['stage_'+id];
      var layerback = window['layerback_'+id];
      var originalWidth = window['originalWidth_'+id];
      var originalHeight = window['originalHeight_'+id];

      canvas = document.getElementById(elId);

      originalWidth = canvas.offsetWidth;
      originalHeight = canvas.offsetHeight;

      stage = new Konva.Stage({
        container: elId,
        width: originalWidth,
        height: originalHeight,
      });
      console.log(stage);
      stage.add(layerback);

      var keysLayer = new Konva.Layer({
        x: 0,
        y: 0,
        id: "keysLayer",
      });
      stage.add(keysLayer);

      window.addEventListener('resize', function() {
        if($(window).width() != w_width || $(window).height() != w_height){
          fitStageIntoParentContainer(id);
        }
      });

      window['canvas_'+id] = canvas;
      window['stage_'+id] = stage;
      window['layerback_'+id] = layerback;
      window['originalWidth_'+id] = originalWidth;
      window['originalHeight_'+id] = originalHeight;
    }

    function getRelativePointerPosition(node) {
        var transform = node.getAbsoluteTransform().copy();
        // to detect relative position we need to invert transform
        transform.invert();

        // get pointer (say mouse or touch) position
        var pos = node.getStage().getPointerPosition();

        // now we can find relative point
        return transform.point(pos);
    }

  

    /*
    function move(current, direction) {
      document.getElementById("step_"+'1').style.height = '100%';
      document.getElementById("step_"+current).style.left = '-200%';
      document.getElementById("step_"+current).style.height = '0px';
      document.getElementById("step_"+'1').style.left = '0%';
    }
    */

    var ds = document.getElementById('drawingCnvs');
    var ctx = ds.getContext('2d');
    function getKey(piece, id) {

      var original_image = window['original_image_'+id];
      var canvas = window['canvas_'+id];
      var stage = window['stage_'+id];
      var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
      var original_piece_coordintes = window['original_piece_coordintes_'+id];

      if (piecesOfPuzzle[piece]['text'] != undefined && piecesOfPuzzle[piece]['text'] != '') {
        var cnvs = updateTxt(piecesOfPuzzle[piece]['text'], piece, id);
        cnvs.toBlob(function(blob) {
          addKey(piecesOfPuzzle[piece], piece, blob, id);
        })

      } else {
        if (piecesOfPuzzle[piece]['image'] != undefined && piecesOfPuzzle[piece]['image'] != '') {
          addKey(piecesOfPuzzle[piece], piece, piecesOfPuzzle[piece]['image'], id);

        } else {
          var scaleW = window['original_size_'+id]['width'] / original_image.naturalWidth;
          ctx.clearRect(0, 0, ds.width, ds.height);
          var x11 = original_piece_coordintes[piece]['X'] / scaleW;
          var x12 = original_piece_coordintes[piece]['width'] / scaleW;
          var y11 = original_piece_coordintes[piece]['Y'] / scaleW;
          var y12 = original_piece_coordintes[piece]['height'] / scaleW;
          ctx.drawImage(original_image, x11, y11, x12, y12, 0, 0, ds.width, ds.height);
          ds.toBlob(function(blob) {
            addKey(piecesOfPuzzle[piece], piece, blob, id);
          })
        }
      }
    }

    function addKey(piece, pieceName, blob, id) {
      var iter = window['iter_'+id];
      var checker = window['checker_'+id];
      var heights = window['heights_'+id];
      var stage = window['stage_'+id];
      var height = heights[iter] != undefined ? heights[iter] : heights[heights.length -1];
      var imageObj = new Image();
      var factor = checker %2;
      imageObj.crossOrigin = "Anonymous";
      imageObj.onload = function() {
        var pieceImg = new Konva.Image({
          x: stage.width()/2 * factor + 20,
          y: height,
          width: piece['width'],
          height: piece['height'],
          image: imageObj,
          id: pieceName,
          stroke: 'rgba(0,0,255,0.5)',
          strokeWidth: 4,
          draggable: true,
          dragBoundFunc: function (pos) {
            setTimeout(function() {
				document.getElementById('puzzle_ans_'+id).value = get_ans_poses(id);
			});
            if (Math.abs(pos.x - piece['X']) <= 30 && Math.abs(pos.y - piece['Y']) <= 30) {
              // snapping feature
                //return {
                //  x: piece['X'],
                //  y: piece['Y'],
                //}
              return pos;
            } else {
              return pos;
            }
          },
        });

        URL.revokeObjectURL(this.src);

        pieceImg.on('mouseover', function () {
          document.body.style.cursor = 'pointer';
        });
        pieceImg.on('mouseout', function () {
          document.body.style.cursor = 'default';
        });
        pieceImg.on('dragmove', function () {
        });

        if (piece['hide_origin']) {
          var rect1 = new Konva.Rect({
            x: 0,
            y: 0,
            width: piece['width'],
            height: piece['height'],
            fill: 'white',
          });

          stage.findOne('#keysLayer').add(rect1);
          rect1.moveToBottom();
          rect1.absolutePosition({
            x: piece['X'],
            y: piece['Y'],
          });

          stage.findOne('#keysLayer').draw();
        }
        stage.findOne('#keysLayer').add(pieceImg);
        stage.findOne('#keysLayer').moveToTop();
        stage.findOne('#keysLayer').draw();
      }
      imageObj.src = typeof blob == "string" ? blob : URL.createObjectURL(blob);//URL.createObjectURL(blob) ;
      if ( checker % 2 == 1) {
        iter += 1;
      }
      checker += 1;
      window['iter_'+id] = iter;
      window['checker_'+id] = checker;
      window['heights_'+id] = heights;
      window['stage_'+id] = stage;
    }

    function updateTxt(txt, piece, id) {
      var original_image = window['original_image_'+id];
      var original_piece_coordintes = window['original_piece_coordintes_'+id];
      var scalen = window['original_size_'+id]['width'] / original_image.naturalWidth;
      var width = original_piece_coordintes[piece]['width'] / scalen;
      var height = original_piece_coordintes[piece]['height'] / scalen;
      width = width < 200 ? 200 : width;
      height = height < 200 ? 200 : height;
      var caption = new Konva.Text({
        x: 0,
        y: 0,
        text: txt,
        fontSize: 28,
        fontFamily: 'Calibri',
        fill: '#555',
        width: width,
        height: height,
        padding: 10,
        align: 'center',
        verticalAlign: 'middle',
      });
      var cnvs = caption.toCanvas();
      return cnvs;
    }

    function get_ans_poses(id) {
      var data = {};
      var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
      var stage = window['stage_'+id];
	  var scale = window['canvas_'+id].offsetWidth / window['original_size_'+id]['width'];
      for (const piece in piecesOfPuzzle) {
        var pieceInPuzzle = stage.findOne("#"+piece) || {absolutePosition: function() { return 'error in piece'}};
		data[piece] = {};
		data[piece]['X'] = pieceInPuzzle.absolutePosition().x || 'NaN';
		data[piece]['Y'] = pieceInPuzzle.absolutePosition().y || 'NaN' ;
		data[piece]['scale'] = scale;
      }
      return JSON.stringify(data);
    }

    @if(isset($ans))
	  function validate_ddqsn(id) {
        var correctBag = [];
        var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
        var stage = window['stage_'+id];

        for (const piece in piecesOfPuzzle) {
          var pieceInPuzzle = stage.findOne("#"+piece);
          //console.log( (piecesOfPuzzle[piece]['X'] - 4) < pieceInPuzzle.absolutePosition().x < (piecesOfPuzzle[piece]['X'] + 4) );
          if ( (piecesOfPuzzle[piece]['X'] - 20) <= pieceInPuzzle.absolutePosition().x && pieceInPuzzle.absolutePosition().x <= (piecesOfPuzzle[piece]['X'] + 20) && (piecesOfPuzzle[piece]['Y'] - 20) <= pieceInPuzzle.absolutePosition().y && pieceInPuzzle.absolutePosition().y <= (piecesOfPuzzle[piece]['Y'] + 20)) {
              correctBag.push(piece);
              //var pieceInPuzzle = stage.findOne("#"+piece);
              pieceInPuzzle.stroke('rgba(0,255,0,0.5)');
              //stage.findOne("#keysLayer").draw();
          } else {
              //var pieceInPuzzle = stage.findOne("#"+piece);
              pieceInPuzzle.stroke('rgba(255,0,0,0.5)');
          }
        }
        stage.findOne("#keysLayer").draw();
        window['piecesOfPuzzle_'+id] = piecesOfPuzzle;
        window['stage_'+id] = stage;
        //console.log(correctBag);
        if (Object.keys(piecesOfPuzzle).length == correctBag.length) {
          return true;
          /*
          Swal.fire({
            icon: 'success',
            title: 'You Nailed it',
            showConfirmButton: true,
          })
          */
        } else {
          return false;
          //stage.draw()

          /*
          Swal.fire({
            icon: 'error',
            title: 'You have some wrong answers. Check back the puzzle to know the misplaced parts',
            showConfirmButton: true,
          })
          */
        }

      }

      function solve_ddqsn(id) {
        var correctBag = [];
        var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
        var stage = window['stage_'+id];

        for (const piece in piecesOfPuzzle) {
          var pieceInPuzzle = stage.findOne("#"+piece);
          if ( (piecesOfPuzzle[piece]['X'] - 20) <= pieceInPuzzle.absolutePosition().x && pieceInPuzzle.absolutePosition().x <= (piecesOfPuzzle[piece]['X'] + 20) && (piecesOfPuzzle[piece]['Y'] - 20) <= pieceInPuzzle.absolutePosition().y && pieceInPuzzle.absolutePosition().y <= (piecesOfPuzzle[piece]['Y'] + 20)) {

          } else {
            pieceInPuzzle.absolutePosition({
              x: piecesOfPuzzle[piece]['X'],
              y: piecesOfPuzzle[piece]['Y']
            });
          }
          pieceInPuzzle.stroke('rgba(0,255,0,0.5)');
        }
        stage.findOne("#keysLayer").draw();
        window['piecesOfPuzzle_'+id] = piecesOfPuzzle;
        window['stage_'+id] = stage;
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-right',
          showConfirmButton: false,
          timer: 4000
        });
        Toast.fire({
          icon: 'success',
          title: 'Puzzle Solved'
        });
      }

      function restore_stored_piece(id, key, name) {
        var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
        var stage = window['stage_'+id];
        var pieceInPuzzle = stage.findOne("#" + name);
        pieceInPuzzle.absolutePosition(key);
        window['stage_'+id] = stage;
      }
    @endif

	var w_width = $(window).width()
    var w_height = $(window).height();
    function fitStageIntoParentContainer(id) {
      var elId = 'ddgame_adjustSpinner_'+id;
      document.getElementById(elId).style.visibility = "visible";
      setTimeout( function () {
        var container = window['canvas_'+id];
        var stage = window['stage_'+id];
        var originalWidth = window['originalWidth_'+id];

        // now we need to fit stage into parent
        var containerWidth = container.offsetWidth;
        // to do this we need to scale the stage
        var scale = containerWidth / originalWidth;
        stage.width(originalWidth * scale);
        stage.height(window['originalHeight_'+id] * scale);
        stage.scale({ x: scale, y: scale });
        calibrate(id);
        stage.draw();
        container.style.height = stage.height() + "px";
        document.getElementById(elId).style.visibility = "hidden";
        window['canvas_'+id] = container;
        window['stage_'+id] = stage;
        window['originalWidth_'+id] = originalWidth;
      }, 1000)

    }
    /*
    function step(direction) {
      var relaxedeles = document.getElementsByClassName("relaxed");
      var hiddeneles;
      var newCls = "pushed";
      if (direction == 1) {
        hiddeneles = document.getElementsByClassName("pushed");
        newCls = "pushed";
      } else {
        hiddeneles = document.getElementsByClassName("pulled");
        newCls = "pulled";
      }

      for (let i = 0; i < relaxedeles.length; i++) {
        relaxedeles[i].style.marginLeft = "0%";
        setTimeout(() => {
          relaxedeles[i].style.marginLeft = "-200%";
          relaxedeles[i].className = relaxedeles[i].className.replace('relaxed', 'pulled');
        }, 300);
      }

      for (let i = 0; i < hiddeneles.length; i++) {
        hiddeneles[i].style.marginLeft = "200%";
        setTimeout(() => {
          hiddeneles[i].style.marginLeft = "0%";
          hiddeneles[i].className = hiddeneles[i].className.replace(newCls, 'relaxed');
        }, 310);
      }
    }
    */
</script>
@endsection
