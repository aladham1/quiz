
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
    function addImg(id, img_link) {

        var original_image = window['original_image_'+id];
        var canvas = window['canvas_'+id];
        var stage = window['stage_'+id];
        var layerback = window['layerback_'+id];
        var originalWidth = window['originalWidth_'+id];
        var originalHeight = window['originalHeight_'+id];
        var heights = window['heights_'+id];
        var piecesOfPuzzle = window['piecesOfPuzzle_'+id];
        var original_piece_coordintes = window['original_piece_coordintes_'+id];

        var puzzleImg = new Image();
        puzzleImg.crossOrigin = "Anonymous";
        puzzleImg.src = img_link;
        puzzleImg.onload = function () {
            original_image = puzzleImg.cloneNode();
            var imgCalcHeight = puzzleImg.height * originalWidth / puzzleImg.width;
            puzzleImg.width = originalWidth;
            puzzleImg.height = imgCalcHeight;
            var scaleW = canvas.offsetWidth / window['original_size_'+id]['width'];
            fitStageIntoParentContainer(id);
            calibrate(id);
            var oheights = [];
            var addition = 0;
            heights.push(puzzleImg.height + 10);

            for (const key in original_piece_coordintes) {
                if (piecesOfPuzzle[key]['piece'] !== '/') {
                    oheights.push(original_piece_coordintes[key]['height']);
                }
            }

            for (var i = 0; i < oheights.length; i += 2) {
                var w1 = oheights[i] * scaleW;
                var y = i + 1;
                var w2 = oheights[y] == undefined ? w1 : oheights[y];
                w2 = w2 * scaleW;
                heights.push(Math.max(w1,w2) + 15 + heights[0]);
                addition += Math.max(w1,w2) + 30;
            }
            var cnvsimg = new Konva.Image({
                x: 0,
                y: 0,
                image: puzzleImg,
                id: "puzzleImg",
            });
            originalHeight = parseInt(puzzleImg.height) + addition;
            canvas.style.height = originalHeight + "px";

            layerback.add(cnvsimg);
            layerback.draw();
            stage.draw();

            window['canvas_'+id] = canvas;
            window['stage_'+id] = stage;
            window['layerback_'+id] = layerback;
            window['originalWidth_'+id] = originalWidth;
            window['originalHeight_'+id] = originalHeight;
            window['heights_'+id] = heights;
            window['piecesOfPuzzle_'+id] = piecesOfPuzzle;
            window['original_piece_coordintes_'+id] = original_piece_coordintes;
            window['original_image_'+id] = original_image;

            for (const piece in piecesOfPuzzle) {
                if (piecesOfPuzzle[piece]['piece'] !== '/') {
                    getKey(piece, id);
                }
            }
            @if(isset($ans))
            var stored_answers = JSON.parse($('#puzzle_ans_'+id).val());
            var imgs_added = setInterval(function () {
                var imgs_of_puzzle = window['stage_'+id].findOne('#keysLayer').find(node => {
                    return node.getType() === 'Shape' && node.fill() !== 'white';
                });
                if (imgs_of_puzzle.length >= Object.keys(piecesOfPuzzle).length) {
                    clearInterval(imgs_added);
                    for (const key in stored_answers) {
                        var x = (stored_answers[key]['X'] / stored_answers[key]['scale']) * scaleW;
                        var y = (stored_answers[key]['Y'] / stored_answers[key]['scale']) * scaleW;
                        restore_stored_piece(id, {'x': x, 'y': y}, key);
                    }
                    validate_ddqsn(id);
                }
            }, 10);
            @endif
        }
    }
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
    @endif
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
