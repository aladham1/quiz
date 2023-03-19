@php
  $max_count = env('MAX_COUNT', 4);
@endphp
<div class="row justify-content-center overflow-hidden" style="min-height: 100% !important">
  <div class="col-lg-12 overflow-hidden" style="background-color: #f4f4f4">
  <form id="newGameForm" action="{{route('games.new')}}" class="mb-2" role="form" method="POST" enctype="multipart/form-data" novalidate>
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif
    <div class="card justify-content-center trns" style="left: 0%; height:100%; box-shadow: none !important;background-color: #f4f4f4" id="step_0">

      <div class="card-body whtBx1 mx-0 mt-0 mb-5 overflow-hidden">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="form-group">
              <label for="name" class="form-label" style="color: #511285">Question Text:</label>
              <input type="text" class="form-control" placeholder="name" name="puzzle_name" id="puzzle_name">
            </div>
            <div class="form-group col-12 text-center">
              
              <div id="canvas-wrapper" class="col-md-12 w-100 overlay-wrapper" style="overflow: auto">
                <div id="puzzle_canvas" style="width: calc( 100% - 10px ); height:100%; max-width:480px; margin-left:auto; margin-right: auto;"></div>
                <div class="control-btns-div" style="display: none">
                  <div class="btn-group w-100" role="group">
                    <button class="btn btn-primary bg-indigo py-1" style="border-top-left-radius: 0px !important;" type="button" role="button" onclick="cancelSelection()">Cancel</button>
                    <button class="btn btn-primary py-1"style="border-top-right-radius: 0px !important;" type="button" role="button" onclick="minimizeChoice()">Confirm</button> 
                  </div>
                </div>
                <div id="adjustSpinner" class="overlay dark flex-column" style="visibility: hidden; none; font-size: 5rem">
                  <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                  <p class="mt-2">Adjusting...</p>
                </div>
              </div>

              <div class="photoSelection">
                <button class="btn btn-primary m-2 btn-lg" type="button" role="button" onclick="document.getElementById('puzzle').click()"><i class="fas fa-upload"></i> &nbsp; Upload</button>
                <input id="puzzle" name="puzzle" type="file" accept="image/*" hidden onchange="start_puzzle_creation();addImg(this.files[0]);">
              </div>
        
            </div>
          </div>
        </div>
      </div>

      <div  class="card-body pieceSelection p-0 m-0 overflow-hidden text-center d-none">
        <div class="card-header">
          <div class="text-center">
            <h5 class="text-center m-0">Click on area and drag to specify the region</h5>
            <h5 class="text-center m-0" style="color: red">Don't click done until all your chosen parts are fully visible in the preview</h5>
          </div>
          <div class="form-group col-12 text-center mb-0" style="font-size: 18px !important">
            <textarea name="keys" hidden id="keys"></textarea>
          </div>
        </div>
        <div class="card-body text-center px-0 py-2" style="overflow: auto">
          <canvas id="drawingCnvs" style="width: 0px; height: 0px;"></canvas>
         
          <div class="col-md-12 mt-4 text-left">
            @php
                $colors = ['indigo', 'maroon', 'lightblue', 'teal'];
            @endphp
            @for ($i = 0; $i < intval($max_count ?? 4); $i++)
              <div class="row justify-content-center">
              <div class="col-lg-7">
                <div id="{{'col_for_piece_'.$i}}" class="card justify-content-center">
                  <button role="button" type="button" class="btn bg-indigo show-when-piece-selected" style="position: absolute; bottom: 0; right: -1px; display: none; border-radius: 0px !important; border-top-left-radius: 5px !important;" onclick="deleteChoice('{{$i}}')"><i class="fas fa-trash-alt"></i></button>
                  <blockquote id="{{'piece_'.$i}}" class="quote-{{$colors[$i%intval($max_count)]}} px-3 py-0">
                      <div class="row justify-content-center">
                        <div class="col-lg-5 col-sm-5 text-center">
                          <h4 class="font-weight-bold"> Question {{$i + 1}} @if($i > 0) (optional) @endif</h4>
                            <i class="fas fa-image fa-5x placeholder" style="cursor: pointer" onclick="fullscreenChoice('{{$i}}')" aria-hidden="true"></i>
                            <div class="show-when-piece-selected" style="position: relative; display: none;">
                              <canvas class="preview" style="width:100%; display: none; border: 4px solid var(--pink); border-radius: 10px;"></canvas>
                              <button role="button" type="button" class="btn btn-lg bg-pink show-when-piece-selected" style="position: absolute; bottom: 0; left: 0; display: none;" onclick="fullscreenChoice('{{$i}}')"><i class="fas fa-edit"></i></button>
                              <input type="hidden" class="target_{{$i + 1}}">
                              <input type="hidden" class="target_{{$i + 1}}_imgdata">
                            </div>
                            <div class="row mx-0 my-2">
                              <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="whiteCvr{{$i}}" value="" onchange="rectCvr(this.checked, '{{'piece'.$i}}')"><label class="custom-control-label" for="whiteCvr{{$i}}">Hide</label>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-lg-6 text-center col-sm-6 ml-md-3 p-0" id="{{'a_col'.$i}}">
                          <div class="row mx-0 mb-3">
                            <div class="col-md-12 p-0">
                              <h5>Answer:</h5>
                            </div>
                          </div>
                          <div class="row mx-0 align-items-center justify-content-center w-100">
                            <div class="col-xs-5 mr-2 mb-2"><button type="button" role="button" style="font-family: initial; font-size: 1.3rem !important; font-weight: 900;" class="btn bg-indigo py-1 px-3" data-toggle="collapse" data-target="#collapsetxt{{$i}}" aria-expanded="false" aria-controls="collapsetxt{{$i}}">T</button></div>
                            <div class="col-xs-4 mb-2">
                              <button type="button" role="button" class="btn bg-indigo py-1 px-2" onclick="document.getElementById('coverImg{{$i}}').click()"><i class="fas fa-image" style="font-size: 2rem !important" aria-hidden="true"></i></button>
                              <input type="file" class="ddTgAnsImg_{{$i + 1}}" accept="image/*" hidden id="coverImg{{$i}}" onchange="updateImgfromFile(this.files[0], '{{'piece_'.$i}}');hideAnswerCol('{{$i}}')">
                            </div>
                            <div class="col-12"></div>
                            <div class="collapse" id="collapsetxt{{$i}}">
                              <div class="col-xs-12">
                                  <input type="text" id="txtAnswerInput{{$i}}" class="form-control mb-1" oninput="">
                                  <input type="hidden" class="ddTgAnsTxt_{{$i + 1}}">
                              </div>
                                <button type="button" role="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapsetxt{{$i}}" aria-expanded="false" aria-controls="collapsetxt{{$i}}" onclick="updateTxt(document.getElementById('txtAnswerInput{{$i}}').value, '{{'piece'.$i}}');hideAnswerCol('{{$i}}')">Done</button>
                            </div>
                          </div>
                        </div>
                      <div class="col-lg-5 text-center col-md-6 ml-md-3 p-0" style="display: none" id="{{'a_preview_col'.$i}}">
                          <div class="row justify-content-center mx-0 w-100">
                            <div class="col-md-12 p-0">
                              <h5>Answer:</h5>
                            </div>
                            <div class="col-md-12 p-0">
                              <img class="a_preview" style="width:100%; max-width: 180px; border: 4px solid var(--indigo); border-radius: 10px;">
                              <button role="button" type="button" class="btn btn-primary bg-pink show-when-piece-selected ml-2" onclick="showAnswerCol('{{$i}}')"><i class="fas fa-minus-circle"></i></button>
                            </div>
                          </div>
                      </div>
                    </div>
                    </blockquote>
                </div>
              </div>
              </div>
            @endfor
            @csrf
          </div>
        </div>
      </div>

    </div>

</form>
  </div>
</div>
<!-- /.row -->

