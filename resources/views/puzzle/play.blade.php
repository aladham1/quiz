
<div class="row justify-content-center">
  <div class="col-md-12 overlay-wrapper d-flex justify-content-center" style="margin-bottom: 4rem;">
    <input type="hidden" name='{{ $id }}_Puzzle' id='puzzle_ans_{{ $i }}' @if (isset($ans)) value="" @endif>
    <div id="ddgame_canvas_{{$i}}" style="width: calc( 100% - 20px ); height:100%; min-height:320px; overflow: visible; margin-left:auto; margin-right: auto;"></div>
    <div id="ddgame_adjustSpinner_{{$i}}" class="overlay flex-column" style="visibility: hidden; font-size: 5rem">
      <i class="fas fa-2x fa-sync-alt fa-spin"></i>
      <p class="mt-2">Adjusting...</p>
    </div>
  </div>
  @if(isset($ans) && $ans)
    <div class="col-md-12 mb-2 d-flex justify-content-center">
      <button class="btn btn-primary border-0 bg-pink btn-lg" type="button" role="button" style="position: fixed; bottom: 7.5rem;" onclick="solve_ddqsn({{ $i }})">Validate Answers</button>
    </div>
  @endif
</div>
<!-- /.row -->
 
<script>
  var ddinterval_{{$i}} = setInterval( function () {
    if ($('.index').text() == '{{$i}}' || document.getElementsByClassName('qsn_{{$i}}')[0].style.display != 'none' ) {
      clearInterval(window['ddinterval_'+{{$i}}]);
      showCnvs({{$i}});
      @if(isset($ans))
        $('#puzzle_ans_'+{{$i}}).val('{!! $student_answer !!}');
      @endif
      addImg({{$i}}, '{{ $puzzle }}');
    }
  }, 10);
  var canvas_{{$i}}, originalWidth_{{$i}}, originalHeight_{{$i}}, stage_{{$i}};
  
  var checker_{{$i}} = 0;
  var iter_{{$i}}=0;
  var heights_{{$i}} = [];
  var original_image_{{$i}};
  var piecesOfPuzzle_{{$i}} = JSON.parse('{!! $pieces !!}');
  var original_size_{{$i}} = piecesOfPuzzle_{{$i}}['piece0']['original_size'];
  //delete piecesOfPuzzle_{{$i}}.original_size_{{$i}};
  var original_piece_coordintes_{{$i}} = JSON.parse('{!! $pieces !!}');
  //delete original_piece_coordintes_{{$i}}.original_size_{{$i}};
  for (const key in original_piece_coordintes_{{$i}}) {
    original_piece_coordintes_{{$i}}[key]['X'] = original_piece_coordintes_{{$i}}[key]['X'] / original_piece_coordintes_{{$i}}[key]['scale'];
    original_piece_coordintes_{{$i}}[key]['Y'] = original_piece_coordintes_{{$i}}[key]['Y'] / original_piece_coordintes_{{$i}}[key]['scale'];
    original_piece_coordintes_{{$i}}[key]['width'] = original_piece_coordintes_{{$i}}[key]['width'] / original_piece_coordintes_{{$i}}[key]['scale'];
    original_piece_coordintes_{{$i}}[key]['height'] = original_piece_coordintes_{{$i}}[key]['height'] / original_piece_coordintes_{{$i}}[key]['scale'];
  }
  var layerback_{{$i}} = new Konva.Layer({
    id: "background",
  });
  
</script>