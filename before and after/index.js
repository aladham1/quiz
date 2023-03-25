
base_url = $('.base_url').val();
function getId(url){
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = url.match(regExp);

    if (match && match[2].length == 11) {
        return match[2];
    } else {
        return 'error';
    }
}

function getDuration(src, cb) {
    var audio = new Audio();
    $(audio).on("loadedmetadata", function(){
        cb(audio.duration);
    });
    audio.src = src;
}

function saveDataPopIntro(type,index=''){

    if(type=='title'){
        var title =$('.intro_title').val(); console.log(title);
        localStorage.setItem("intro_title",title);

        //popp1close();
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log("xm id="+d_exam_id);
        if(d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            if(title==''){
                swal("Please fill the input area");
            }else{
                showLoader();
                $.post("saveExamIntroDraft",{type:type,value:title,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                    if(rs!=0){
                        var obj = $.parseJSON(rs);
                        rs = obj.id;
                        var html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                        '<span class="drgEdt" onclick="editPopIntro(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="images/text.svg"/>'+
                                        '<span>Title</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" >Uparrow</span>'+
                                        '<span class="drgDwar" >Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgT2 center title_data">'+title+'</div>'+
                                '</div>'+
                            '</div>';
                        $('.quest_subject').append(html);

                        $('.intro_title').val('');
                        $('#mask').click();
                    }
                })
            }
        }


    }else if(type=='video'){
        var url = $('.intro_video').val();
        localStorage.setItem("intro_video",url);

        urls = getId(url);

        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            if(url==''){
                $('.canBtn').click();
                //swal("Error","Please fill the input area","error");
            }else{ showLoader();
                $.post("saveExamIntroDraft",{type:type,value:url,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                    if(rs!=0){
                        var obj = $.parseJSON(rs);
                        rs = obj.id;

                        html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                        '<span class="drgEdt" onclick="editPopIntro(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="images/video.svg"/>'+
                                        '<span>Video</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" >Uparrow</span>'+
                                        '<span class="drgDwar" >Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgvdo center video_data">'+
                                        '<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';

                            $('.quest_subject').append(html);

                            $('.intro_video').val('');
                            $('#mask').click();
                    }
                });
            }
        }
    }else if(type=='audio'){
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            $('.canBtn').click();
            //swal("Error","Please fill the First page","error");
        }else{
            var audio_name = localStorage.getItem("intro_draft_audio_name");
            if(audio_name==null){
                swal("Error","Please fill the input area","error");
            }else{
                showLoader();
                var form_data = new FormData();
                form_data.append("intro_audio_draft",audio_name);
                form_data.append("type",type);
                form_data.append("draft_exam_id",d_exam_id);
                $.ajax({
                    url: "saveExamIntroDraft",
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(rs){ hideLoader();
                        var obj = $.parseJSON(rs);
                        rs = obj.id;
                        $('#recordingsList').html('');
                            //form_data = undefined;
                            showLoader();
                            var html = ' <li data-post-id="'+rs+'">'+
                            '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                            '<div class="drgHdr">'+
                                                '<div class="drgAction">'+
                                                    '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                                    '<!--<span class="drgEdt">EDIT</span>-->'+
                                                '</div>'+
                                                '<div class="drgT1">'+
                                                    '<img src="images/audio.svg"/>'+
                                                    '<span>Audio</span>'+
                                                '</div>'+
                                                '<div class="drgAction drgAction2">'+
                                                    '<span class="drgUpar" >Uparrow</span>'+
                                                    '<span class="drgDwar" >Downarrow</span>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="drgWhte">'+
                                                '<div class="drgAudio center">'+
                                                '<audio controls>'+
                                                '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                                                '</audio>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div></li>';

                                        setTimeout(function(){
                                            hideLoader();
                                            $('.quest_subject').append(html);
                                        },1000);

                                        $('#mask').click();
                                        localStorage.removeItem("intro_draft_audio_name");
                    }
                });
            }
        }
    }else if(type=='image'){
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            showLoader();
            var form_data = new FormData();
            form_data.append("intro_image_raft",$('.introImg').prop("files")[0]);
            form_data.append("type",type);
            form_data.append("draft_exam_id",d_exam_id);
            $.ajax({
                url: "saveExamIntroDraft",
                type: "POST",
                data: form_data,
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData:false,
                success: function(rs){  hideLoader();
                    var obj = $.parseJSON(rs);
                    rs = obj.id;

                    var html = ' <li data-post-id="'+rs+'">'+
                    '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+

                                    '<div class="drgHdr">'+
                                        '<div class="drgAction">'+
                                            '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                            '<!--<span class="drgEdt">EDIT</span>-->'+
                                        '</div>'+
                                        '<div class="drgT1">'+
                                            '<img src="images/img.svg"/>'+
                                            '<span>Image</span>'+
                                        '</div>'+
                                        '<div class="drgAction drgAction2">'+
                                            '<span class="drgUpar" >Uparrow</span>'+
                                            '<span class="drgDwar" >Downarrow</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="drgWhte">'+
                                        '<div class="drgimg center">'+
                                            '<img src="api/uploads/'+obj.data+'"/>'+
                                        '</div>'+
                                    '</div>'+
                                '</div></li>';
                        $('.quest_subject').append(html);
                            $('#mask').click();
                }
            });
        }

    }else if(type=='paragraph'){
        //var content = $('.intro_paragraph').val();
        var content = myEditor.getData(); console.log(content);
        $('.intro_paragraph').val('');
        $('#mask').click();
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            if(content==''){
                swal("Error","Please fill the input area","error");
            }else{
                showLoader();
                $.post("saveExamIntroDraft",{type:type,value:content,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                    var obj = $.parseJSON(rs);
                    rs = obj.id;

                    var html = ' <li data-post-id="'+rs+'">'+
                                '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                    '<div class="drgHdr">'+
                                        '<div class="drgAction">'+
                                            '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                            '<span class="drgEdt" onclick="editPopIntro(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                        '</div>'+
                                        '<div class="drgT1">'+
                                            '<img src="images/note.svg"/>'+
                                            '<span>Paragraph</span>'+
                                        '</div>'+
                                        '<div class="drgAction drgAction2">'+
                                            '<span class="drgUpar" >Uparrow</span>'+
                                            '<span class="drgDwar" >Downarrow</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="drgWhte">'+
                                        '<div class="drgtxtp paragraph_data">'+
                                            obj.data+
                                        '</div>'+
                                    '</div>'+
                                '</div></li>';
                            $('.quest_subject').append(html);
                            $('#mask').click();
                })
            }
        }
    }else if(type=='table'){
        var table_data = $('.dynamicTable2').html(); console.log("table data",$('.dynamicTable2').length);
        $('.dynamicTable').html('');
        $('.dynamicTable2').html('');
        $('#mask').click();
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            showLoader();
            $.post("saveExamIntroDraft",{type:type,value:table_data,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                var obj = $.parseJSON(rs);
                rs = obj.id;

                var html = ' <li data-post-id="'+rs+'">'+
                            '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                        '<!--<span class="drgEdt" onclick="editPopIntro(&#039;'+type+'&#039;,'+rs+')">EDIT</span>-->'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="images/table.svg"/>'+
                                        '<span>Table</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" >Uparrow</span>'+
                                        '<span class="drgDwar" >Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgvdo center">'+
                                        obj.data+
                                    '</div>'+
                                '</div>'+
                            '</div></li>';
                        $('.quest_subject').append(html);
                        $('#mask').click();
            })
        }

    }else if(type=='file'){
        var file = $('.introFile').prop("files")[0];
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        var form_data = new FormData();
        form_data.append("file",file);
        form_data.append("type",type);
        form_data.append("draft_exam_id",d_exam_id);
        //form_data.append()
        showLoader();
            $.ajax({
               // url: base_url+"uploadTmpIntroFile",
               url: base_url+"saveExamIntroDraft",
                type: "POST",
                data: form_data,
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData:false,
                success: function(rs){ hideLoader();
                    if(rs!=0){
                        var obj = $.parseJSON(rs);
                        rs = obj.id;
                        var html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+

                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="images/attach_white.svg"/>'+
                                        '<span>File</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" >Uparrow</span>'+
                                        '<span class="drgDwar" >Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgT2 center title_data">'+obj.show_title+'</div>'+
                                '</div>'+
                            '</div>';
                        $('.quest_subject').append(html);
                        $('#mask').click();
                    }else{
                        iqwerty.toast.toast('Upload Small Size than 3Mb');
                        $('#mask').click();
                    }
                }
            });
    }else if(type=='quest_wg_video'){

        var url = $('.intro_video').val();
        localStorage.setItem("intro_video",url);

        if(url==''){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{
            urls = getId(url);


                $('.quest_video').val(url);

                $('.wgvdoTag').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
                $('.wgVdoBx').slideDown();
                $('.saveDataPopIntro').attr("onclick","saveDataPopIntro('video')");

                $('.intro_video').val('');
                $('#mask2').click();

                // if video choose make blank other two
                $('.quest_audio').val(''); $('.wgaudTag').html(''); $('.wgAudBx').slideUp();
                $('.quest_image').val(''); $('.wg_img_list').html(''); $('.wgImgBx').slideUp();
        }

    }else if(type=='quest_wg_audio'){

        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
           // swal("Error","Please fill the input area","error");
        }else{

                $('.quest_audio').val(audio_name);
                var html ='<audio controls>'+
                            '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                        '</audio>';
                setTimeout(function(){
                    $('.wgaudTag').html(html);
                },1000);

                $('.wgAudBx').slideDown();
                $('#mask2').click();
                $('#recordingsList').html('');

                // if audio choose make blank other two
                $('.quest_video').val(''); $('.wgvdoTag').html(''); $('.wgVdoBx').slideUp();
                $('.quest_image').val(''); $('.wg_img_list').html(''); $('.wgImgBx').slideUp();
                localStorage.removeItem("intro_draft_audio_name");
        }

    }else if(type=='quest_mc_video'){

        var url = $('.intro_video').val();
        localStorage.setItem("intro_video",url);


        if(url==''){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{
                urls = getId(url);

                $('.quest_video').val(url);

                $('.mcvdoTag').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
                $('.mcVdoBx').slideDown();
                $('.saveDataPopIntro').attr("onclick","saveDataPopIntro('video')");

                // if video choose make blank other two
                $('.quest_audio').val(''); $('.mcaudTag').html(''); $('.mcAudBx').slideUp();
                $('.quest_image').val(''); $('.mc_img_list').html(''); $('.mcImgBx').slideUp();

                $('.intro_video').val('');
                $('#mask2').click();

        }
    }else if(type=='quest_mc_audio'){

        var audio_name = localStorage.getItem("intro_draft_audio_name"); console.log("qst mc audio blank",audio_name);
        if(audio_name==null){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{ showLoader();
                $('.quest_audio').val(audio_name);
                var html ='<audio controls>'+
                            '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                        '</audio>';
                        setTimeout(function(){
                            hideLoader();
                            $('.mcaudTag').html(html);
                        },1000);

                $('.mcAudBx').slideDown();
                $('#mask2').click();
                $('#recordingsList').html('');

                // if audio choose make blank other two
                $('.quest_video').val(''); $('.mcvdoTag').html(''); $('.mcVdoBx').slideUp();
                $('.quest_image').val(''); $('.mc_img_list').html(''); $('.mcImgBx').slideUp();
                localStorage.removeItem("intro_draft_audio_name");
        }

    }else if(type=='quest_qo_audio'){ // question options audio

        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{ showLoader();
            $('.quest_voice_option_'+index).val(audio_name);

            var html ='<audio controls>'+
                        '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                    '</audio>';
                    setTimeout(function(){
                        hideLoader();
                        $('.ansAud_'+index).html(html);
                    },1000);

            $('.ansAud_'+index).fadeIn();
            $('#mask2').click();
            $('#recordingsList').html('');
        }

    }else if(type=='quest_pr_video'){

        var url = $('.intro_video').val();
        if(url==''){
            $('.canBtn').click();
           // swal("Error","Please fill the input area","error");
        }else{
            localStorage.setItem("intro_video",url);
            $('.intro_video').val('');
            $('#mask2').click();
            urls = getId(url);

            $('.quest_video').val(url);

            $('.prvdoTag').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
            $('.prVdoBx').slideDown();
            $('.saveDataPopIntro').attr("onclick","saveDataPopIntro('video')");

            // if video choose make blank other two
            $('.quest_audio').val(''); $('.praudTag').html(''); $('.prAudBx').slideUp();
            $('.quest_image').val(''); $('.pr_img_list').html(''); $('.prImgBx').slideUp();
        }

    }else if(type=='quest_pr_audio'){

        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{ showLoader();
            $('.quest_audio').val(audio_name);
            var html ='<audio controls>'+
                        '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                    '</audio>';
                setTimeout(function(){
                    hideLoader();
                    $('.praudTag').html(html);
                },1000);

            $('.prAudBx').slideDown();
            $('#mask2').click();
            $('#recordingsList').html('');

            // if audio choose make blank other two
            $('.quest_video').val(''); $('.prvdoTag').html(''); $('.prVdoBx').slideUp();
            $('.quest_image').val(''); $('.pr_img_list').html(''); $('.prImgBx').slideUp();
            localStorage.removeItem("intro_draft_audio_name");
        }
    }else if(type=='projects_video'){
        var url = $('.intro_video').val();
        if(url==''){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{
            $('.psubmit_type').val('video');
            $('.psubmit_value').val(url);
            urls = getId(url);
            $('#mask2').click();
            $('.project_data').css("width","100%");
            $('.project_data').css("height","155px");
            $('.project_data').css("text-align","center");
            $('.project_data').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
            $('.project_data').slideDown();
        }
    }else if(type=='projects_audio'){
        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
           // swal("Error","Please fill the input area","error");
        }else{ showLoader();
            $('.psubmit_type').val('audio');
            $('.psubmit_value').val(audio_name);

            var html ='<audio controls>'+
                            '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                        '</audio>';
            setTimeout(function(){
                hideLoader();
                $('.project_data').html(html);
            },1000);

            $('.project_data').css("width","100%");
            $('.project_data').css("height","54px");
            $('.project_data').css("text-align","center");
            $('.project_data').slideDown();
            $('#mask2').click();
        }
    }else if(type=='order'){
        var url = $('.order_url').val();
        var d_exam_id = localStorage.getItem("draft_exam_id");

        if(url!=''){
            $.post("saveExamIntroDraft",{type:type,value:url,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                if(rs!=0){
                    var obj = $.parseJSON(rs);
                    rs = obj.id;

                    var html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                        '<span class="drgEdt" onclick="editPopIntro(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="images/order-white.svg"/>'+
                                        '<span>Order Button</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" >Uparrow</span>'+
                                        '<span class="drgDwar" >Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgT2 center title_data">'+url+'</div>'+
                                '</div>'+
                            '</div>';
                        $('.quest_subject').append(html);

                        $('.order_url').val('');
                        $('#mask').click();
                }
            })
        }else{
            $('#mask').click();
        }
    }
}

function saveDataPopIntroEdit(type,index=''){

    if(type=='title'){
        var title =$('.intro_title').val(); console.log(title);
        localStorage.setItem("intro_title",title);

        //popp1close();
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log("xm id="+d_exam_id);
        if(d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            if(title==''){
                swal("Please fill the input area");
            }else{
                showLoader();
                $.post(base_url+"saveExamIntroDraftEdit",{type:type,value:title,draft_exam_id:d_exam_id},function(rs){  hideLoader();
                    if(rs!=0){
                        var obj = $.parseJSON(rs);
                        rs = obj.id;
                        var html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                        '<span class="drgEdt" onclick="editPopIntroEdit(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="'+base_url+'images/text.svg"/>'+
                                        '<span>Title</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                        '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgT2 center title_data">'+title+'</div>'+
                                '</div>'+
                            '</div>';
                        $('.quest_subject').append(html);

                        $('.intro_title').val('');
                        $('#mask').click();
                    }
                })
            }
        }


    }else if(type=='video'){
        var url = $('.intro_video').val();
        localStorage.setItem("intro_video",url);

        urls = getId(url);

        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            if(url==''){
                $('.canBtn').click();
                //swal("Error","Please fill the input area","error");
            }else{ showLoader();
                $.post(base_url+"saveExamIntroDraftEdit",{type:type,value:url,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                    if(rs!=0){
                        var obj = $.parseJSON(rs);
                        rs = obj.id;

                        html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                        '<span class="drgEdt" onclick="editPopIntroEdit(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="'+base_url+'images/video.svg"/>'+
                                        '<span>Video</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                        '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgvdo center video_data">'+
                                        '<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';

                            $('.quest_subject').append(html);

                            $('.intro_video').val('');
                            $('#mask').click();
                    }
                });
            }
        }
    }else if(type=='audio'){
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            $('.canBtn').click();
            //swal("Error","Please fill the First page","error");
        }else{
            var audio_name = localStorage.getItem("intro_draft_audio_name");
            if(audio_name==null){
                swal("Error","Please fill the input area","error");
            }else{
                showLoader();
                var form_data = new FormData();
                form_data.append("intro_audio_draft",audio_name);
                form_data.append("type",type);
                form_data.append("draft_exam_id",d_exam_id);
                $.ajax({
                    url: base_url+"saveExamIntroDraftEdit",
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(rs){ //hideLoader();
                        var obj = $.parseJSON(rs);
                        rs = obj.id;
                        $('#recordingsList').html('');
                            //form_data = undefined;

                            var html = ' <li data-post-id="'+rs+'">'+
                            '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                            '<div class="drgHdr">'+
                                                '<div class="drgAction">'+
                                                    '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                                    '<!--<span class="drgEdt">EDIT</span>-->'+
                                                '</div>'+
                                                '<div class="drgT1">'+
                                                    '<img src="'+base_url+'images/audio.svg"/>'+
                                                    '<span>Audio</span>'+
                                                '</div>'+
                                                '<div class="drgAction drgAction2">'+
                                                    '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                                    '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="drgWhte">'+
                                                '<div class="drgAudio center">'+
                                                '<audio controls>'+
                                                '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                                                '</audio>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div></li>';

                                        setTimeout(function(){
                                            hideLoader();
                                            $('.quest_subject').append(html);
                                        },1000);

                                        $('#mask').click();
                                        localStorage.removeItem("intro_draft_audio_name");
                    }
                });
            }
        }
    }else if(type=='image'){
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            showLoader();
            var form_data = new FormData();
            form_data.append("intro_image_raft",$('.introImg').prop("files")[0]);
            form_data.append("type",type);
            form_data.append("draft_exam_id",d_exam_id);
            $.ajax({
                url: base_url+"saveExamIntroDraftEdit",
                type: "POST",
                data: form_data,
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData:false,
                success: function(rs){ console.log(rs);  hideLoader();
                    var obj = $.parseJSON(rs);
                    rs = obj.id;

                    var html = ' <li data-post-id="'+rs+'">'+
                    '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+

                                    '<div class="drgHdr">'+
                                        '<div class="drgAction">'+
                                            '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                            '<!--<span class="drgEdt">EDIT</span>-->'+
                                        '</div>'+
                                        '<div class="drgT1">'+
                                            '<img src="'+base_url+'images/img.svg"/>'+
                                            '<span>Image</span>'+
                                        '</div>'+
                                        '<div class="drgAction drgAction2">'+
                                            '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                            '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="drgWhte">'+
                                        '<div class="drgimg center">'+
                                            '<img src="../../api/uploads/'+obj.data+'"/>'+
                                        '</div>'+
                                    '</div>'+
                                '</div></li>';
                        $('.quest_subject').append(html);
                        $('#mask').click();
                }
            });
        }

    }else if(type=='paragraph'){
        //var content = $('.intro_paragraph').val();
        var content = myEditor.getData(); console.log(content);
        $('.intro_paragraph').val('');
        $('#mask').click();
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            if(content==''){
                swal("Error","Please fill the input area","error");
            }else{
                showLoader();
                $.post(base_url+"saveExamIntroDraftEdit",{type:type,value:content,draft_exam_id:d_exam_id},function(rs){ hideLoader(); console.log("rs",rs);
                    var obj = $.parseJSON(rs);
                    rs = obj.id;

                    var html = ' <li data-post-id="'+rs+'">'+
                                '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                    '<div class="drgHdr">'+
                                        '<div class="drgAction">'+
                                            '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                            '<span class="drgEdt" onclick="editPopIntroEdit(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                        '</div>'+
                                        '<div class="drgT1">'+
                                            '<img src="'+base_url+'images/note.svg"/>'+
                                            '<span>Paragraph</span>'+
                                        '</div>'+
                                        '<div class="drgAction drgAction2">'+
                                            '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                            '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="drgWhte">'+
                                        '<div class="drgtxtp paragraph_data">'+
                                            obj.data+
                                        '</div>'+
                                    '</div>'+
                                '</div></li>';
                            $('.quest_subject').append(html);
                            $('#mask').click();
                })
            }
        }
    }else if(type=='table'){
        var table_data = $('.dynamicTable2').html();
        $('.dynamicTable').html('');
        $('#mask').click();
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        if( d_exam_id ==null){
            swal("Error","Please fill the First page","error");
        }else{
            showLoader();
            $.post(base_url+"saveExamIntroDraftEdit",{type:type,value:table_data,draft_exam_id:d_exam_id},function(rs){ hideLoader();
                var obj = $.parseJSON(rs);
                rs = obj.id;

                var html = ' <li data-post-id="'+rs+'">'+
                            '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                        '<span class="drgEdt" onclick="editPopIntroEdit(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="'+base_url+'images/table.svg"/>'+
                                        '<span>Table</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                        '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgvdo center">'+
                                        obj.data+
                                    '</div>'+
                                '</div>'+
                            '</div></li>';
                        $('.quest_subject').append(html);
                        $('#mask').click();
            })
        }

    }else if(type=='file'){
        var file = $('.introFile').prop("files")[0];
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        var form_data = new FormData();
        form_data.append("file",file);
        form_data.append("type",type);
        form_data.append("draft_exam_id",d_exam_id);
        //form_data.append()
        showLoader();
            $.ajax({
               // url: base_url+"uploadTmpIntroFile",
               url: base_url+"saveExamIntroDraftEdit",
                type: "POST",
                data: form_data,
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData:false,
                success: function(rs){console.log("rs=",rs); hideLoader();
                if(rs!=0){
                        var obj = $.parseJSON(rs);
                        rs = obj.id;
                        var html = ' <li data-post-id="'+rs+'">'+
                        '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                                '<div class="drgHdr">'+
                                    '<div class="drgAction">'+
                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+

                                    '</div>'+
                                    '<div class="drgT1">'+
                                        '<img src="'+base_url+'images/attach_white.svg"/>'+
                                        '<span>File</span>'+
                                    '</div>'+
                                    '<div class="drgAction drgAction2">'+
                                        '<span class="drgUpar" >Uparrow</span>'+
                                        '<span class="drgDwar" >Downarrow</span>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="drgWhte">'+
                                    '<div class="drgT2 center title_data">'+obj.show_title+'</div>'+
                                '</div>'+
                            '</div>';
                        $('.quest_subject').append(html);
                        $('#mask').click();
                    }else{
                        iqwerty.toast.toast('Upload Small Size than 3Mb');
                        $('#mask').click();
                    }
                }
            });
    }else if(type=='project_file'){
        var file = $('.projectFile').prop("files")[0];
        var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
        var form_data = new FormData();
        form_data.append("file",file);
        form_data.append("type",type);
        form_data.append("draft_exam_id",d_exam_id);

    }else if(type=='quest_wg_video'){

        var url = $('.intro_video').val();
        localStorage.setItem("intro_video",url);

        if(url==''){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{
            urls = getId(url);


                $('.quest_video').val(url);

                $('.wgvdoTag').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
                $('.wgVdoBx').slideDown();
                $('.saveDataPopIntro').attr("onclick","saveDataPopIntro('video')");

                $('.intro_video').val('');
                $('#mask2').click();

                // if video choose make blank other two
                $('.quest_audio').val(''); $('.wgaudTag').html(''); $('.wgAudBx').slideUp();
                $('.quest_image').val(''); $('.wg_img_list').html(''); $('.wgImgBx').slideUp();
        }

    }else if(type=='quest_wg_audio'){

        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
           // swal("Error","Please fill the input area","error");
        }else{
            showLoader();
                $('.quest_audio').val(audio_name);
                var html ='<audio controls>'+
                            '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                        '</audio>';
                setTimeout(function(){
                    $('.wgaudTag').html(html);
                    hideLoader();
                }, 1000);
                // $('.wgaudTag').html(html);
                $('.wgAudBx').slideDown();
                $('#mask2').click();
                $('#recordingsList').html('');

                // if audio choose make blank other two
                $('.quest_video').val(''); $('.wgvdoTag').html(''); $('.wgVdoBx').slideUp();
                $('.quest_image').val(''); $('.wg_img_list').html(''); $('.wgImgBx').slideUp();
                localStorage.removeItem("intro_draft_audio_name");
        }

    }else if(type=='quest_mc_video'){

        var url = $('.intro_video').val();
        localStorage.setItem("intro_video",url);


        if(url==''){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{
                urls = getId(url);

                $('.quest_video').val(url);

                $('.mcvdoTag').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
                $('.mcVdoBx').slideDown();
                $('.saveDataPopIntro').attr("onclick","saveDataPopIntro('video')");

                // if video choose make blank other two
                $('.quest_audio').val(''); $('.mcaudTag').html(''); $('.mcAudBx').slideUp();
                $('.quest_image').val(''); $('.mc_img_list').html(''); $('.mcImgBx').slideUp();

                $('.intro_video').val('');
                $('#mask2').click();

        }
    }else if(type=='quest_mc_audio'){

        var audio_name = localStorage.getItem("intro_draft_audio_name"); console.log("qst mc audio blank",audio_name);
        if(audio_name==null){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{showLoader();
                $('.quest_audio').val(audio_name);
                var html ='<audio controls>'+
                            '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                        '</audio>';

                setTimeout(function(){
                    $('.mcaudTag').html(html);
                    hideLoader();
                 }, 1000);

                $('.mcAudBx').slideDown();
                $('#mask2').click();
                $('#recordingsList').html('');

                // if audio choose make blank other two
                $('.quest_video').val(''); $('.mcvdoTag').html(''); $('.mcVdoBx').slideUp();
                $('.quest_image').val(''); $('.mc_img_list').html(''); $('.mcImgBx').slideUp();
                localStorage.removeItem("intro_draft_audio_name");
        }

    }else if(type=='quest_qo_audio'){ // question options audio

        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{
            showLoader();
            $('.quest_voice_option_'+index).val(audio_name);

            var html ='<audio controls>'+
                        '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                    '</audio>';
            setTimeout(function(){
                $('.ansAud_'+index).html(html);
                hideLoader();
            }, 1000);

            // $('.ansAud_'+index).html(html);
            $('.ansAud_'+index).fadeIn();
            $('#mask2').click();
            $('#recordingsList').html('');
        }

    }else if(type=='quest_pr_video'){

        var url = $('.intro_video').val();
        if(url==''){
            $('.canBtn').click();
           // swal("Error","Please fill the input area","error");
        }else{
            localStorage.setItem("intro_video",url);
            $('.intro_video').val('');
            $('#mask2').click();
            urls = getId(url);

            $('.quest_video').val(url);

            $('.prvdoTag').html('<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>');
            $('.prVdoBx').slideDown();
            $('.saveDataPopIntro').attr("onclick","saveDataPopIntro('video')");

            // if video choose make blank other two
            //$('.quest_audio').val(''); $('.praudTag').html(''); $('.prAudBx').slideUp();
            //$('.quest_image').val(''); $('.pr_img_list').html(''); $('.prImgBx').slideUp();
        }

    }else if(type=='quest_pr_audio'){

        var audio_name = localStorage.getItem("intro_draft_audio_name");
        if(audio_name==null){
            $('.canBtn').click();
            //swal("Error","Please fill the input area","error");
        }else{ showLoader();
            $('.quest_audio').val(audio_name);
            var html ='<audio controls>'+
                        '<source src="'+base_url+'api/uploads/'+audio_name+'.wav" type="audio/wav">'+
                    '</audio>';

            setTimeout(function(){
                $('.praudTag').html(html);
                hideLoader();
                }, 1000);
                // $('.praudTag').html(html);

            $('.prAudBx').slideDown();
            $('#mask2').click();
            $('#recordingsList').html('');

            hideLoader();
            // if audio choose make blank other two
            //$('.quest_video').val(''); $('.prvdoTag').html(''); $('.prVdoBx').slideUp();
            //$('.quest_image').val(''); $('.pr_img_list').html(''); $('.prImgBx').slideUp();
            localStorage.removeItem("intro_draft_audio_name");
        }
    }else if(type=='order'){
        var url = $('.order_url').val();
        var d_exam_id = localStorage.getItem("draft_exam_id");
        $.post(base_url+"saveExamIntroDraftEdit",{type:type,value:url,draft_exam_id:d_exam_id},function(rs){  hideLoader();
            if(rs!=0){
                var obj = $.parseJSON(rs);
                rs = obj.id;
                var html = ' <li data-post-id="'+rs+'">'+
                '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+
                        '<div class="drgHdr">'+
                            '<div class="drgAction">'+
                                '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                '<span class="drgEdt" onclick="editPopIntroEdit(&#039;'+type+'&#039;,'+rs+')">EDIT</span>'+
                            '</div>'+
                            '<div class="drgT1">'+
                                '<img src="'+base_url+'images/order-white.svg"/>'+
                                '<span>Order</span>'+
                            '</div>'+
                            '<div class="drgAction drgAction2">'+
                                '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="drgWhte">'+
                            '<div class="drgT2 center title_data">'+url+'</div>'+
                        '</div>'+
                    '</div>';
                $('.quest_subject').append(html);

                $('.order_url').val('');
                $('#mask').click();
            }
        })
    }
}

function fetchAlldata(){
    var exam_id = localStorage.getItem("draft_exam_id");
    $.post("getExamAllDetails",{exam_id:exam_id},function(rs){
        var obj = $.parseJSON(rs);
        console.log(obj);
        $('.xm_title').text(obj.exam_data.exam_title);
        if(obj.exam_data.exam_icon!='' && obj.exam_data.exam_icon!=null && obj.exam_data.exam_icon!='null' &&  obj.exam_data.exam_icon!='undefined'){
            $('.revImg').html('<img src="api/uploads/exam/'+obj.exam_data.exam_icon+'" style="max-height: 100%;">');
        }else{
            $('.revImg').html('<img src="images/placeholder.jpeg">');
        }

        var preq_txt ='';
        if(obj.exam_data.preq_type==0){
            preq_txt = 'No prerequisite';
        }else if(obj.exam_data.preq_type==1){
            preq_txt = 'Passing Exam - '+obj.exam_data.preq_value;
        }else if(obj.exam_data.preq_type==2){
            preq_txt = 'Group Star - '+obj.exam_data.preq_value;
        }
        $('.xm_preq').text(preq_txt);
        if(obj.exam_data.is_random==1){
            $('#rvchk1').prop("checked", true);
        }
        if(obj.exam_data.is_retake==1){
            $('#rvchk2').prop("checked", true);
        }
        if(obj.exam_data.is_chat==1){
            $('#rvchk4').prop("checked", true);
        }
        if(obj.exam_data.is_private==1){
            $('#rvchk5').prop("checked", true);
        }
        if(obj.exam_data.time_limit!=0){
            $('#rvchk3').prop("checked", true);
            $('.xm_time').text("Time limit ( "+obj.exam_data.time_limit+" minuts )");
        }

        var intro_html='';
        for(var i=0;i<obj.intro.length;i++){
            if(obj.intro[i].intro_type=='title'){
                intro_html +='<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="images/p_text.svg"></div>'+
                                    '<div class="subTxt2">'+
                                        obj.intro[i].intro_data+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].intro_type=='paragraph'){
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="images/p_note.svg"></div>'+
                                    '<div class="subTxt2">'+
                                    obj.intro[i].intro_data+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].intro_type=='image'){
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="images/p_img.svg"></div>'+
                                    '<div class="subTxt2">'+
                                        'Image'+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].intro_type=='video'){
                var url = getId(obj.intro[i].intro_data);
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="images/p_video.svg"></div>'+
                                    '<div class="subTxt2">'+
                                    'Video'+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].intro_type=='table'){
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="images/p_table.svg"></div>'+
                                    '<div class="subTxt2">'+
                                        'Table'+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].intro_type=='audio'){
                getDuration("api/uploads/"+obj.intro[i].intro_data, function(length) {
                    console.log('I got length ' + length);
                    var showTime='00:00';
                    if(length>60){
                        var minute = (length-60);

                        showTime = '01'+':'+minute;
                    }else{
                        if(parseInt(length).toString().length==1){
                            showTime = '00:0'+parseInt(length);
                        }else{
                            showTime = '00:'+parseInt(length);
                        }
                    }
                    intro_htmls = '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="images/p_audio.svg"></div>'+
                                    '<div class="subTxt2">'+
                                    showTime+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
                            $('.xm_subjects').append(intro_htmls);

                });

            }


        }

        $('.xm_subjects').html(intro_html);


        $('.xm_total_mcq').text(obj.mcq);
        $('.xm_total_word_game').text(obj.word_game);
        if(obj.project=='null' || obj.project==null){
            $('.xm_toatl_project').text(0);
        }else{
            $('.xm_toatl_project').text(obj.project);
        }
        $('.xm_total_dd').text(obj.dd);

        var rev_text = '';
        if(obj.exam_data.type==0){
            rev_text ='(Default) Do NOT show wrong answer';
        }else if(obj.exam_data.type==1){
            rev_text = 'Show only wrong questions at the end of the exam';
        }else if(obj.exam_data.type==2){
            rev_text ='Show wrong questions & Answer ar the end of the exam';
        }
        $('.xm_review').text(rev_text);
        $('.xm_percentage').text(obj.exam_data.pass_percentage+'%');
        var reward_mode = '';
        if(obj.exam_data.reward_mode==0){
            reward_mode = 'Single';
        }else{
            reward_mode = 'Coupon List';
        }
        $('.xm_reward_mode').val(reward_mode);
        var re_type='';
        if(obj.exam_data.reaward_type==0){
            re_type='Bluetooth';
        }else if(obj.exam_data.reaward_type==1){
            re_type ='Show Text Message';
        }else if(obj.exam_data.reaward_type==2){
            re_type='Show Image';
        }else if(obj.exam_data.reaward_type==3){
            re_type='Play Video';
        }else if(obj.exam_data.reaward_type==4){
            re_type='Certificate';
        }
        $('.xm_reward_type').text(re_type);
    })
}

function fetchAlldataEdit(){
    var exam_id = localStorage.getItem("draft_exam_id");
    $.post(base_url+"getExamAllDetailsEdit",{exam_id:exam_id},function(rs){
        var obj = $.parseJSON(rs);
        console.log(obj);
        $('.xm_title').text(obj.exam_data.exam_title); //console.log("xm icon",obj.exam_data.exam_icon);
        if(obj.exam_data.icon!='' && obj.exam_data.icon!=null && obj.exam_data.icon!='null' &&  obj.exam_data.icon!='undefined'){
            $('.revImg').html('<img src="../../api/uploads/exam/'+obj.exam_data.icon+'" style="max-height: 100%;">');
        }else{
            $('.revImg').html('<img src="'+base_url+'images/placeholder.jpeg">');
        }

        var preq_txt ='';
        if(obj.exam_data.preq_type==0){
            preq_txt = 'No prerequisite';
        }else if(obj.exam_data.preq_type==1){
            preq_txt = 'Passing Exam - '+obj.exam_data.preq_value;
        }else if(obj.exam_data.preq_type==2){
            preq_txt = 'Group Star - '+obj.exam_data.preq_value;
        }
        $('.xm_preq').text(preq_txt);
        if(obj.exam_data.is_order==0){
            $('#rvchk1').prop("checked", true);
        }else{
            $('#rvchk1').prop("unchecked", true);
        }
        if(obj.exam_data.exam_term==1){
            $('#rvchk2').prop("checked", true);
        }
        if(obj.exam_data.chat_on==1){
            $('#rvchk4').prop("checked", true);
        }
        if(obj.exam_data.access==1){
            $('#rvchk5').prop("checked", true);
        }
        if(obj.exam_data.time_limit!=0){
            $('#rvchk3').prop("checked", true);
            $('.xm_time').text("Time limit ( "+obj.exam_data.time_limit+" minuts )");
        }

        var intro_html='';
        for(var i=0;i<obj.intro.length;i++){
            if(obj.intro[i].type=='title'){
                intro_html +='<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="'+base_url+'images/p_text.svg"></div>'+
                                    '<div class="subTxt2">'+
                                        obj.intro[i].text+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].type=='paragraph'){
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="'+base_url+'images/p_note.svg"></div>'+
                                    '<div class="subTxt2">'+
                                    obj.intro[i].intro_paragraph+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].type=='image'){
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="'+base_url+'images/p_img.svg"></div>'+
                                    '<div class="subTxt2">'+
                                        'Image'+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].type=='video'){
                var url = getId(obj.intro[i].intro_data);
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="'+base_url+'images/p_video.svg"></div>'+
                                    '<div class="subTxt2">'+
                                    'Video'+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].type=='table'){
                intro_html += '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="'+base_url+'images/p_table.svg"></div>'+
                                    '<div class="subTxt2">'+
                                        'Table'+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
            }else if(obj.intro[i].intro_type=='audio'){
                getDuration("../../api/uploads/"+obj.intro[i].intro_audio, function(length) {
                    console.log('I got length ' + length);
                    var showTime='00:00';
                    if(length>60){
                        var minute = (length-60);

                        showTime = '01'+':'+minute;
                    }else{
                        if(parseInt(length).toString().length==1){
                            showTime = '00:0'+parseInt(length);
                        }else{
                            showTime = '00:'+parseInt(length);
                        }
                    }
                    intro_htmls = '<li>'+
                                '<aside class="subTxt">'+
                                    '<div class="subIcn"><img src="'+base_url+'images/p_audio.svg"></div>'+
                                    '<div class="subTxt2">'+
                                    showTime+
                                    '</div>'+
                                '</aside>'+
                            '</li>';
                            $('.xm_subjects').append(intro_htmls);

                });

            }


        }

        $('.xm_subjects').html(intro_html);


        $('.xm_total_mcq').text(obj.mcq);
        $('.xm_total_word_game').text(obj.word_game);
        if(obj.project=='null' || obj.project==null){
            $('.xm_toatl_project').text(0);
        }else{
            $('.xm_toatl_project').text(obj.project);
        }


        var rev_text = '';
        if(obj.exam_data.type==0){
            rev_text ='(Default) Do NOT show wrong answer';
        }else if(obj.exam_data.type==1){
            rev_text = 'Show only wrong questions at the end of the exam';
        }else if(obj.exam_data.type==2){
            rev_text ='Show wrong questions & Answer ar the end of the exam';
        }
        $('.xm_review').text(rev_text);
        $('.xm_percentage').text(obj.exam_data.pass_percentage+'%');
        var reward_mode = '';
        if(obj.exam_data.reward_mode==0){
            reward_mode = 'Single';
        }else{
            reward_mode = 'Coupon List';
        }
        $('.xm_reward_mode').val(reward_mode);
        var re_type='';
        if(obj.exam_data.reaward_type==0){
            re_type='Bluetooth';
        }else if(obj.exam_data.reaward_type==1){
            re_type ='Show Text Message';
        }else if(obj.exam_data.reaward_type==2){
            re_type='Show Image';
        }else if(obj.exam_data.reaward_type==3){
            re_type='Play Video';
        }else if(obj.exam_data.reaward_type==4){
            re_type='Certificate';
        }
        $('.xm_reward_type').text(re_type);
    })
}

async function submitReviewPage(){
    var type = $('.rev_type').val();
    var count = $('.count').val();
    var pass_percentage = $('.pass_percentage').val();
    var rew_mod = $('.rew_mod').val();
    var coupon_list = $('.coupon_list').val();
    var rew_type = $('.rew_type').val();
    var hardware_name = $('.hardware_name').val();
    var charectar = $('.charectar').val();
    var rew_text = $('.rew_text').val();
    var rew_image = $('.rew_image').prop("files")[0];
    var rew_video = $('.rew_video').val();
    var cert_lang = $('.cert_lang').val();
    var cert_logo = $('.cert_logo').prop("files")[0];
    var exam_id = localStorage.getItem("draft_exam_id");

    var form_data = new FormData();
    form_data.append("type",type);
    form_data.append("count",count);
    form_data.append("pass_percentage",pass_percentage);
    form_data.append("reward_mode",rew_mod);
    form_data.append("coupon_list",coupon_list);
    form_data.append("reaward_type",rew_type);
    form_data.append("hardwarename",hardware_name);
    form_data.append("special_control_char",charectar);
    form_data.append("re_textmsg",rew_text);
    form_data.append("re_image",rew_image);
    form_data.append("re_video",rew_video);
    form_data.append("cert_lang",cert_lang);
    form_data.append("cert_sponsor_img",cert_logo);
    form_data.append("exam_id",exam_id);

    $.ajax({
        url: "saveReviewDraft",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){ console.log(result);
            if(result!=1){
                swal("Error","Something went wrong!","error");
            }
        }
    });
}

async function submitReviewPageEdit(){
    var type = $('.rev_type').val();
    var count = $('.count').val();
    var pass_percentage = $('.pass_percentage').val();
    var rew_mod = $('.rew_mod').val();
    var coupon_list = $('.coupon_list').val();
    var rew_type = $('.rew_type').val();
    var hardware_name = $('.hardware_name').val();
    var charectar = $('.charectar').val();
    var rew_text = $('.rew_text').val();
    var rew_image = $('#rew_image').prop("files")[0];
    var rew_video = $('.rew_video').val();
    var cert_lang = $('.cert_lang').val();
    //var cert_logo = $('.cert_logo').prop("files")[0];
    var cert_logo = $('.sponsor_img').val();
    var exam_id = localStorage.getItem("draft_exam_id");
console.log("rew type",rew_type);
console.log("rew image",rew_image);
    var form_data = new FormData();
    form_data.append("type",type);
    form_data.append("count",count);
    form_data.append("pass_percentage",pass_percentage);
    form_data.append("reward_mode",rew_mod);
    form_data.append("coupon_list",coupon_list);
    form_data.append("reaward_type",rew_type);
    form_data.append("hardwarename",hardware_name);
    form_data.append("special_control_char",charectar);
    form_data.append("re_textmsg",rew_text);
    form_data.append("re_image",rew_image);
    form_data.append("re_video",rew_video);
    form_data.append("cert_lang",cert_lang);
    form_data.append("cert_sponsor_img",cert_logo);
    form_data.append("exam_id",exam_id);

    $.ajax({
        url: base_url+"saveReviewDraftEdit",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){ console.log(result);
            if(result!=1){
                swal("Error","Something went wrong!","error");
            }
        }
    });
}

function ajaxPromise(url, method, data) {
    var boolattr = method == 'POST' ? false : true;
    return new Promise( function (resolve, reject) {
        $.ajax({
          url: url,
          type: method,
          data: data,
          enctype: 'multipart/form-data',
          contentType: boolattr,
          cache: boolattr,
          processData:boolattr,
          success: function (data) {
            resolve(data)
          },
          error: function (error) {
            reject(error)
          },
        })
      })
}

function getQuestionsListCount(list) {
    return parseInt( $(list).children('.dragBx').length ) + 1;
}

function renderQuestionsListHTML(type, text) {

    var list = '.question_lists';
    var count = getQuestionsListCount(list);
    var divClass = type == 'project' ? 'prjctItm' : 'quest_list_item';
    var xmtype = type == 'project' ? '' : ' xmtype=' + type;
    var title, param, iconClass, txtClass;

    if (type == 'wg') {
        title = 'Words Game'; param = 'word_game'; iconClass = 'wrdgmicon'; txtClass = 'wgQTitle';
    }
    else if (type == 'dd') {
        title = 'Drag & Drop'; param = 'dd'; iconClass = 'ddicon'; txtClass = 'ddTxt';
    }
    else if (type == 'mcq') {
        title = 'Multiple Choice'; param = 'mc'; iconClass = 'mcicon'; txtClass = 'mcqTxt';
    }
    else if (type == 'project') {
        title = 'Project'; iconClass = 'prjicon'; txtClass = 'projcTitle';
    }

    var delEvent = type == 'project' ? 'onclick="deleteProject('+count+')"' : 'onclick="deleteQuestionDraft('+count+')"';
    var editEvent = type == 'project' ? 'onclick="editProject('+count+')"' : 'onclick="editQuestion('+count+',&#039;' + param + '&#039;)"';

    var html ='<div class="dragBx ' + divClass + '"' + xmtype + ' data="' + count + '">' +
                '<div class="drgHdr">'+
                    '<div class="drgAction">'+
                        '<span class="drgDel" ' + delEvent + '>DELETE</span>'+
                        '<span class="drgEdt" ' + editEvent + '>EDIT</span>'+
                    '</div>'+
                    '<div class="drgT1">'+
                        '<span>' + title + '</span>'+
                    '</div>'+
                    '<div class="drgAction drgAction2">'+
                        '<span class="drgUpar">Uparrow</span>'+
                        '<span class="drgDwar">Downarrow</span>'+
                    '</div>'+
                '</div>'+
                '<div class="drgWhte2"> <span class="' + iconClass + '">' + title + ' Icon</span>'+
                    '<div class="drgT3 ' + txtClass + ' center">'+text+'</div>'+
                '</div>'+
            '</div>';
    $(list).append(html);
}

function resetCommonInput() {
    $('.quest_image').val('');
    $('.quest_audio').val('');
    $('.quest_video').val('');
}

var questions = localforage.createInstance({
  name: "questions"
});

function saveProject(id, exam){
    var exam_id = exam || localStorage.getItem("draft_exam_id");
    //exam_id=1;
    if( exam_id ==null){
        swal("Error","Please fill the First page","error");
    }else{
        var project = $('.project_title').val();
        var image = $('.quest_image').val();
        var audio = $('.quest_audio').val();
        var video = $('.quest_video').val();

        var count = id || getQuestionsListCount('.question_lists');
        var jsonId = 'project_' + count;
        questions.setItem(jsonId, {examid:exam_id,title:project,link:video,order:count})
        .then( function () {
            questions.setItem(jsonId+'_files', {image:image,audio:audio})
        })
        .then( function () {
            renderQuestionsListHTML('project', project);
            closeQuestPop('project');
            popp1close();
            //reset all popup value
            $('.project_title').val('');
            resetCommonInput();
            $('.pr_img_list').html('');$('.prImgBx').hide();
            $('.prvdoTag').html(''); $('.prVdoBx').hide();
            $('.praudTag').html(''); $('.prAudBx').hide();
        })
        .catch( function (err) {
            swal("Error","Something went wrong!","error");
        });
    }
}

function saveProjectEdit(){
    var exam_id = localStorage.getItem("draft_exam_id");
    saveProject(exam_id);
}

function editProject(id){
    var count = id;
    var jsonId = 'project_' + count;

    Promise.join(questions.getItem(jsonId), questions.getItem(jsonId+'_files'), function (obj, files) {
        $('.project_title').val(obj.title);

        if(files.project_image!='' && files.project_image!=null){
            $('.quest_image').val(files.project_image);

            var split = files.project_image.split(',');
            var html = '';
            for(var i=0;i<split.length;i++){
                if(split[i]!=''){
                    html += '<li data="'+split[i]+'" onclick="deleteWgImg(this,'+id+',&#039;'+split[i]+'&#039;)"> <span class="wgIdlt">X</span>'+
                                '<div class="wgImgCrop">'+
                                    '<img src="api/uploads/'+split[i]+'">'+
                                '</div>'+
                            '</li>';
                }
            }

            $('.pr_img_list').html(html);
            $('.prImgBx').show();
        }else if(files.audio!='' && files.audio!=null){
            var html = '<audio controls>'+
                         '<source src="api/uploads/'+files.audio+'.wav" type="audio/wav">'+
                        '</audio>';
            $('.praudTag').html(html);
            $('.prAudBx').show();
            $('.quest_audio').val(files.audio);

        }else if(obj.link!='' && obj.link!=null){
            var vid = getId(obj.link);

           var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+vid+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
           $('.prvdoTag').html(ifrmhtml);
           $('.prVdoBx').show();
           $('.quest_video').val(obj.link);

        }

        $('.pr_svBtn').attr("onclick","updateProject("+obj.id+")");

        openQuestPop('project');
    })
}

function editProjectEdit(id){
    editProject(id);
}

function updateProject(id){
    saveProject(id);
}

function updateProjectEdit(id){
    saveProject(id);
}

function deleteProject(id){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {
            localStorage.removeItem('project_'+id)
            .then( function () {
                localStorage.removeItem('project_'+id+'_files');
            })
            .catch( function (err) {
                swal("Error","Something went wrong!","error");
            })
        }
    });
}

function deleteProjectEdit(id){
    deleteProject(id);
}

function saveMultipleChoiceQuest(){
    var exam_id = localStorage.getItem("draft_exam_id");
    //exam_id=1;
    if( exam_id ==null){
        swal("Error","Please fill the First page","error");
    }else{

        var question = $('.multiple_question').val();
        var image = $('.quest_image').val();
        var audio = $('.quest_audio').val();
        var video = $('.quest_video').val();

        //option data
        var option1_type = $("input[type='radio'][name='sml_rdio']:checked").val();
        var option2_type = $("input[type='radio'][name='sml_rdio2']:checked").val();
        var option3_type = $("input[type='radio'][name='sml_rdio3']:checked").val();
        var option4_type = $("input[type='radio'][name='sml_rdio4']:checked").val();

        var option1='';
        var option2='';
        var option3='';
        var option4='';
        if(option1_type=='text'){
            option1 = $('.qst_ans_1').val();
        }else if(option1_type=='image'){

            if($('.quest_image_option_1').val()=='' && $('.qst_ans_1').val()!=''){
                option1 = $('.qst_ans_1').val();
                option1_type = 'text';
            }else{
                option1 = $('.quest_image_option_1').val();
            }
        }else if(option1_type=='audio'){
            option1 = $('.quest_voice_option_1').val();
        }

        if(option2_type=='text'){
            option2 = $('.qst_ans_2').val();
        }else if(option2_type=='image'){

            if($('.quest_image_option_2').val()=='' && $('.qst_ans_2').val()!=''){
                option2 = $('.qst_ans_2').val();
                option2_type = 'text';
            }else{
                option2 = $('.quest_image_option_2').val();
            }
        }else if(option2_type=='audio'){
            option2 = $('.quest_voice_option_2').val();
        }

        if(option3_type=='text'){
            option3 = $('.qst_ans_3').val();
        }else if(option3_type=='image'){
           // option3 = $('.quest_image_option_3').val();
           if($('.quest_image_option_3').val()=='' && $('.qst_ans_3').val()!=''){
                option3 = $('.qst_ans_3').val();
                option3_type = 'text';
            }else{
                option3 = $('.quest_image_option_3').val();
            }
        }else if(option3_type=='audio'){
            option3 = $('.quest_voice_option_3').val();
        }

        if(option4_type=='text'){
            option4 = $('.qst_ans_4').val();
        }else if(option4_type=='image'){
            //option4 = $('.quest_image_option_4').val();
            if($('.quest_image_option_4').val()=='' && $('.qst_ans_4').val()!=''){
                option4 = $('.qst_ans_4').val();
                option4_type = 'text';
            }else{
                option4 = $('.quest_image_option_4').val();
            }
        }else if(option4_type=='audio'){
            option4 = $('.quest_voice_option_4').val();
        }

        var answer = $('input[type="radio"][name="mch_check"]:checked').val();
        if(question!='' && option1!='' && option2!=''){
            $.post("saveMultipleChoiceQuestion",{exam_id:exam_id,question:question,image:image,audio:audio,video:video,option1_type:option1_type,option2_type:option2_type,option3_type:option3_type,option4_type:option4_type,option1:option1,option2:option2,option3:option3,option4:option4,answer:answer},function(rs){
                if(rs!=0){

                    renderQuestionsListHTML('mcq', question);
                    //var obj = $.parseJSON(rs);
                    //var html = '<div class="dragBx quest_list_item" xmtype="mcq" data="'+obj.id+'">'+
                    //                '<div class="drgHdr">'+
                    //                    '<div class="drgAction">'+
                    //                        '<span class="drgDel" onclick="deleteQuestionDraft('+obj.id+')">DELETE</span>'+
                    //                        '<span class="drgEdt" onclick="editQuestion('+obj.id+',&#039;mc&#039;)">EDIT</span>'+
                    //                    '</div>'+
                    //                    '<div class="drgT1">'+
                    //                        '<span>Multiple Choice</span>'+
                    //                    '</div>'+
                    //                    '<div class="drgAction drgAction2">'+
                    //                        '<span class="drgUpar">Uparrow</span>'+
                    //                        '<span class="drgDwar">Downarrow</span>'+
                    //                    '</div>'+
                    //                '</div>'+
                    //                '<div class="drgWhte2"> <span class="mcicon">Multiple Choice Icon</span>'+
                    //                    '<div class="drgT3 mcqTxt center">'+question+'</div>'+
                    //                '</div>'+
                    //            '</div>';
                    //    $('.question_lists').append(html);
                        closeQuestPop('multiple_choice');
                        popp1close();
                        $('.mask').click();
                    //reset all popup value
                    $('.multiple_question').val('');
                    $('.quest_image').val('');
                    $('.quest_audio').val('');
                    $('.quest_video').val('');

                    $('.mc_img_list').html('');$('.mcImgBx').hide();

                    $('.quest_image_option_1').val('');
                    $('.quest_image_option_2').val('');
                    $('.quest_image_option_3').val('');
                    $('.quest_image_option_4').val('');

                    $('.qst_ans_1').val('');
                    $('.qst_ans_2').val('');
                    $('.qst_ans_3').val('');
                    $('.qst_ans_4').val('');

                    //question html remove
                    $('.mcvdoTag').html(''); $('.mcVdoBx').hide();
                    $('.mcaudTag').html(''); $('.mcAudBx').hide();
                    $('.mc_img_list').html(''); $('.mcImgBx').hide();
                    //remove html prview
                    $('.ansImgArea_1').html(''); $('.ansImgArea_1').hide();
                    $('.ansAud_1').html(''); $('.ansAud_1').hide();

                    $('.ansImgArea_2').html(''); $('.ansImgArea_2').hide();
                    $('.ansAud_2').html(''); $('.ansAud_2').hide();

                    $('.ansImgArea_3').html(''); $('.ansImgArea_3').hide();
                    $('.ansAud_3').html(''); $('.ansAud_3').hide();

                    $('.ansImgArea_4').html(''); $('.ansImgArea_4').hide();
                    $('.ansAud_4').html(''); $('.ansAud_4').hide();


                }else{
                    swal("Error","Something went wrong!","error");
                }
            })

        }else{
            swal("Error","Please enter all required field","error");
        }

    }
}

function saveMultipleChoiceQuestEdit(){
    var exam_id = localStorage.getItem("draft_exam_id");
    //exam_id=1;
    if( exam_id ==null){
        swal("Error","Please fill the First page","error");
    }else{

        var question = $('.multiple_question').val();
        var image = $('.quest_image').val();
        var audio = $('.quest_audio').val();
        var video = $('.quest_video').val();

        //option data
        var option1_type = $("input[type='radio'][name='sml_rdio']:checked").val();
        var option2_type = $("input[type='radio'][name='sml_rdio2']:checked").val();
        var option3_type = $("input[type='radio'][name='sml_rdio3']:checked").val();
        var option4_type = $("input[type='radio'][name='sml_rdio4']:checked").val();

        var option1='';
        var option2='';
        var option3='';
        var option4='';

        var option1_image='';
        var option2_image='';
        var option3_image='';
        var option4_image='';

        var option1_audio='';
        var option2_audio='';
        var option3_audio='';
        var option4_audio='';

        if(option1_type=='text'){
            option1 = $('.qst_ans_1').val();
        }else if(option1_type=='image'){
            option1_image = $('.quest_image_option_1').val();
        }else if(option1_type=='audio'){
            option1_audio = $('.quest_voice_option_1').val();
        }

        if(option2_type=='text'){
            option2 = $('.qst_ans_2').val();
        }else if(option2_type=='image'){
            option2_image = $('.quest_image_option_2').val();
        }else if(option2_type=='audio'){
            option2_audio = $('.quest_voice_option_2').val();
        }

        if(option3_type=='text'){
            option3 = $('.qst_ans_3').val();
        }else if(option3_type=='image'){
            option3_image = $('.quest_image_option_3').val();
        }else if(option3_type=='audio'){
            option3_audio = $('.quest_voice_option_3').val();
        }

        if(option4_type=='text'){
            option4 = $('.qst_ans_4').val();
        }else if(option4_type=='image'){
            option4_image = $('.quest_image_option_4').val();
        }else if(option4_type=='audio'){
            option4_audio = $('.quest_voice_option_4').val();
        }

        var answer = $('input[type="radio"][name="mch_check"]:checked').val();
        if((question!='') && (option1!='' || option1_image!='' || option1_audio!='') && (option2!='' || option2_image!='' || option2_audio!='')){
            $.post(base_url+"saveMultipleChoiceQuestionEdit",{examid:exam_id,question:question,question_image:image,audio:audio,link:video,option1:option1,option2:option2,option3:option3,option4:option4,option1_image:option1_image,option2_image:option2_image,option3_image:option3_image,option4_image:option4_image,option1_audio:option1_audio,option2_audio:option2_audio,option3_audio:option3_audio,option4_audio:option4_audio,answer:answer},function(rs){ console.log(rs);
                if(rs!=0){
                    renderQuestionsListHTML('mcq', question);
                    //var obj = $.parseJSON(rs);
                    //var html = '<div class="dragBx quest_list_item" xmtype="mcq" data="'+obj.id+'">'+
                    //                '<div class="drgHdr">'+
                    //                    '<div class="drgAction">'+
                    //                        '<span class="drgDel" onclick="deleteQuestionDraftEdit('+obj.id+')">DELETE</span>'+
                    //                        '<span class="drgEdt" onclick="editQuestionEdit('+obj.id+',&#039;mc&#039;)">EDIT</span>'+
                    //                    '</div>'+
                    //                    '<div class="drgT1">'+
                    //                        '<span>Multiple Choice</span>'+
                    //                    '</div>'+
                    //                    '<div class="drgAction drgAction2">'+
                    //                        '<span class="drgUpar">Uparrow</span>'+
                    //                        '<span class="drgDwar">Downarrow</span>'+
                    //                    '</div>'+
                    //                '</div>'+
                    //                '<div class="drgWhte2"> <span class="mcicon">Multiple Choice Icon</span>'+
                    //                    '<div class="drgT3 mcqTxt center">'+question+'</div>'+
                    //                '</div>'+
                    //            '</div>';
                    //    $('.question_lists').append(html);
                        closeQuestPop('multiple_choice');
                        popp1close();
                        $('.mask').click();
                    //reset all popup value
                    $('.multiple_question').val('');
                    $('.quest_image').val('');
                    $('.quest_audio').val('');
                    $('.quest_video').val('');

                    $('.mc_img_list').html('');$('.mcImgBx').hide();

                    $('.quest_image_option_1').val('');
                    $('.quest_image_option_2').val('');
                    $('.quest_image_option_3').val('');
                    $('.quest_image_option_4').val('');

                    $('.qst_ans_1').val('');
                    $('.qst_ans_2').val('');
                    $('.qst_ans_3').val('');
                    $('.qst_ans_4').val('');

                    //question html remove
                    $('.mcvdoTag').html(''); $('.mcVdoBx').hide();
                    $('.mcaudTag').html(''); $('.mcAudBx').hide();
                    $('.mc_img_list').html(''); $('.mcImgBx').hide();
                    //remove html prview
                    $('.ansImgArea_1').html(''); $('.ansImgArea_1').hide();
                    $('.ansAud_1').html(''); $('.ansAud_1').hide();

                    $('.ansImgArea_2').html(''); $('.ansImgArea_2').hide();
                    $('.ansAud_2').html(''); $('.ansAud_2').hide();

                    $('.ansImgArea_3').html(''); $('.ansImgArea_3').hide();
                    $('.ansAud_3').html(''); $('.ansAud_3').hide();

                    $('.ansImgArea_4').html(''); $('.ansImgArea_4').hide();
                    $('.ansAud_4').html(''); $('.ansAud_4').hide();


                }else{
                    swal("Error","Something went wrong!","error");
                }
            })

        }else{
            swal("Error","Please enter all required field","error");
        }

    }
}

function updateMultipleChoiceQuest(id){
    var exam_id = localStorage.getItem("draft_exam_id");

    var question = $('.multiple_question').val();
    var image = $('.quest_image').val();
    var audio = $('.quest_audio').val();
    var video = $('.quest_video').val();

    //option data
    var option1_type = $("input[type='radio'][name='sml_rdio']:checked").val();
    var option2_type = $("input[type='radio'][name='sml_rdio2']:checked").val();
    var option3_type = $("input[type='radio'][name='sml_rdio3']:checked").val();
    var option4_type = $("input[type='radio'][name='sml_rdio4']:checked").val();

    var option1='';
    var option2='';
    var option3='';
    var option4='';
    if(option1_type=='text'){
        option1 = $('.qst_ans_1').val();
    }else if(option1_type=='image'){
        option1 = $('.quest_image_option_1').val();
    }else if(option1_type=='audio'){
        option1 = $('.quest_voice_option_1').val();
    }

    if(option2_type=='text'){
        option2 = $('.qst_ans_2').val();
    }else if(option2_type=='image'){
        option2 = $('.quest_image_option_2').val();
    }else if(option2_type=='audio'){
        option2 = $('.quest_voice_option_2').val();
    }

    if(option3_type=='text'){
        option3 = $('.qst_ans_3').val();
    }else if(option3_type=='image'){
        option3 = $('.quest_image_option_3').val();
    }else if(option3_type=='audio'){
        option3 = $('.quest_voice_option_3').val();
    }

    if(option4_type=='text'){
        option4 = $('.qst_ans_4').val();
    }else if(option4_type=='image'){
        option4 = $('.quest_image_option_4').val();
    }else if(option4_type=='audio'){
        option4 = $('.quest_voice_option_4').val();
    }

    var answer = $('input[type="radio"][name="mch_check"]:checked').val();
    console.log("answer=",answer);
    $.post("updateMultipleChoiceQuestion",{id:id,exam_id:exam_id,question:question,question_image:image,audio:audio,video:video,option1_type:option1_type,option2_type:option2_type,option3_type:option3_type,option4_type:option4_type,option1:option1,option2:option2,option3:option3,option4:option4,answer:answer},function(rs){
        if(rs!=0){

            $('.quest_list_item').each(function(){
                if($(this).attr("data")==id){
                    $(this).find(".mcqTxt").text(question);
                }
            })

                closeQuestPop('multiple_choice');
                popp1close();
            //reset all popup value
            $('.multiple_question').val('');
            $('.quest_image').val('');
            $('.quest_audio').val('');
            $('.quest_video').val('');

            $('.mc_img_list').html('');$('.mcImgBx').hide();

            $('.quest_image_option_1').val('');
            $('.quest_image_option_2').val('');
            $('.quest_image_option_3').val('');
            $('.quest_image_option_4').val('');

            $('.qst_ans_1').val('');
            $('.qst_ans_2').val('');
            $('.qst_ans_3').val('');
            $('.qst_ans_4').val('');

            //question html remove
            $('.mcvdoTag').html(''); $('.mcVdoBx').hide();
            $('.mcaudTag').html(''); $('.mcAudBx').hide();
            $('.mc_img_list').html(''); $('.mcImgBx').hide();
            //remove html prview
            $('.ansImgArea_1').html(''); $('.ansImgArea_1').hide();
            $('.ansAud_1').html(''); $('.ansAud_1').hide();

            $('.ansImgArea_2').html(''); $('.ansImgArea_2').hide();
            $('.ansAud_2').html(''); $('.ansAud_2').hide();

            $('.ansImgArea_3').html(''); $('.ansImgArea_3').hide();
            $('.ansAud_3').html(''); $('.ansAud_3').hide();

            $('.ansImgArea_4').html(''); $('.ansImgArea_4').hide();
            $('.ansAud_4').html(''); $('.ansAud_4').hide();

            $('.mcq_svBtn').attr("onclick","saveMultipleChoiceQuest()");

        }else{
            swal("Error","Something went wrong!","error");
        }
    })
}

function updateMultipleChoiceQuestEdit(id){
    var exam_id = localStorage.getItem("draft_exam_id");

    var question = $('.multiple_question').val();
    var image = $('.quest_image').val();
    var audio = $('.quest_audio').val();
    var video = $('.quest_video').val();

    //option data
    var option1_type = $("input[type='radio'][name='sml_rdio']:checked").val();
    var option2_type = $("input[type='radio'][name='sml_rdio2']:checked").val();
    var option3_type = $("input[type='radio'][name='sml_rdio3']:checked").val();
    var option4_type = $("input[type='radio'][name='sml_rdio4']:checked").val();

    var option1='';
    var option2='';
    var option3='';
    var option4='';

    var option1_image='';
    var option2_image='';
    var option3_image='';
    var option4_image='';

    var option1_audio='';
    var option2_audio='';
    var option3_audio='';
    var option4_audio='';

    if(option1_type=='text'){
        option1 = $('.qst_ans_1').val();
    }else if(option1_type=='image'){
        option1_image = $('.quest_image_option_1').val();
    }else if(option1_type=='audio'){
        option1_audio = $('.quest_voice_option_1').val();
    }

    if(option2_type=='text'){
        option2 = $('.qst_ans_2').val();
    }else if(option2_type=='image'){
        option2_image = $('.quest_image_option_2').val();
    }else if(option2_type=='audio'){
        option2_audio = $('.quest_voice_option_2').val();
    }

    if(option3_type=='text'){
        option3 = $('.qst_ans_3').val();
    }else if(option3_type=='image'){
        option3_image = $('.quest_image_option_3').val();
    }else if(option3_type=='audio'){
        option3_audio = $('.quest_voice_option_3').val();
    }

    if(option4_type=='text'){
        option4 = $('.qst_ans_4').val();
    }else if(option4_type=='image'){
        option4_image = $('.quest_image_option_4').val();
    }else if(option4_type=='audio'){
        option4_audio = $('.quest_voice_option_4').val();
    }

    var answer = $('input[type="radio"][name="mch_check"]:checked').val();
    console.log("answer=",answer);
    $.post(base_url+"updateMultipleChoiceQuestionEdit",{id:id,exam_id:exam_id,question:question,question_image:image,audio:audio,link:video,option1:option1,option2:option2,option3:option3,option4:option4,option1_image:option1_image,option2_image:option2_image,option3_image:option3_image,option4_image:option4_image,option1_audio:option1_audio,option2_audio:option2_audio,option3_audio:option3_audio,option4_audio:option4_audio,answer:answer},function(rs){
        if(rs!=0){

            $('.quest_list_item').each(function(){
                if($(this).attr("data")==id){
                    $(this).find(".mcqTxt").text(question);
                }
            })

                closeQuestPop('multiple_choice');
                popp1close();
            //reset all popup value
            $('.multiple_question').val('');
            $('.quest_image').val('');
            $('.quest_audio').val('');
            $('.quest_video').val('');

            $('.mc_img_list').html('');$('.mcImgBx').hide();

            $('.quest_image_option_1').val('');
            $('.quest_image_option_2').val('');
            $('.quest_image_option_3').val('');
            $('.quest_image_option_4').val('');

            $('.qst_ans_1').val('');
            $('.qst_ans_2').val('');
            $('.qst_ans_3').val('');
            $('.qst_ans_4').val('');

            //question html remove
            $('.mcvdoTag').html(''); $('.mcVdoBx').hide();
            $('.mcaudTag').html(''); $('.mcAudBx').hide();
            $('.mc_img_list').html(''); $('.mcImgBx').hide();
            //remove html prview
            $('.ansImgArea_1').html(''); $('.ansImgArea_1').hide();
            $('.ansAud_1').html(''); $('.ansAud_1').hide();

            $('.ansImgArea_2').html(''); $('.ansImgArea_2').hide();
            $('.ansAud_2').html(''); $('.ansAud_2').hide();

            $('.ansImgArea_3').html(''); $('.ansImgArea_3').hide();
            $('.ansAud_3').html(''); $('.ansAud_3').hide();

            $('.ansImgArea_4').html(''); $('.ansImgArea_4').hide();
            $('.ansAud_4').html(''); $('.ansAud_4').hide();

            $('.mcq_svBtn').attr("onclick","saveMultipleChoiceQuest()");

        }else{
            swal("Error","Something went wrong!","error");
        }
    })
}

function saveWordGameQuest(){
    var image = $('.quest_image').val();
    var audio = $('.quest_audio').val();
    var video = $('.quest_video').val();
    var question = $('.word_game_title').val();
    //check arabic text and reverese
    var arabic = /[\u0600-\u06FF]/;
    var answer = $('.word_game_answer').val();
    if(arabic.test(answer)){
        answer = answer.split("").reverse().join("");
    }
    var exam_id = localStorage.getItem("draft_exam_id");
    var type='word_game';
    if( exam_id ==null){
        swal("Error","Please fill the First page","error");
    }else if(question=='' || answer==''){
        swal("Error","Please fill all the fields","error");
    }else{

            $.post("saveQuestDraft",{image:image,audio:audio,video:video,question:question,answer:answer,type:type,exam_id:exam_id},function(rs){
                console.log(rs);
                if(rs!=0){

                    renderQuestionsListHTML('wg', question);

                    //var obj = $.parseJSON(rs);
                    //var html ='<div class="dragBx quest_list_item" xmtype="wg" data="'+obj.id+'">'+
                    //            '<div class="drgHdr">'+
                    //                '<div class="drgAction">'+
                    //                    '<span class="drgDel" onclick="deleteQuestionDraft('+obj.id+')">DELETE</span>'+
                    //                    '<span class="drgEdt" onclick="editQuestion('+obj.id+',&#039;word_game&#039;)">EDIT</span>'+
                    //                '</div>'+
                    //                '<div class="drgT1">'+
                    //                    '<span>Words Game</span>'+
                    //                '</div>'+
                    //                '<div class="drgAction drgAction2">'+
                    //                        '<span class="drgUpar">Uparrow</span>'+
                    //                        '<span class="drgDwar">Downarrow</span>'+
                    //                    '</div>'+
                    //            '</div>'+
                    //            '<div class="drgWhte2"> <span class="wrdgmicon">Words Game Icon</span>'+
                    //                '<div class="drgT3 wgQTitle center">'+obj.question+'</div>'+
                    //            '</div>'+
                    //        '</div>';
                    //$('.question_lists').append(html);

                    //reset all data
                    $('.quest_image').val('');
                    $('.quest_video').val('');
                    $('.quest_audio').val('');
                    $('.word_game_title').val('');
                    $('.word_game_answer').val('');
                    $('.wg_img_list').html(''); $('.wgImgBx').hide();
                    $('.wgaudTag').html(''); $('.wgAudBx').hide();
                    $('.wgvdoTag').html(''); $('.wgVdoBx').hide();

                    $('.mask').click();
                    closeQuestPop('word_game');
                }else{
                    swal("Error","Something went wrong!","error");
                }
            })

    }
}

function saveWordGameQuestEdit(){
    var image = $('.quest_image').val();
    var audio = $('.quest_audio').val();
    var video = $('.quest_video').val();
    var question = $('.word_game_title').val();
    //check arabic text and reverese
    var arabic = /[\u0600-\u06FF]/;
    var answer = $('.word_game_answer').val();
    if(arabic.test(answer)){
        answer = answer.split("").reverse().join("");
    }
    var exam_id = localStorage.getItem("draft_exam_id");
    var type='word_game';
    if( exam_id ==null){
        swal("Error","Please fill the First page","error");
    }else if(question=='' || answer==''){
        swal("Error","Please fill all the fields","error");
    }else{
        $.post(base_url+"saveQuestDraftEdit",{image:image,audio:audio,link:video,question:question,answer:answer,type:type,exam_id:exam_id},function(rs){
            console.log(rs);
            if(rs!=0){

                renderQuestionsListHTML('wg', question);

                //var obj = $.parseJSON(rs);
                //var html ='<div class="dragBx quest_list_item" xmtype="wg" data="'+obj.id+'">'+
                //            '<div class="drgHdr">'+
                //                '<div class="drgAction">'+
                //                    '<span class="drgDel" onclick="deleteQuestionDraftEdit('+obj.id+')">DELETE</span>'+
                //                    '<span class="drgEdt" onclick="editQuestionEdit('+obj.id+',&#039;word_game&#039;)">EDIT</span>'+
                //                '</div>'+
                //                '<div class="drgT1">'+
                //                    '<span>Words Game</span>'+
                //                '</div>'+
                //                '<div class="drgAction drgAction2">'+
                //                        '<span class="drgUpar">Uparrow</span>'+
                //                        '<span class="drgDwar">Downarrow</span>'+
                //                    '</div>'+
                //            '</div>'+
                //            '<div class="drgWhte2"> <span class="wrdgmicon">Words Game Icon</span>'+
                //                '<div class="drgT3 wgQTitle center">'+obj.question+'</div>'+
                //            '</div>'+
                //        '</div>';
                //$('.question_lists').append(html);

                //reset all data
                $('.quest_image').val('');
                $('.quest_video').val('');
                $('.quest_audio').val('');
                $('.word_game_title').val('');
                $('.word_game_answer').val('');
                $('.wg_img_list').html(''); $('.wgImgBx').hide();
                $('.wgaudTag').html(''); $('.wgAudBx').hide();
                $('.wgvdoTag').html(''); $('.wgVdoBx').hide();

                $('.mask').click();
                closeQuestPop('word_game');
            }else{
                swal("Error","Something went wrong!","error");
            }
        })
    }
}

function updateWordGameQuest(id){
    var question = $('.word_game_title').val();
    var image = $('.quest_image').val();
    var audio = $('.quest_audio').val();
    var video = $('.quest_video').val();
    var answer = $('.word_game_answer').val();

    var arabic = /[\u0600-\u06FF]/;
    if(arabic.test(answer)){
        answer = answer.split("").reverse().join("");
    }

    $.post("updateWordGameQuest",{id:id,image:image,audio:audio,video:video,answer:answer,question:question},function(rs){
        if(rs==1){
            $('.quest_list_item').each(function(rs){
                if($(this).attr("data")==id){
                   $(this).find('.wgQTitle').text(question);
                }
            })

            closeQuestPop('word_game');
            //reset all data
            $('.quest_image').val('');
            $('.quest_video').val('');
            $('.quest_audio').val('');
            $('.word_game_title').val('');
            $('.word_game_answer').val('');
            $('.wg_img_list').html(''); $('.wgImgBx').hide();
            $('.wgaudTag').html(''); $('.wgAudBx').hide();
            $('.wgvdoTag').html(''); $('.wgVdoBx').hide();

            $('.wgSvBtn').attr("onclick","saveWordGameQuest()");
        }
    })
}

function updateWordGameQuestEdit(id){
    var question = $('.word_game_title').val();
    var image = $('.quest_image').val();
    var audio = $('.quest_audio').val();
    var video = $('.quest_video').val();
    var answer = $('.word_game_answer').val();
   // var answer = $('.word_game_answer').val();
   var arabic = /[\u0600-\u06FF]/;
    if(arabic.test(answer)){
        answer = answer.split("").reverse().join("");
    }
    $.post(base_url+"updateWordGameQuestEdit",{id:id,image:image,audio:audio,video:video,answer:answer,question:question},function(rs){ console.log(rs);
        if(rs==1){
            $('.quest_list_item').each(function(rs){
                if($(this).attr("data")==id){
                   $(this).find('.wgQTitle').text(question);
                }
            })

            closeQuestPop('word_game');
            //reset all data
            $('.quest_image').val('');
            $('.quest_video').val('');
            $('.quest_audio').val('');
            $('.word_game_title').val('');
            $('.word_game_answer').val('');
            $('.wg_img_list').html(''); $('.wgImgBx').hide();
            $('.wgaudTag').html(''); $('.wgAudBx').hide();
            $('.wgvdoTag').html(''); $('.wgVdoBx').hide();

            $('.wgSvBtn').attr("onclick","saveWordGameQuest()");
        }
    })
}

function deleteWgImg(obj,id,name){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {

                var images = $('.quest_image').val();
                var split_img = images.split(',');
                split_img = $.grep(split_img, function(value){
                    return value!=name;
                })

                var updated_image = split_img.join(); //deafault join by comma ,
                $('.quest_image').val(updated_image);

                $(obj).fadeOut();
            }
        });

}

function deleteWgImgEdit(obj,id,name){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {

                var images = $('.quest_image').val();
                var split_img = images.split(',');
                split_img = $.grep(split_img, function(value){
                    return value!=name;
                })

                var updated_image = split_img.join(); //deafault join by comma ,
                $('.quest_image').val(updated_image);

                $(obj).parent().fadeOut();
            }
        });

}

function saveDDQuestion(){
    var question = $('.dd_question').val();
    var question_img = $('.quest_image').val();

    var target1 = $('.target_1').val();
    var target1_data = $('.target_1_imgdata').val();

    var target2 = $('.target_2').val();
    var target2_data = $('.target_2_imgdata').val();

    var target3 = $('.target_3').val();
    var target3_data = $('.target_3_imgdata').val();

    var target4 = $('.target_4').val();
    var target4_data = $('.target_4_imgdata').val();

    var target_ans_text1 = $('.ddTgAnsTxt_1').val();
    var target_ans_text2 = $('.ddTgAnsTxt_2').val();
    var target_ans_text3 = $('.ddTgAnsTxt_3').val();
    var target_ans_text4 = $('.ddTgAnsTxt_4').val();

    var target_ans_img1 = $('.ddTgAnsImg_1').prop("files")[0];
    var target_ans_img2 = $('.ddTgAnsImg_2').prop("files")[0];
    var target_ans_img3 = $('.ddTgAnsImg_3').prop("files")[0];
    var target_ans_img4 = $('.ddTgAnsImg_4').prop("files")[0];


    var exam_id = localStorage.getItem("draft_exam_id");

    var form_data = new FormData();
    form_data.append("question",question);
    form_data.append("question_img",question_img);
    form_data.append("target1",target1);
    form_data.append("target1_data",target1_data);
    form_data.append("target2",target2);
    form_data.append("target2_data",target2_data);
    form_data.append("target3",target3);
    form_data.append("target3_data",target3_data);
    form_data.append("target4",target4);
    form_data.append("target4_data",target4_data);
    form_data.append("target_ans_text1",target_ans_text1);
    form_data.append("target_ans_text2",target_ans_text2);
    form_data.append("target_ans_text3",target_ans_text3);
    form_data.append("target_ans_text4",target_ans_text4);
    if(target_ans_img1!='undefined'){
        form_data.append("target_ans_img1",target_ans_img1);
    }
    if(target_ans_img2!='undefined'){
        form_data.append("target_ans_img2",target_ans_img2);
    }
    if(target_ans_img3!='undefined'){
        form_data.append("target_ans_img3",target_ans_img3);
    }
    if(target_ans_img4!='undefined'){
        form_data.append("target_ans_img4",target_ans_img4);
    }
    form_data.append("exam_id",exam_id);

    $.ajax({
        url:"createDDquestionDraft",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        contentType: false,
        cache: false,
        question: question,
        processData:false,
        success: function(result){console.log(result);
            var obj = $.parseJSON(result);
            if(obj.status==1){
                renderQuestionsListHTML('dd', question);
                //$('.question_lists').append('<div class="dragBx quest_list_item" xmtype="dd" data="'+obj.id+'">'+
                //                                '<div class="drgHdr">'+
                //                                    '<div class="drgAction">'+
                //                                        '<span class="drgDel" onclick="deleteQuestionDraft('+obj.id+')">DELETE</span>'+
                //                                        '<span class="drgEdt" onclick="editQuestion('+obj.id+',&#039;dd&#039;)">EDIT</span>'+
                //                                    '</div>'+
                //                                    '<div class="drgT1">'+
                //                                        '<span>Drag & Drop </span>'+
                //                                    '</div>'+
                //                                    '<div class="drgAction drgAction2">'+
                //                                        '<span class="drgUpar">Uparrow</span>'+
                //                                        '<span class="drgDwar">Downarrow</span>'+
                //                                    '</div>'+
                //                                '</div>'+
                //                                '<div class="drgWhte2"> <span class="ddicon">Drag & Drop  Icon</span>'+
                //                                    '<div class="drgT3 ddTxt center">'+question+'</div>'+
                //                                '</div>'+
                //                            '</div>');

                //reset the dd form
                for(var rm=1;rm<=4;rm++){
                    $('.ddTgAnsTxt_'+rm).val('');
                    $('.ddAnsTxt_'+rm).slideUp();
                    $('.ddAnsImg_'+rm).slideUp();
                    $('.ddItbtn_'+rm).show();
                    $('.ddTgAnsImg_'+rm).val('');

                    $('.crpTrg'+rm).html('<img src="images/image.svg" />');
                    $('.target_'+rm).val('');
                    $('.target_'+rm+'_imgdata').val('');
                    $('.ddTrgtLi_'+rm).removeClass("added");
                }

                removeDDQsnImg();
                $('.dd_question').val('');
                $('.quest_image').val('');

                closeQuestPop('dd');
                $('.mask').click();
            }else{
                swal("Error","Something went wrong!","error");
            }
        }
    });
}

function updateDDQuestion(id){
    var question = $('.dd_question').val();
    var question_img = $('.quest_image').val();

    var target1 = $('.target_1').val();
    var target1_data = $('.target_1_imgdata').val();

    var target2 = $('.target_2').val();
    var target2_data = $('.target_2_imgdata').val();

    var target3 = $('.target_3').val();
    var target3_data = $('.target_3_imgdata').val();

    var target4 = $('.target_4').val();
    var target4_data = $('.target_4_imgdata').val();

    var target_ans_text1 = $('.ddTgAnsTxt_1').val();
    var target_ans_text2 = $('.ddTgAnsTxt_2').val();
    var target_ans_text3 = $('.ddTgAnsTxt_3').val();
    var target_ans_text4 = $('.ddTgAnsTxt_4').val();

    var target_ans_img1 = $('.ddTgAnsImg_1').prop("files")[0];
    var target_ans_img2 = $('.ddTgAnsImg_2').prop("files")[0];
    var target_ans_img3 = $('.ddTgAnsImg_3').prop("files")[0];
    var target_ans_img4 = $('.ddTgAnsImg_4').prop("files")[0];


    var qsn_id = id;

    var form_data = new FormData();
    form_data.append("question",question);
    form_data.append("question_img",question_img);
    form_data.append("target1",target1);
    form_data.append("target1_data",target1_data);
    form_data.append("target2",target2);
    form_data.append("target2_data",target2_data);
    form_data.append("target3",target3);
    form_data.append("target3_data",target3_data);
    form_data.append("target4",target4);
    form_data.append("target4_data",target4_data);
    form_data.append("target_ans_text1",target_ans_text1);
    form_data.append("target_ans_text2",target_ans_text2);
    form_data.append("target_ans_text3",target_ans_text3);
    form_data.append("target_ans_text4",target_ans_text4);
    if(target_ans_img1!='undefined'){
        form_data.append("target_ans_img1",target_ans_img1);
    }
    if(target_ans_img2!='undefined'){
        form_data.append("target_ans_img2",target_ans_img2);
    }
    if(target_ans_img3!='undefined'){
        form_data.append("target_ans_img3",target_ans_img3);
    }
    if(target_ans_img4!='undefined'){
        form_data.append("target_ans_img4",target_ans_img4);
    }
    form_data.append("qsn_id",qsn_id);

    $.ajax({
        url:"updateDDquestionDraft",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){
            if(result==1){
                $('.quest_list_item').each(function(){
                    if($(this).attr('data')==id){
                        $(this).find(".ddTxt").text(question);
                    }
                })

                //reset the dd form
                for(var rm=1;rm<=4;rm++){
                    $('.ddTgAnsTxt_'+rm).val('');
                    $('.ddAnsTxt_'+rm).slideUp();
                    $('.ddAnsImg_'+rm).slideUp();
                    $('.ddItbtn_'+rm).show();
                    $('.ddTgAnsImg_'+rm).val('');

                    $('.crpTrg'+rm).html('<img src="images/image.svg" />');
                    $('.target_'+rm).val('');
                    $('.target_'+rm+'_imgdata').val('');
                    $('.ddTrgtLi_'+rm).removeClass("added");
                }

                removeDDQsnImg();
                $('.dd_question').val('');
                $('.quest_image').val('');

                $('.ddsaveQsn').attr("onclick","saveDDQuestion()");
                closeQuestPop('dd');
                $('.mask').click();
            }else{
                swal("Error","Something went wrong!","error");
            }
        }
    });
}

function saveDDQuestionEdit(){
    var question = $('.dd_question').val();
    var question_img = $('.quest_image').val();

    var target1 = $('.target_1').val();
    var target1_data = $('.target_1_imgdata').val();

    var target2 = $('.target_2').val();
    var target2_data = $('.target_2_imgdata').val();

    var target3 = $('.target_3').val();
    var target3_data = $('.target_3_imgdata').val();

    var target4 = $('.target_4').val();
    var target4_data = $('.target_4_imgdata').val();

    var target_ans_text1 = $('.ddTgAnsTxt_1').val();
    var target_ans_text2 = $('.ddTgAnsTxt_2').val();
    var target_ans_text3 = $('.ddTgAnsTxt_3').val();
    var target_ans_text4 = $('.ddTgAnsTxt_4').val();

    var target_ans_img1 = $('.ddTgAnsImg_1').prop("files")[0];
    var target_ans_img2 = $('.ddTgAnsImg_2').prop("files")[0];
    var target_ans_img3 = $('.ddTgAnsImg_3').prop("files")[0];
    var target_ans_img4 = $('.ddTgAnsImg_4').prop("files")[0];


    var exam_id = localStorage.getItem("draft_exam_id");

    var form_data = new FormData();
    form_data.append("question",question);
    form_data.append("question_img",question_img);
    form_data.append("target1",target1);
    form_data.append("target1_data",target1_data);
    form_data.append("target2",target2);
    form_data.append("target2_data",target2_data);
    form_data.append("target3",target3);
    form_data.append("target3_data",target3_data);
    form_data.append("target4",target4);
    form_data.append("target4_data",target4_data);
    form_data.append("target_ans_text1",target_ans_text1);
    form_data.append("target_ans_text2",target_ans_text2);
    form_data.append("target_ans_text3",target_ans_text3);
    form_data.append("target_ans_text4",target_ans_text4);
    if(target_ans_img1!='undefined'){
        form_data.append("target_ans_img1",target_ans_img1);
    }
    if(target_ans_img2!='undefined'){
        form_data.append("target_ans_img2",target_ans_img2);
    }
    if(target_ans_img3!='undefined'){
        form_data.append("target_ans_img3",target_ans_img3);
    }
    if(target_ans_img4!='undefined'){
        form_data.append("target_ans_img4",target_ans_img4);
    }
    form_data.append("exam_id",exam_id);

    $.ajax({
        url:base_url+"createDDquestionDraftEdit",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        contentType: false,
        cache: false,
        question: question,
        processData:false,
        success: function(result){console.log(result);
            var obj = $.parseJSON(result);
            if(obj.status==1){
                renderQuestionsListHTML('dd', question);
                //$('.question_lists').append('<div class="dragBx quest_list_item" xmtype="dd" data="'+obj.id+'">'+
                //                                '<div class="drgHdr">'+
                //                                    '<div class="drgAction">'+
                //                                        '<span class="drgDel" onclick="deleteQuestionDraft('+obj.id+')">DELETE</span>'+
                //                                        '<span class="drgEdt" onclick="editQuestion('+obj.id+',&#039;dd&#039;)">EDIT</span>'+
                //                                    '</div>'+
                //                                    '<div class="drgT1">'+
                //                                        '<span>Drag & Drop </span>'+
                //                                    '</div>'+
                //                                    '<div class="drgAction drgAction2">'+
                //                                        '<span class="drgUpar">Uparrow</span>'+
                //                                        '<span class="drgDwar">Downarrow</span>'+
                //                                    '</div>'+
                //                                '</div>'+
                //                                '<div class="drgWhte2"> <span class="ddicon">Drag & Drop  Icon</span>'+
                //                                    '<div class="drgT3 ddTxt center">'+question+'</div>'+
                //                                '</div>'+
                //                            '</div>');

                //reset the dd form
                for(var rm=1;rm<=4;rm++){
                    $('.ddTgAnsTxt_'+rm).val('');
                    $('.ddAnsTxt_'+rm).slideUp();
                    $('.ddAnsImg_'+rm).slideUp();
                    $('.ddItbtn_'+rm).show();
                    $('.ddTgAnsImg_'+rm).val('');

                    $('.crpTrg'+rm).html('<img src="../images/image.svg" />');
                    $('.target_'+rm).val('');
                    $('.target_'+rm+'_imgdata').val('');
                    $('.ddTrgtLi_'+rm).removeClass("added");
                }

                removeDDQsnImgEdit();
                $('.dd_question').val('');
                $('.quest_image').val('');

                closeQuestPopEdit('dd');
                $('.mask').click();
            }else{
                swal("Error","Something went wrong!","error");
            }
        }
    });
}

function updateDDQuestionEdit(id){
    var question = $('.dd_question').val();
    var question_img = $('.quest_image').val();

    var target1 = $('.target_1').val();
    var target1_data = $('.target_1_imgdata').val();

    var target2 = $('.target_2').val();
    var target2_data = $('.target_2_imgdata').val();

    var target3 = $('.target_3').val();
    var target3_data = $('.target_3_imgdata').val();

    var target4 = $('.target_4').val();
    var target4_data = $('.target_4_imgdata').val();

    var target_ans_text1 = $('.ddTgAnsTxt_1').val();
    var target_ans_text2 = $('.ddTgAnsTxt_2').val();
    var target_ans_text3 = $('.ddTgAnsTxt_3').val();
    var target_ans_text4 = $('.ddTgAnsTxt_4').val();

    var target_ans_img1 = $('.ddTgAnsImg_1').prop("files")[0];
    var target_ans_img2 = $('.ddTgAnsImg_2').prop("files")[0];
    var target_ans_img3 = $('.ddTgAnsImg_3').prop("files")[0];
    var target_ans_img4 = $('.ddTgAnsImg_4').prop("files")[0];


    var qsn_id = id;

    var form_data = new FormData();
    form_data.append("question",question);
    form_data.append("question_img",question_img);
    form_data.append("target1",target1);
    form_data.append("target1_data",target1_data);
    form_data.append("target2",target2);
    form_data.append("target2_data",target2_data);
    form_data.append("target3",target3);
    form_data.append("target3_data",target3_data);
    form_data.append("target4",target4);
    form_data.append("target4_data",target4_data);
    form_data.append("target_ans_text1",target_ans_text1);
    form_data.append("target_ans_text2",target_ans_text2);
    form_data.append("target_ans_text3",target_ans_text3);
    form_data.append("target_ans_text4",target_ans_text4);
    if(target_ans_img1!='undefined'){
        form_data.append("target_ans_img1",target_ans_img1);
    }
    if(target_ans_img2!='undefined'){
        form_data.append("target_ans_img2",target_ans_img2);
    }
    if(target_ans_img3!='undefined'){
        form_data.append("target_ans_img3",target_ans_img3);
    }
    if(target_ans_img4!='undefined'){
        form_data.append("target_ans_img4",target_ans_img4);
    }
    form_data.append("qsn_id",qsn_id);

    $.ajax({
        url:base_url+"updateDDquestionEdit",
        type: "POST",
        data: form_data,
        enctype: 'multipart/form-data',
        contentType: false,
        cache: false,
        processData:false,
        success: function(result){ console.log(result);
            if(result==1){
                $('.quest_list_item').each(function(){
                    if($(this).attr('data')==id){
                        $(this).find(".ddTxt").text(question);
                    }
                })

                //reset the dd form
                for(var rm=1;rm<=4;rm++){
                    $('.ddTgAnsTxt_'+rm).val('');
                    $('.ddAnsTxt_'+rm).slideUp();
                    $('.ddAnsImg_'+rm).slideUp();
                    $('.ddItbtn_'+rm).show();
                    $('.ddTgAnsImg_'+rm).val('');

                    $('.crpTrg'+rm).html('<img src="../images/image.svg" />');
                    $('.target_'+rm).val('');
                    $('.target_'+rm+'_imgdata').val('');
                    $('.ddTrgtLi_'+rm).removeClass("added");
                }

                removeDDQsnImgEdit();
                $('.dd_question').val('');
                $('.quest_image').val('');

                $('.ddsaveQsn').attr("onclick","saveDDQuestionEdit()");
                closeQuestPop('dd');
                $('.mask').click();
            }else{
                swal("Error","Something went wrong!","error");
            }
        }
    });
}

function editQuestion(id,type){
    if(type=='word_game'){
        $.post("getDraftQuestions",{id:id,type:type},function(rs){ console.log(rs);
            var obj = $.parseJSON(rs);
            $('.word_game_title').val(obj.question);
            if(obj.question_image!='' && obj.question_image!=null){
                $('.quest_image').val(obj.question_image);

                var split = obj.question_image.split(',');
                var html = '';
                for(var i=0;i<split.length;i++){
                    if(split[i]!=''){
                        html += '<li data="'+split[i]+'" onclick="deleteWgImg(this,'+id+',&#039;'+split[i]+'&#039;)"> <span class="wgIdlt">X</span>'+
                                    '<div class="wgImgCrop">'+
                                        '<img src="api/uploads/'+split[i]+'">'+
                                    '</div>'+
                                '</li>';
                    }
                }

                $('.wg_img_list').html(html);

                var existing_real_image = parseInt(parseInt($('.wg_img_list').find('.wgimg').length) + 1);
                $('.wg_img_list').find('.tmpImg').remove();
                //$('.wg_img_list').append(html);
                var placeHolderImg = '';
                for(var m=0;m<=(4 - existing_real_image);m++){
                     placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                             '<div class="wgImgCrop" onclick="clickWgQImage()">'+
                                                 '<img src="images/image.svg">'+
                                             '</div>'+
                                         '</li>';
                     $('.wg_img_list').append(placeHolderImg);
                }

                $('.wgImgBx').show();
            }else if(obj.audio!='' && obj.audio!=null){
                var html = '<audio controls>'+
                             '<source src="api/uploads/'+obj.audio+'" type="audio/wav">'+
                            '</audio>';
                $('.wgaudTag').html(html);
                $('.wgAudBx').show();
                $('.quest_audio').val(obj.audio);

            }else if(obj.video!='' && obj.video!=null){
                var vid = getId(obj.video);

               var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+vid+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
               $('.wgvdoTag').html(ifrmhtml);
               $('.wgVdoBx').show();
               $('.quest_video').val(obj.video);

            }

            var arabic = /[\u0600-\u06FF]/;
            if(arabic.test(obj.answer)){
                obj.answer = obj.answer.split("").reverse().join("");
            }
            $('.word_game_answer').val(obj.answer);

            $('.wgSvBtn').attr("onclick","updateWordGameQuest("+id+")");
        })

        openQuestPop('word_game');
    }else if(type=='mc'){ //multiple choice question
        $.post("getDraftQuestions",{id:id,type:type},function(rs){ console.log(rs);
            var obj = $.parseJSON(rs);
            $('.multiple_question').val(obj.question);

            if(obj.question_image!='' && obj.question_image!=null){
                $('.quest_image').val(obj.question_image);

                var split = obj.question_image.split(',');
                var html = '';
                for(var i=0;i<split.length;i++){
                    if(split[i]!=''){
                        html += '<li data="'+split[i]+'" onclick="deleteWgImg(this,'+id+',&#039;'+split[i]+'&#039;)" class="mcimg"> <span class="wgIdlt">X</span>'+
                                    '<div class="wgImgCrop">'+
                                        '<img src="api/uploads/'+split[i]+'">'+
                                    '</div>'+
                                '</li>';
                    }
                }

                $('.mc_img_list').html(html);
                var existing_real_image = parseInt(parseInt($('.mc_img_list').find('.mcimg').length) + 1);
                $('.mc_img_list').find('.tmpImg').remove();
                //$('.mc_img_list').append(html);
                var placeHolderImg = '';
                for(var m=0;m<=(4 - existing_real_image);m++){
                    placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                            '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">'+
                                                '<img src="images/image.svg">'+
                                            '</div>'+
                                        '</li>';
                    $('.mc_img_list').append(placeHolderImg);
                }

                $('.mcImgBx').show();
            }else if(obj.audio!='' && obj.audio!=null){
                var html = '<audio controls>'+
                             '<source src="api/uploads/'+obj.audio+'" type="audio/wav">'+
                            '</audio>';
                $('.mcaudTag').html(html);
                $('.mcAudBx').show();
                $('.quest_audio').val(obj.audio);

            }else if(obj.video!='' && obj.video!=null){
                var vid = getId(obj.video);

               var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+vid+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
               $('.mcvdoTag').html(ifrmhtml);
               $('.mcVdoBx').show();
               $('.quest_video').val(obj.video);

            }

            //mcq option
                //option1
            if(obj.option1_type=='text'){
                $('.qst_ans_1').val(obj.option1);

                $("input[type='radio'][name='radio_1'][value='text']").prop("checked", true).change();
                //$("input[type='radio'][name='radio_1'][value='text']").change();
            }else if(obj.option1_type=='image'){
                $('.quest_image_option_1').val(obj.option1);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="api/uploads/'+obj.option1+'">'+
                                '</div>';
                $('.ansImgArea_1').html(html_prev).show();
                $("input[type='radio'][name='radio_1'][value='image']").prop("checked", true);

            }else if(obj.option1_type=='audio'){
                $('.quest_voice_option_1').val(obj.option1);
                $('.ansAud_1').html('<audio controls>'+
                                        '<source src="api/uploads/'+obj.option1+'" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='radio_1'][value='audio']").prop("checked", true);

            }

            //option2
            if(obj.option2_type=='text'){
                $('.qst_ans_2').val(obj.option2);

                $("input[type='radio'][name='radio_2'][value='text']").prop("checked", true);
                //$("input[type='radio'][name='radio_2'][value='text']").change();
            }else if(obj.option2_type=='image'){
                $('.quest_image_option_2').val(obj.option2);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="api/uploads/'+obj.option2+'">'+
                                '</div>';
                $('.ansImgArea_2').html(html_prev).show();
                $("input[type='radio'][name='radio_2'][value='image']").prop("checked", true);

            }else if(obj.option2_type=='audio'){
                $('.quest_voice_option_2').val(obj.option2);
                $('.ansAud_2').html('<audio controls>'+
                                        '<source src="api/uploads/'+obj.option2+'" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='radio_2'][value='audio']").prop("checked", true);

            }

            //option3
            if(obj.option3_type=='text'){
                $('.qst_ans_3').val(obj.option3);

                $("input[type='radio'][name='radio_3'][value='text']").prop("checked", true);
                $("input[type='radio'][name='radio_3'][value='text']").change();
            }else if(obj.option3_type=='image'){
                $('.quest_image_option_3').val(obj.option3);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="api/uploads/'+obj.option3+'">'+
                                '</div>';
                $('.ansImgArea_3').html(html_prev).show();
                $("input[type='radio'][name='radio_3'][value='image']").prop("checked", true);

            }else if(obj.option3_type=='audio'){
                $('.quest_voice_option_3').val(obj.option3);
                $('.ansAud_3').html('<audio controls>'+
                                        '<source src="api/uploads/'+obj.option3+'" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='radio_3'][value='audio']").prop("checked", true);
            }

            //option4
            if(obj.option4_type=='text'){
                $('.qst_ans_4').val(obj.option4);

                $("input[type='radio'][name='radio_4'][value='text']").prop("checked", true);
            }else if(obj.option4_type=='image'){
                $('.quest_image_option_4').val(obj.option4);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="api/uploads/'+obj.option4+'">'+
                                '</div>';
                $('.ansImgArea_4').html(html_prev).show();
                $("input[type='radio'][name='radio_4'][value='image']").prop("checked", true);
            }else if(obj.option4_type=='audio'){
                $('.quest_voice_option_4').val(obj.option4);
                $('.ansAud_4').html('<audio controls>'+
                                        '<source src="api/uploads/'+obj.option4+'.wav" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='radio_4'][value='audio']").prop("checked", true);
            }

            //select the answer
            $('input[type="checkbox"][id="mch'+obj.answer+'"]').prop("checked",true);

            //change button function
            $('.mcq_svBtn').attr("onclick","updateMultipleChoiceQuest("+obj.id+")")
            //show the pop
            openQuestPop('multiple_choice');
        });
    }else if(type=='dd'){
        $.post("getDraftDDQuestion",{id:id,type:type},function(rs){
            var obj = $.parseJSON(rs);

            $('.dd_question').val(obj.qsn_title);
            $('.quest_image').val(obj.qsn_img);
            $('.ddImageDisplay').html('<span class="wgIdlt" onclick="removeDDQsnImg()">X</span>'+
                                            '<img src="api/uploads/'+obj.qsn_img+'">');

            if(obj.target1!=''){
                $('.crpTrg1').html('<img src="api/uploads/'+obj.target1+'">');
                $('.target_1').val(obj.target1);
                $('.target_1_imgdata').val(obj.target_1_imgdata);
                $('.ddTrgtLi_1').addClass("added");
            }
            if(obj.target2!=''){
                $('.crpTrg2').html('<img src="api/uploads/'+obj.target2+'">');
                $('.target_2').val(obj.target2);
                $('.target_2_imgdata').val(obj.target_2_imgdata);
                $('.ddTrgtLi_2').addClass("added");
            }
            if(obj.target3!=''){
                $('.crpTrg3').html('<img src="api/uploads/'+obj.target3+'">');
                $('.target_3').val(obj.target3);
                $('.target_3_imgdata').val(obj.target_3_imgdata);
                $('.ddTrgtLi_3').addClass("added");
            }
            if(obj.target4!=''){
                $('.crpTrg4').html('<img src="api/uploads/'+obj.target4+'">');
                $('.target_4').val(obj.target4);
                $('.target_4_imgdata').val(obj.target_4_imgdata);
                $('.ddTrgtLi_4').addClass("added");
            }

            if(obj.target_ans_text1=='' || obj.target_ans_img1==''){
                $('.ddAnsTxt_1').slideUp();
                $('.ddTgAnsImg_1').slideUp();
            }else if(obj.target_ans_text1!=''){
                $('.ddItbtn_1').slideUp();

                $('.ddAnsTxt_1').slideDown();
                $('.ddTgAnsTxt_1').val(obj.target_ans_text1);
                $('.ddTgAnsImg_1').slideUp();
            }else if(obj.target_ans_img1!=''){
                $('.ddItbtn_1').slideUp();

                $('.ddAnsTxt_1').slideUp();
                $('.ddTgAnsImg_1').slideDown();
            }

            if(obj.target_ans_text2=='' || obj.target_ans_img2==''){
                $('.ddAnsTxt_2').slideUp();
                $('.ddTgAnsImg_2').slideUp();
            }else if(obj.target_ans_text2!=''){
                $('.ddItbtn_2').slideUp();

                $('.ddAnsTxt_2').slideDown();
                $('.ddTgAnsTxt_2').val(obj.target_ans_text2);
                $('.ddTgAnsImg_2').slideUp();
            }else if(obj.target_ans_img2!=''){
                $('.ddItbtn_2').slideUp();

                $('.ddAnsTxt_2').slideUp();
                $('.ddTgAnsImg_2').slideDown();
            }

            if(obj.target_ans_text3=='' || obj.target_ans_img3==''){
                $('.ddAnsTxt_3').slideUp();
                $('.ddTgAnsImg_3').slideUp();
            }else if(obj.target_ans_text3!=''){
                $('.ddItbtn_3').slideUp();

                $('.ddAnsTxt_3').slideDown();
                $('.ddTgAnsTxt_3').val(obj.target_ans_text3);
                $('.ddTgAnsImg_3').slideUp();
            }else if(obj.target_ans_img3!=''){
                $('.ddItbtn_3').slideUp();

                $('.ddAnsTxt_3').slideUp();
                $('.ddTgAnsImg_3').slideDown();
            }

            if(obj.target_ans_text4=='' || obj.target_ans_img4==''){
                $('.ddAnsTxt_4').slideUp();
                $('.ddTgAnsImg_4').slideUp();
            }else if(obj.target_ans_text4!=''){
                $('.ddItbtn_4').slideUp();

                $('.ddAnsTxt_4').slideDown();
                $('.ddTgAnsTxt_4').val(obj.target_ans_text4);
                $('.ddTgAnsImg_4').slideUp();
            }else if(obj.target_ans_img4!=''){
                $('.ddItbtn_4').slideUp();

                $('.ddAnsTxt_4').slideUp();
                $('.ddTgAnsImg_4').slideDown();
            }

            $('.ddsaveQsn').attr("onclick","updateDDQuestion("+id+")");
            openQuestPop('dd');

        });
    }
}

function editQuestionEdit(id,type){
    if(type=='word_game'){
        $.post(base_url+"getDraftQuestionsEdit",{id:id,type:type,wg:1},function(rs){
            var obj = $.parseJSON(rs);
            $('.word_game_title').val(obj.question);
            if(obj.question_image!='' && obj.question_image!=null){
                $('.quest_image').val(obj.question_image);

                var split = obj.question_image.split(',');
                var html = '';
                for(var i=0;i<split.length;i++){
                    if(split[i]!=''){
                        html += '<li data="'+split[i]+'" class="wgimg" > <span class="wgIdlt" onclick="deleteWgImgEdit(this,'+id+',&#039;'+split[i]+'&#039;)">X</span>'+
                                    '<div class="wgImgCrop">'+
                                        '<img src="../../api/uploads/'+split[i]+'">'+
                                    '</div>'+
                                '</li>';
                    }
                }

                $('.wg_img_list').html(html);

                var existing_real_image = parseInt(parseInt($('.wg_img_list').find('.wgimg').length) + 1);
                $('.wg_img_list').find('.tmpImg').remove();
                //$('.wg_img_list').append(html);
                var placeHolderImg = '';
                for(var m=0;m<=(4 - existing_real_image);m++){
                     placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                             '<div class="wgImgCrop" onclick="clickWgQImage()">'+
                                                 '<img src="'+base_url+'images/image.svg">'+
                                             '</div>'+
                                         '</li>';
                     $('.wg_img_list').append(placeHolderImg);
                }

                $('.wgImgBx').show();

            }else if(obj.audio!='' && obj.audio!=null){
                var html = '<audio controls>'+
                             '<source src="api/uploads/'+obj.audio+'" type="audio/wav">'+
                            '</audio>';
                $('.wgaudTag').html(html);
                $('.wgAudBx').show();
                $('.quest_audio').val(obj.audio);

            }else if(obj.link!='' && obj.link!=null){
                var vid = getId(obj.link);

               var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+vid+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
               $('.wgvdoTag').html(ifrmhtml);
               $('.wgVdoBx').show();
               $('.quest_video').val(obj.link);

            }
            var arabic = /[\u0600-\u06FF]/;
            if(arabic.test(obj.answer)){
                obj.answer = obj.answer.split("").reverse().join("");
            }

            $('.word_game_answer').val(obj.answer);

            $('.wgSvBtn').attr("onclick","updateWordGameQuestEdit("+id+")");
        })
        //$('.wgSvBtn').attr("onclick","updateWordGameQuestEdit("+id+")");
        openQuestPop('word_game');
    }else if(type=='mc'){ //multiple choice question
        $.post(base_url+"getDraftQuestionsEdit",{id:id,type:type,wg:0},function(rs){
            var obj = $.parseJSON(rs);
            $('.multiple_question').val(obj.question);

            if(obj.question_image!='' && obj.question_image!=null){
                $('.quest_image').val(obj.question_image);

                var split = obj.question_image.split(',');
                var html = '';
                for(var i=0;i<split.length;i++){
                    if(split[i]!=''){
                        html += '<li data="'+split[i]+'" onclick="deleteWgImg(this,'+id+',&#039;'+split[i]+'&#039;)" class="mcimg"> <span class="wgIdlt">X</span>'+
                                    '<div class="wgImgCrop">'+
                                        '<img src="../../api/uploads/'+split[i]+'">'+
                                    '</div>'+
                                '</li>';
                    }
                }

                $('.mc_img_list').html(html);
                var existing_real_image = parseInt(parseInt($('.mc_img_list').find('.mcimg').length) + 1);
                $('.mc_img_list').find('.tmpImg').remove();
                //$('.mc_img_list').append(html);
                var placeHolderImg = '';
                for(var m=0;m<=(4 - existing_real_image);m++){
                    placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                            '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">'+
                                                '<img src="'+base_url+'images/image.svg">'+
                                            '</div>'+
                                        '</li>';
                    $('.mc_img_list').append(placeHolderImg);
                }

                $('.mcImgBx').show();
            }else if(obj.audio!='' && obj.audio!=null){
                var html = '<audio controls>'+
                             '<source src="api/uploads/'+obj.audio+'" type="audio/wav">'+
                            '</audio>';
                $('.mcaudTag').html(html);
                $('.mcAudBx').show();
                $('.quest_audio').val(obj.audio);

            }else if(obj.video!='' && obj.video!=null){
                var vid = getId(obj.video);

               var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+vid+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
               $('.mcvdoTag').html(ifrmhtml);
               $('.mcVdoBx').show();
               $('.quest_video').val(obj.video);

            }

            //mcq option
                //option1
            if(obj.option1!=''){
                $('.qst_ans_1').val(obj.option1);

                $("input[type='radio'][name='sml_rdio'][value='text']").prop("checked", true).change();
                //$("input[type='radio'][name='radio_1'][value='text']").change();
            }else if(obj.option1_image!=''){
                $('.quest_image_option_1').val(obj.option1_image);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="../../api/uploads/'+obj.option1_image+'">'+
                                '</div>';
                $('.ansImgArea_1').html(html_prev).show();
                $("input[type='radio'][name='sml_rdio'][value='image']").prop("checked", true);
                $('.ansTxt_1').hide();
            }else if(obj.option1_audio!=''){
                $('.quest_voice_option_1').val(obj.option1_audio);
                $('.ansAud_1').html('<audio controls>'+
                                        '<source src="../../api/uploads/'+obj.option1_audio+'" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='sml_rdio'][value='audio']").prop("checked", true);
                $('.ansTxt_1').hide();
            }

            //option2
            if(obj.option2!=''){
                $('.qst_ans_2').val(obj.option2);

                $("input[type='radio'][name='sml_rdio2'][value='text']").prop("checked", true);
                //$("input[type='radio'][name='radio_2'][value='text']").change();
            }else if(obj.option2_image!=''){
                $('.quest_image_option_2').val(obj.option2_image);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="../../api/uploads/'+obj.option2_image+'">'+
                                '</div>';
                $('.ansImgArea_2').html(html_prev).show();
                $("input[type='radio'][name='sml_rdio2'][value='image']").prop("checked", true);
                $('.ansTxt_2').hide();
            }else if(obj.option2_audio!=''){
                $('.quest_voice_option_2').val(obj.option2_audio);
                $('.ansAud_2').html('<audio controls>'+
                                        '<source src="../../api/uploads/'+obj.option2_audio+'" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='sml_rdio2'][value='audio']").prop("checked", true);
                $('.ansTxt_2').hide();
            }

            //option3
            if(obj.option3!=''){
                $('.qst_ans_3').val(obj.option3);

                $("input[type='radio'][name='sml_rdio3'][value='text']").prop("checked", true);
                $("input[type='radio'][name='sml_rdio3'][value='text']").change();
            }else if(obj.option3_image!=''){
                $('.quest_image_option_3').val(obj.option3_image);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="../../api/uploads/'+obj.option3_image+'">'+
                                '</div>';
                $('.ansImgArea_3').html(html_prev).show();
                $("input[type='radio'][name='sml_rdio3'][value='image']").prop("checked", true);
                $('.ansTxt_3').hide();
            }else if(obj.option3_audio!=''){
                $('.quest_voice_option_3').val(obj.option3_audio);
                $('.ansAud_3').html('<audio controls>'+
                                        '<source src="../../api/uploads/'+obj.option3_audio+'" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='sml_rdio3'][value='audio']").prop("checked", true);
                $('.ansTxt_3').hide();
            }

            //option4
            if(obj.option4!=''){
                $('.qst_ans_4').val(obj.option4);

                $("input[type='radio'][name='sml_rdio4'][value='text']").prop("checked", true);
            }else if(obj.option4_image!=''){
                $('.quest_image_option_4').val(obj.option4_image);
                //preview image
                var html_prev = '<div class="imgFld">'+
                                    '<img src="../../api/uploads/'+obj.option4_image+'">'+
                                '</div>';
                $('.ansImgArea_4').html(html_prev).show();
                $("input[type='radio'][name='sml_rdio4'][value='image']").prop("checked", true);
                $('.ansTxt_4').hide();
            }else if(obj.option4_audio!=''){
                $('.quest_voice_option_4').val(obj.option4_audio);
                $('.ansAud_4').html('<audio controls>'+
                                        '<source src="../../api/uploads/'+obj.option4_audio+'.wav" type="audio/wav">'+
                                    '</audio>').show();
                $("input[type='radio'][name='sml_rdio4'][value='audio']").prop("checked", true);
                $('.ansTxt_4').hide();
            }

            //select the answer
            $('input[type="radio"][name="mch_check"][value="'+obj.answer+'"]').prop("checked",true);

            //change button function
            $('.mcq_svBtn').attr("onclick","updateMultipleChoiceQuestEdit("+obj.id+")")
            //show the pop
            openQuestPop('multiple_choice');
        });
    }else if(type=='dd'){
        $.post(base_url+"getDraftQuestionsEdit",{id:id,type:type,wg:0,dd:1},function(rs){
            console.log(rs);
            var obj = $.parseJSON(rs);
            $('.dd_question').val(obj.qsn_title);
            $('.quest_image').val(obj.qsn_img);
            $('.ddImageDisplay').html('<span class="wgIdlt" onclick="removeDDQsnImgEdit()">X</span>'+
                                        '<img src="../../api/uploads/'+obj.qsn_img+'">');
            if(obj.target1!='' && obj.target1!='undefined' && obj.target1!=null){
                $('.crpTrg1').html('<img src="../../api/uploads/'+obj.target1+'">');
                $('.ddTrgtLi_1').addClass('added');
                $('.target_1').val(obj.target1);
                $('.target_1_imgdata').val(obj.target_1_imgdata);
            }
            if(obj.target2!='' && obj.target2!='undefined' && obj.target2!=null){
                $('.crpTrg2').html('<img src="../../api/uploads/'+obj.target2+'">');
                $('.ddTrgtLi_2').addClass('added');
                $('.target_2').val(obj.target2);
                $('.target_2_imgdata').val(obj.target_2_imgdata);
            }
            if(obj.target3!='' && obj.target3!='undefined' && obj.target3!=null){
                $('.crpTrg3').html('<img src="../../api/uploads/'+obj.target3+'">');
                $('.ddTrgtLi_3').addClass('added');
                $('.target_3').val(obj.target3);
                $('.target_3_imgdata').val(obj.target_3_imgdata);
            }
            if(obj.target4!='' && obj.target4!='undefined' && obj.target4!=null){
                $('.crpTrg4').html('<img src="../../api/uploads/'+obj.target4+'">');
                $('.ddTrgtLi_4').addClass('added');
                $('.target_4').val(obj.target4);
                $('.target_4_imgdata').val(obj.target_4_imgdata);
            }
            if(obj.target_ans_text1!=''){
                $('.ddItbtn_1').slideUp();
                $('.ddAnsTxt_1').slideDown();
                $('.ddTgAnsTxt_1').val(obj.target_ans_text1);
            }
            if(obj.target_ans_text2!=''){
                $('.ddItbtn_2').slideUp();
                $('.ddAnsTxt_2').slideDown();
                $('.ddTgAnsTxt_2').val(obj.target_ans_text2);
            }
            if(obj.target_ans_text3!=''){
                $('.ddItbtn_3').slideUp();
                $('.ddAnsTxt_3').slideDown();
                $('.ddTgAnsTxt_3').val(obj.target_ans_text3);
            }
            if(obj.target_ans_text4!=''){
                $('.ddItbtn_4').slideUp();
                $('.ddAnsTxt_4').slideDown();
                $('.ddTgAnsTxt_4').val(obj.target_ans_text4);
            }
            if(obj.target_ans_img1!='' && obj.target_ans_img1!='undefined' && obj.target_ans_img1!=null){
                $('.ddItbtn_1').slideUp();
                $('.ddAnsImg_1').slideDown();
                $('.ddAnsImg_1').html('<div class="ddImgFld"> <div class="ddImgDlt" onclick="removeTargetAns(1)">X</div>'+
                                            '<div class="ddImFCrop"><img src="'+obj.target_ans_img1+'"></div>'+
                                        '</div>');
            }
            if(obj.target_ans_img2!='' && obj.target_ans_img2!='undefined' && obj.target_ans_img2!=null){
                $('.ddItbtn_2').slideUp();
                $('.ddAnsImg_2').slideDown();
                $('.ddAnsImg_2').html('<div class="ddImgFld"> <div class="ddImgDlt" onclick="removeTargetAns(2)">X</div>'+
                                            '<div class="ddImFCrop"><img src="'+obj.target_ans_img2+'"></div>'+
                                        '</div>');
            }
            if(obj.target_ans_img3!='' && obj.target_ans_img3!='undefined' && obj.target_ans_img3!=null){
                $('.ddItbtn_3').slideUp();
                $('.ddAnsImg_3').slideDown();
                $('.ddAnsImg_3').html('<div class="ddImgFld"> <div class="ddImgDlt" onclick="removeTargetAns(3)">X</div>'+
                                            '<div class="ddImFCrop"><img src="'+obj.target_ans_img3+'"></div>'+
                                        '</div>');
            }
            if(obj.target_ans_img4!='' && obj.target_ans_img4!='undefined' && obj.target_ans_img4!=null){
                $('.ddItbtn_4').slideUp();
                $('.ddAnsImg_4').slideDown();
                $('.ddAnsImg_4').html('<div class="ddImgFld"> <div class="ddImgDlt" onclick="removeTargetAns(4)">X</div>'+
                                            '<div class="ddImFCrop"><img src="'+obj.target_ans_img4+'"></div>'+
                                        '</div>');
            }

            $('#ddPop').fadeIn();
            $('.ddsaveQsn').attr("onclick","updateDDQuestionEdit("+id+")");
        });

    }
}

function deleteQuestionDraft(id){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {

                $.post("deleteDraft_question",{id:id},function(rs){
                    if(rs==1){
                        $('.quest_list_item').each(function(){
                            if($(this).attr("data")==id){
                                $(this).remove();
                            }
                        })
                    }else{
                        swal("Error","Something went wrong!","error");
                    }
                })
            }
        });
}

function deleteQuestionDraftEdit(id){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {


                $.post(base_url+"deleteDraft_questionEdit",{id:id},function(rs){ console.log(rs);
                    if(rs==1){
                        $('.quest_list_item').each(function(){
                            if($(this).attr("data")==id){
                                $(this).remove();
                            }
                        })
                    }else{
                        swal("Error","Something went wrong!","error");
                    }
                })
            }
        });
}

function editPopIntro(type,id){
    //localStorage.setItem("edit_dintro_id",id);
    $.post("getDraftIntro",{id:id},function(rs){ console.log(rs);
        var obj = $.parseJSON(rs);
        if(type=='title'){
            openIntroPop(type);
            $('.intro_title').val(obj.intro_data);
            $('.title_svbtn').attr("onclick","updateIntro('"+type+"',"+id+")");

        }else if(type=='video'){
            openIntroPop(type);
            $('.intro_video').val(obj.intro_data);
            $('.video_svbtn').attr("onclick","updateIntro('"+type+"',"+id+")");
        }else if(type=='paragraph'){
            openIntroPop(type);
            myEditor.setData(obj.intro_data);
            $('.intro_paragraph').val(obj.intro_data);
            $('.paragraph_svbtn').attr("onclick","updateIntro('"+type+"',"+id+")");
        }else if(type=='order'){
            openIntroPop(type);
            $('.order_url').val(obj.intro_data);
            $('.order_svbtn').attr("onclick","updateIntro('"+type+"',"+id+")");
        }
    })
}

function editPopIntroEdit(type,id){
    //localStorage.setItem("edit_dintro_id",id);
    $.post(base_url+"getDraftIntroEdit",{id:id},function(rs){ console.log(rs);
        var obj = $.parseJSON(rs);
        if(type=='title'){
            openIntroPop(type);
            $('.intro_title').val(obj.text);
            $('.title_svbtn').attr("onclick","updateIntroEdit('"+type+"',"+id+")");

        }else if(type=='video'){
            openIntroPop(type);
            $('.intro_video').val(obj.intro_video);
            $('.video_svbtn').attr("onclick","updateIntroEdit('"+type+"',"+id+")");
        }else if(type=='paragraph'){
            openIntroPop(type);

            /*myEditor.model.change( writer => {
                writer.insertElement(obj.intro_paragraph,myEditor.model.document.selection.getFirstPosition());
            } ); */
            myEditor.setData(obj.intro_paragraph);
            // const viewFragment = myEditor.data.processor.toView(obj.intro_paragraph );
            // const modelFragment = myEditor.data.toModel( viewFragment );

            // myEditor.model.insertContent( modelFragment, myEditor.model.document.selection );

            $('.intro_paragraph').val(obj.intro_data);
            $('.paragraph_svbtn').attr("onclick","updateIntroEdit('"+type+"',"+id+")");
        }else if(type=='order'){
            openIntroPop(type);
            $('.order_url').val(obj.intro_url);
            $('.order_svbtn').attr("onclick","updateIntroEdit('"+type+"',"+id+")");
        }
    })
}

function updateIntro(type,id){
    if(type=="title"){
        var title = $('.intro_title').val();
        $.post("updateDraftIntro",{data:title,id:id},function(rs){
            if(rs==1){
                $('.dragBx').each(function(){
                    if($(this).attr('data')==id){
                        $(this).find('.title_data').text(title);
                        $('.mask').click();
                        $('.title_svbtn').attr("onclick","saveDataPopIntro('title')");
                        //saveDataPopIntro('title')
                    }
                })
            }
        })
    }else if(type=='video'){
        var video = $('.intro_video').val();
        if(video==''){
            $('.canBtn').click();
        }else{
            $.post("updateDraftIntro",{data:video,id:id},function(rs){
                if(rs==1){
                    $('.dragBx').each(function(){
                        if($(this).attr('data')==id){
                            var urls = getId(video);
                            var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
                            $(this).find('.video_data').html(ifrmhtml);
                            $('.mask').click();
                            $('.video_svbtn').attr("onclick","saveDataPopIntro('video')");
                        }
                    });
                }
            });
        }
    }else if(type=='paragraph'){
        var content = myEditor.getData();
        $.post("updateDraftIntro",{data:content,id:id},function(rs){
            $('.dragBx').each(function(){
                if($(this).attr('data')==id){
                    $(this).find('.paragraph_data').html(content);
                    $('.mask').click();

                    $('.paragraph_svbtn').attr("onclick","saveDataPopIntro('paragraph')");
                }
            });
        })
    }else if(type=='order'){
        var url = $('.order_url').val();
        $.post("updateDraftIntro",{data:url,id:id},function(rs){
            if(rs==1){
                $('.dragBx').each(function(){
                    if($(this).attr('data')==id){
                        $(this).find('.title_data').text(url);
                        $('.mask').click();
                        $('.order_svbtn').attr("onclick","saveDataPopIntro('order')");
                        $('.order_url').val('');
                        //saveDataPopIntro('title')
                    }
                })
            }
        })
    }
}

function updateIntroEdit(type,id){
    if(type=="title"){
        var title = $('.intro_title').val();
        $.post(base_url+"updateDraftIntroEdit",{data:title,id:id,field:'text'},function(rs){
            if(rs==1){
                $('.dragBx').each(function(){
                    if($(this).attr('data')==id){
                        $(this).find('.title_data').text(title);
                        $('.mask').click();
                        $('.title_svbtn').attr("onclick","saveDataPopIntro('title')");
                        //saveDataPopIntro('title')
                    }
                })
            }
        })
    }else if(type=='video'){
        var video = $('.intro_video').val();
        if(video==''){
            $('.canBtn').click();
        }else{
            $.post(base_url+"updateDraftIntroEdit",{data:video,id:id,field:'intro_video'},function(rs){
                if(rs==1){
                    $('.dragBx').each(function(){
                        if($(this).attr('data')==id){
                            var urls = getId(video);
                            var ifrmhtml = '<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
                            $(this).find('.video_data').html(ifrmhtml);
                            $('.mask').click();
                            $('.video_svbtn').attr("onclick","saveDataPopIntro('video')");
                        }
                    });
                }
            });
        }
    }else if(type=='paragraph'){
        var content = myEditor.getData();
        //var content = $('.intro_paragraph').val();
        $.post(base_url+"updateDraftIntroEdit",{data:content,id:id,field:'intro_paragraph'},function(rs){ console.log(rs);
            $('.dragBx').each(function(){
                if($(this).attr('data')==id){
                    $(this).find('.paragraph_data').html(content);
                    $('.mask').click();
                    //$('.pcancel').click();
                    $('.paragraph_svbtn').attr("onclick","saveDataPopIntro('paragraph')");
                }
            });
        })
    }else if(type=='order'){
        var url = $('.order_url').val();
        $.post(base_url+"updateDraftIntroEdit",{data:url,id:id,field:'intro_url'},function(rs){ console.log(rs);
            if(rs==1){
                $('.dragBx').each(function(){
                    if($(this).attr('data')==id){
                        $(this).find('.title_data').text(url);
                        $('.mask').click();
                        $('.order_svbtn').attr("onclick","saveDataPopIntro('order')");
                        //saveDataPopIntro('title')
                    }
                })
            }
        })
    }
}

function deleteIntro(id){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {

                $.post("deleteDraftIntro",{id:id},function(rs){
                    if(rs==1){
                        $('.idnt_'+id).slideUp();
                    }else{
                        swal("Error","Sorry you do not have privilege to do so","success");
                    }
                })

        }
    })
}

function deleteIntroEdit(id){
    swal({
        title: "Are you sure?",
        text: "Are you willing to Delete?",
        icon: "warning",
        buttons: [
          'No, cancel it!',
          'Yes, I am sure!'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {

                $.post(base_url+"deleteDraftIntroEdit",{id:id},function(rs){ console.log(rs)
                    if(rs==1){
                        $('.idnt_'+id).slideUp();
                    }else{
                        swal("Error","Sorry you do not have privilege to do so","error");
                    }
                })

        }
    })
}

function saveExamDraft(step){
    if(step==1){
        //localStorage.removeItem("draft_exam_id");
        var exam_icon= $('.imgInp_hidden').val();
        var title = $('.exam_title').val();
        var preq_type = $('.preq_type').val();
        var preq_value = $('.preq_value').val();
        var is_random = 1;
        var is_retake = 0;
        var is_chat = 0;
        var time_limit = 0;
        var is_private=0;
        if($('input[type="checkbox"][name="is_random"]').is(":checked")){
            is_random = 1;
        }

        if($('input[type="checkbox"][name="is_retake"]').is(":checked")){
            is_retake = 1;
        }
        if($('input[type="checkbox"][name="is_chat"]').is(":checked")){
            is_chat = 1;
        }
        if($('input[type="checkbox"][class="timeLimit"]').is(":checked")){
            time_limit = $('.tmBx').val();
        }
        if($('input[type="checkbox"][name="is_private"]').is(":checked")){
            is_private = 1;
        }

        var form_data = new FormData();

        var know_id = localStorage.getItem("draft_exam_id");

        console.log("know id",know_id);
            var urls = '';
            if(know_id != null){
                urls = base_url+'updateExamDraft';
                form_data.append("exam_id",know_id);
            }else{
                urls = 'saveExamDraft/1';
            }


            form_data.append("exam_icon",exam_icon);
            form_data.append("exam_title",title);
            form_data.append("preq_type",preq_type);
            form_data.append("preq_value",preq_value);
            form_data.append("is_random",is_random);
            form_data.append("is_retake",is_retake);
            form_data.append("is_chat",is_chat);
            form_data.append("time_limit",time_limit);
            form_data.append("is_private",is_private);
            showLoader();
            $.ajax({
                url:urls,
                type: "POST",
                data: form_data,
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData:false,
                success: function(result){
                    var obj = $.parseJSON(result);
                    if(obj.status==1){
                        localStorage.setItem("draft_exam_id",obj.exam_id);

                        var href ='';
                        var base_url = $('.base_url').val();
                        href = base_url+'preview/'+obj.exam_id;
                        $('.preview_link').attr("href",href);

                            hideLoader();

                    }else{
                        swal("Error","Something went wrong!","error");
                    }
                }
            });


    }

}

function saveExamDraftEdit(step){ console.log("new function");
    if(step==1){
        //localStorage.removeItem("draft_exam_id");
        var exam_icon= $('.imgInp_hidden').val(); console.log("exam icon",exam_icon);
        var title = $('.exam_title').val();
        var preq_type = $('.preq_type').val();
        var preq_value = $('.preq_value').val();
        var is_random = 1;
        var is_retake = 0;
        var is_chat = 0;
        var time_limit = 0;
        var is_private=1;
        if($('input[type="checkbox"][name="is_random"]').is(":checked")){
            is_random = 0;
        }

        if($('input[type="checkbox"][name="is_retake"]').is(":checked")){
            is_retake = 1;
        }
        if($('input[type="checkbox"][name="is_chat"]').is(":checked")){
            is_chat = 1;
        }
        if($('input[type="checkbox"][class="timeLimit"]').is(":checked")){
            time_limit = $('.tmBx').val();
        }
        if($('input[type="checkbox"][name="is_private"]').is(":checked")){
            is_private = 0;
        }

        var form_data = new FormData();

        var know_id = localStorage.getItem("draft_exam_id");
        var urls = '';
        if(know_id != null){
            urls = base_url+'updateExamDraftEdit';
            form_data.append("exam_id",know_id);
        }else{
            urls = 'saveExamDraft/1';
        }


        form_data.append("icon",exam_icon);
        form_data.append("examname",title);
        form_data.append("prereq_type",preq_type);
        form_data.append("prereq_value",preq_value);
        form_data.append("is_order",is_random);
        form_data.append("exam_term",is_retake);
        form_data.append("chat_on",is_chat);
        form_data.append("time_limit",time_limit);
        form_data.append("access",is_private);
        form_data.append("exam_type",is_private);
        showLoader();
        $.ajax({
            url:urls,
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData:false,
            success: function(result){  hideLoader();
                var obj = $.parseJSON(result);
                if(obj.status==1){
                    localStorage.setItem("draft_exam_id",obj.exam_id);

                    var href ='';
                    var base_url = $('.base_url').val();
                    href = base_url+'previews/'+obj.exam_id;
                    $('.preview_link').attr("href",href);
                }else{
                    swal("Error","Something went wrong!","error");
                }
            }
        });


    }

}

function searchExamSuggestion(xmid){ console.log(xmid);
    if(xmid==''){
        $('.hintLst').slideUp();
    }else{
        $.post(base_url+"getExamList",{xmid:xmid},function(rs){ console.log("rs",rs);
            var obj = $.parseJSON(rs);
            if(obj.length==0){
                $('.hintLst').slideUp();
            }else{
                var html = '';
                for(i=0;i<obj.length;i++){
                    var show_id = parseInt(1000 + parseInt(obj[i].id));
                    html += '<li onclick="putInField(this)" data="'+show_id+'">('+show_id+')'+obj[i].examname+'</li>';
                }
                $('.hintLst').html(html);
                $('.hintLst').slideDown();
            }
        })
    }
}

function putInField(obj){
    var xm_id = $(obj).attr('data');
    $('.preq_value').val(xm_id);
    $('.hintLst').slideUp();
}

function generateTable(){
    var row = $('.IntrPopRow').val();
    var colm = $('.IntrPopClm').val();
    var table = $('<table></table>').addClass('foo');
    var table2 = $('<table></table>').addClass('foo');
    var width = (100 / colm);
    var m=0;
        for (var i = 0; i < row; i++) {
                rows = $('<tr ></tr>');
                rows2 = $('<tr ></tr>');
                for (var j = 0; j < colm; j++) {
                    var rowData = $('<td style="width:'+width+'%"><input onkeyup="generateText(this,this.value)" class="tblCl_'+m+'" data="'+m+'" type="text"></td>');
                    var rowData2 = $('<td style="width:'+width+'%" class="tblCl_'+m+'"></td>');
                    rows.append(rowData);
                    rows2.append(rowData2);
                    m++;
                }
                table.append(rows);
                table2.append(rows2);
            }
$('.dynamicTable').html(table);
$('.dynamicTable2').html(table2);
}

function generateText(obj,val){
    var class_nm = $(obj).attr("class"); console.log("class nm",class_nm);
    console.log("length",($('.'+class_nm).length));
    $('body .'+class_nm).each(function(m){
        //if(m==1){
            $( this).text(val);
        //}
    })
    //$('.'+class_nm)[0].text(val);
}

function saveExamAsDraft(){
    //just close the page and send to home page
    localStorage.removeItem("draft_exam_id");
    window.location = base_url+"savingXmDraftFinal";
/*
    swal({
        title: "Saved!",
        type: "success",
        text: "Your exam has been stored as draft"

     }).then(function() {
       if(condition){
          window.location = "home";
       }
     }); */
}

function publishExam(){ //swal("warning","Men at works!","warning"); publishExam
    var exam_id = localStorage.getItem("draft_exam_id");
    if(exam_id==null){
        swal("Error","Something went wrong!","error");
    }else{
        showLoader();
        $.post("publishDraftExam",{exam_id:exam_id},function(rs){ console.log(rs); hideLoader();
            var obj = $.parseJSON(rs);
            if(obj.status==1){
                swal({
                    title: obj.name,
                    type: "success",
                    text: "Your exam has been published. Exam id : "+obj.show_exam_id

                 }).then(function(condition) {
                   if(condition){
                       showLoader();
                    window.onbeforeunload = null;
                      window.location = "home";
                   }
                 });
            }else{
                swal("Error","Something went wrong!","error");
            }
        })
    }

}

function introPositonUp(id){ showLoader();
    $.post(base_url+"updateIntroPosition",{id:id,up:1},function(rs){ hideLoader();
        if(rs==1){
            $("#sortable").load(location.href + " #sortable");
        }
    })
}

function introPositonDown(id){ showLoader();
    $.post(base_url+"updateIntroPosition",{id:id,up:0},function(rs){ hideLoader();
        if(rs==1){
            $("#sortable").load(location.href + " #sortable");
        }
    })
}


function introPositonUpQsn(id){ showLoader();
    $.post(base_url+"updateIntroPositionQsn",{id:id,up:1},function(rs){ hideLoader();
        if(rs==1){
            $("#sortableQsn").load(location.href + " #sortableQsn");
        }
    })
}

function introPositonDownQsn(id){ showLoader();
    $.post(base_url+"updateIntroPositionQsn",{id:id,up:0},function(rs){ hideLoader();
        if(rs==1){
            $("#sortableQsn").load(location.href + " #sortableQsn");
        }
    })
}

function DraftIntroPosition(id,op){
    showLoader();
       $.post(base_url+"updateDraftIntroPosition",{id:id,op:op},function(rs){ hideLoader();
           if(rs==1){
                $("#sortable").load(location.href + " #sortable");
           }
       })

}

function startCountRecording(){
    var sec=0;
   timer =  setInterval(function(){
        sec = parseInt(parseInt(sec) + 1);
        if(sec<60){
            if (sec < 10){
                $('.aud_timer').text('00 : 0'+sec);
            }else{
                $('.aud_timer').text('00 : '+sec);
            }

        }else if(sec==60){
            $('.aud_timer').text('01 : 00');
        }else{
            var min = parseInt(parseInt(sec) / 60);
            secs = (sec - (min * 60 ));
            var show_min=min;
            if(min < 10){
                show_min = '0'+min;
            }
            var show_sec = secs;
            if(secs < 10){
                show_sec = '0'+secs;
            }
            $('.aud_timer').text(show_min+' : '+show_sec);
        }
    },1000);

}

function submitProjectByStudent(xmid){
    //var project_text = $('.project_text').val();
    var project_text = myEditor.getData();
    var ptype = $('.psubmit_type').val();
    var pvalue = $('.psubmit_value').val();
    var licens_id = $('.licens_id').val();
    showLoader();
    $.post(base_url+"submitStudentProject",{examid:xmid,project_text:project_text,psubmit_type:ptype,psubmit_value:pvalue,licens_id:licens_id},function(rs){ hideLoader();
        if(rs==1){
            swal({
                title: "Success",
                text: "Project submitted successfully",
                icon: "success",
                buttons: [
                  'No, cancel it!',
                  'Yes, I am sure!'
                ],
                dangerMode: false,
              }).then(function(isConfirm) {
                if (isConfirm) {
                    window.location.href=base_url;
                }else{
                    window.location.href=base_url;
                }
            });
        }
    })
}

function OpenCroperPop(class_name='',imgdata){ console.log("clikcing here crop open");
    if(class_name==1){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {
            //   console.log(event.detail.x);
            //   console.log(event.detail.y);
            //   console.log(event.detail.width);
            //   console.log(event.detail.height);
            //   console.log(event.detail.rotate);
            //   console.log(event.detail.scaleX);
            //   console.log(event.detail.scaleY);
            },
        });
    }else if(class_name=='quest_icon'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        //$('.cropDImage').attr("onclick","cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='intro_image'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage('intro_image')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='mc_qst_img'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage('mc_qst_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='ansImg_1' || class_name=='ansImg_2' || class_name=='ansImg_3' || class_name=='ansImg_4'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage('"+class_name+"')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='wg_img'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage('wg_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='pr_img'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='project_submit'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage('project_submit')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }

}

function OpenCroperPopEdit(class_name,imgdata){ console.log("class_name",class_name);
    // $('.cropperJsPop').show();
    // var shimage = $('.quest_image').val();
    // $('.cropperJs').attr("src","../../api/uploads/"+shimage);

    // $('.cropDImage').attr("onclick","cropImageEdit('"+class_name+"')");

    // const image = document.getElementById('crpImg');
    //  cropper = new Cropper(image, {
    //     initialAspectRatio: 1,
    //     crop(event) {
    //       /*console.log(event.detail.x);
    //       console.log(event.detail.y);
    //       console.log(event.detail.width);
    //       console.log(event.detail.height);
    //       console.log(event.detail.rotate);
    //       console.log(event.detail.scaleX);
    //       console.log(event.detail.scaleY);*/
    //     },
    //   });

    if(class_name==1){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {
            //   console.log(event.detail.x);
            //   console.log(event.detail.y);
            //   console.log(event.detail.width);
            //   console.log(event.detail.height);
            //   console.log(event.detail.rotate);
            //   console.log(event.detail.scaleX);
            //   console.log(event.detail.scaleY);
            },
        });
    }else if(class_name=='quest_icon'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        //$('.cropDImage').attr("onclick","cropImage()");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='intro_image'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('intro_image')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='mc_qst_img'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('mc_qst_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='ansImg_1' || class_name=='ansImg_2' || class_name=='ansImg_3' || class_name=='ansImg_4'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('"+class_name+"')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='wg_img'){ console.log("here wg",imgdata);
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('wg_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='pr_img'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='cert'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('pr_img')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }else if(class_name=='profile_pic_edit'){
        $('.cropperJsPop').show();
        //var shimage = $('.quest_image').val();
        $('.cropperJs').attr("src",imgdata);

        $('.cropDImage').attr("onclick","cropImageEdit('profile_pic_edit')");

        const image = document.getElementById('crpImg');
        cropper = new Cropper(image, {

            aspectRatio: 2/2,
            zoomOnWheel: false,

            crop(event) {

            },
        });
    }

}

function cropImage(class_name=''){
    showLoader();
    var img = document.getElementById("crpImg");

    canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
      });

      var crop_image_data = cropper.getData();

     // var exam_id = localStorage.getItem("draft_exam_id");

    canvas.toBlob(function(blob) {
    url = URL.createObjectURL(blob);
    var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
        var base64data = reader.result;
        console.log("cropping img",crop_image_data);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url+"saveTmpImgs",
            data: {image: base64data,img_data:crop_image_data.toString(),class_name:class_name},
            success: function(data){
                console.log(data); hideLoader();

                if(class_name==''){ //group page
                    $('.group_image').val(data.filename);

                    $('#blah').attr('src',base_url+'api/uploads/'+data.filename);
                }else if(class_name=='quest_icon'){
                    $('.imgInp_hidden').val(data.filename);
                    $('#blah').attr('src',base_url+'api/uploads/exam/'+data.filename);
                }else if(class_name=='intro_image'){

                    var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
                    if( d_exam_id ==null){
                        swal("Error","Please fill the First page","error");
                    }else{
                        var type='image';
                        showLoader();
                        var form_data = new FormData();
                        form_data.append("intro_image_raft",data.filename);
                        form_data.append("type",type);
                        form_data.append("draft_exam_id",d_exam_id);
                        $.ajax({
                            url: base_url+"saveExamIntroDraft",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader(); console.log(rs);
                                var obj = $.parseJSON(rs);
                                rs = obj.id;

                                var html = ' <li data-post-id="'+rs+'">'+
                                '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+

                                                '<div class="drgHdr">'+
                                                    '<div class="drgAction">'+
                                                        '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                                                        '<!--<span class="drgEdt">EDIT</span>-->'+
                                                    '</div>'+
                                                    '<div class="drgT1">'+
                                                        '<img src="images/img.svg"/>'+
                                                        '<span>Image</span>'+
                                                    '</div>'+
                                                    '<div class="drgAction drgAction2">'+
                                                        '<span class="drgUpar" >Uparrow</span>'+
                                                        '<span class="drgDwar" >Downarrow</span>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="drgWhte">'+
                                                    '<div class="drgimg center">'+
                                                        '<img src="api/uploads/'+obj.data+'"/>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div></li>';
                                    $('.quest_subject').append(html);
                                        $('#mask').click();
                            }
                        });
                    }
                }else if(class_name=='mc_qst_img'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: "saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                        var html = '<li class="wgimg mcimg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;mc&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                                        '<div class="wgImgCrop"> '+
                                            '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                        '</div>'+
                                    '</li>';
                                    var existing_real_image = parseInt(parseInt($('.mc_img_list').find('.mcimg').length) + 1);
                                $('.mc_img_list').find('.tmpImg').remove();
                                $('.mc_img_list').append(html);
                                var placeHolderImg = '';
                                for(var m=0;m<(4 - existing_real_image);m++){
                                        placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                                                '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">'+
                                                                    '<img src="images/image.svg">'+
                                                                '</div>'+
                                                            '</li>';
                                        $('.mc_img_list').append(placeHolderImg);
                                }

                                // $('.mc_img_list').append(html);
                                    $('.mcImgBx').slideDown();

                                    var quest_img = $('.quest_image').val();
                                    quest_img = quest_img+','+obj.filename;
                                    $('.quest_image').val(quest_img);
                                    // if image choose make blank other two
                                    $('.quest_video').val(''); $('.mcvdoTag').html(''); $('.mcVdoBx').slideUp();
                                    $('.quest_audio').val(''); $('.mcaudTag').html(''); $('.mcAudBx').slideUp();
                        }
                    });
                }else if(class_name=='ansImg_1'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img"); //just to set in php not to run upload script direct save the name of images
                    showLoader();
                    var index = 1;
                    $.ajax({
                            url: "saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs); console.log("ans img",obj);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    console.log($('.ansImgArea_'+index).length);
                                    //reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                    '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                  //  }

                                   // reader.readAsDataURL(input.files[0]);
                               // }
                            }
                        });
                }else if(class_name=='ansImg_2'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img"); //just to set in php not to run upload script direct save the name of images
                    showLoader();
                    var index = 2;
                    $.ajax({
                            url: "saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs); console.log("ans img",obj);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    console.log($('.ansImgArea_'+index).length);
                                    //reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                    '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                  //  }

                                   // reader.readAsDataURL(input.files[0]);
                               // }
                            }
                        });
                }else if(class_name=='ansImg_3'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img"); //just to set in php not to run upload script direct save the name of images
                    showLoader();
                    var index = 3;
                    $.ajax({
                            url: "saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs); console.log("ans img",obj);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    console.log($('.ansImgArea_'+index).length);
                                    //reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                    '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                  //  }

                                   // reader.readAsDataURL(input.files[0]);
                               // }
                            }
                        });
                }else if(class_name=='ansImg_4'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img"); //just to set in php not to run upload script direct save the name of images
                    showLoader();
                    var index = 4;
                    $.ajax({
                            url: "saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs); console.log("ans img",obj);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    console.log($('.ansImgArea_'+index).length);
                                    //reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                    '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                  //  }

                                   // reader.readAsDataURL(input.files[0]);
                               // }
                            }
                        });
                }else if(class_name=='wg_img'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: "saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            var html = '<li class="wgimg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;wg&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                                            '<div class="wgImgCrop"> '+
                                                '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                            '</div>'+
                                        '</li>';
                                        //
                                    var existing_real_image = parseInt(parseInt($('.wg_img_list').find('.wgimg').length) + 1);
                                    $('.wg_img_list').find('.tmpImg').remove();
                                    $('.wg_img_list').append(html);
                                    var placeHolderImg = '';
                                    for(var m=0;m<(4 - existing_real_image);m++){
                                            placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                                                    '<div class="wgImgCrop" onclick="clickWgQImage()">'+
                                                                        '<img src="images/image.svg">'+
                                                                    '</div>'+
                                                                '</li>';
                                            $('.wg_img_list').append(placeHolderImg);
                                    }

                                        $('.wgImgBx').slideDown();
                                    var quest_img = $('.quest_image').val();
                                    quest_img = quest_img+','+obj.filename;
                                    $('.quest_image').val(quest_img);

                                    // if image choose make blank other two
                                    $('.quest_video').val('');
                                    $('.quest_audio').val('');
                        }
                    });

                    $('#showWgImg').val('');
                }else if(class_name=='pr_img'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: "saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            var html = '<li class="wgimg primg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;pr&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                                            '<div class="wgImgCrop"> '+
                                                '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                            '</div>'+
                                        '</li>';
                                        $('.pr_img_list').append(html);
                                        $('.prImgBx').slideDown();

                                        var quest_img = $('.quest_image').val();
                                        quest_img = quest_img+','+obj.filename;
                                        $('.quest_image').val(quest_img);
                                        // if image choose make blank other two
                                        $('.quest_video').val(''); $('.prvdoTag').html(''); $('.prVdoBx').slideUp();
                                        $('.quest_audio').val(''); $('.praudTag').html(''); $('.prAudBx').slideUp();

                        }
                    });

                    $('#showPrImg').val('');
                }else if(class_name=='project_submit'){

                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var url = window.location.href;
                    var split_url = url.split('/');

                    var d_exam_id = split_url[split_url.length - 1];

                    $('.project_data').html('<img src="'+base_url+'api/uploads/'+data.filename+'" />');
                    $('.project_data').slideDown();
                    console.log("xmid",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    showLoader();

                    $.ajax({
                    url: base_url+"saveWgTmpImage",
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(rs){  hideLoader(); console.log(rs);
                        var obj = $.parseJSON(rs);

                        $('.psubmit_type').val('image');
                        $('.psubmit_value').val(obj.filename);
                        //$('.project_data').html('<img src="../../../api/uploads/'+obj.filename+'" />');



                    }
                });
                }


                $('.cropperJsPop').hide();


                cropper.destroy();
            },
            error:function(){
                console.log("Error ajax call");
            }
            });
        }
    });


    /*
    var canvas = document.getElementById('canvasArea');
    var context = canvas.getContext('2d');

    var c=document.getElementById("canvasArea");
    var ctx=c.getContext("2d");


    ctx.drawImage(img,0,0);
//console.log("x value",crop_image_data.x);
    context.beginPath();
    context.rect(crop_image_data.x, crop_image_data.y, crop_image_data.width, crop_image_data.height);
    //context.globalAlpha = 0;
    context.fillStyle = 'white';
    context.fill();
    context.lineWidth = 7;
    context.strokeStyle = 'white';

    context.stroke();

    var url_img = canvas.toDataURL();
    //console.log(url_img);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url+"saveDDCropImg",
        data: {image: url_img,exam_id:exam_id},
        success: function(data){ hideLoader();
            console.log(data);

            $('.cropperJsPop').hide();

            $('.quest_image').val(data.filename);
            $('.ddImageDisplay').html('<span onclick="clickWgQImage(&#039;dd&#039;)" class="ddImageDisplay"><span class="wgIdlt" onclick="removeDDQsnImg()">X</span><img src="api/uploads/'+data.filename+'"></span>');
        }
        }); */
}

function cropImageEdit(class_name,index){

    showLoader();
    var img = document.getElementById("crpImg");

    canvas = cropper.getCroppedCanvas({
        width: 160,
        height: 160,
      });

      var crop_image_data = cropper.getData();

     // var exam_id = localStorage.getItem("draft_exam_id");

    canvas.toBlob(function(blob) {
    url = URL.createObjectURL(blob);
    var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
        var base64data = reader.result;
        console.log("cropping img",crop_image_data);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url+"saveTmpImgs",
            data: {image: base64data,img_data:crop_image_data.toString(),class_name:class_name},
            success: function(data){
                console.log(data); hideLoader();

                if(class_name==''){ //group page
                    $('.group_image').val(data.filename);

                    $('#blah').attr('src',base_url+'api/uploads/'+data.filename);
                }else if(class_name=='quest_icon'){
                    $('.imgInp_hidden').val(data.filename);
                    $('#blah').attr('src',base_url+'api/uploads/exam/'+data.filename);
                }else if(class_name=='intro_image'){

                        showLoader();
                        var d_exam_id = localStorage.getItem("draft_exam_id");

                        var form_data = new FormData();
                        form_data.append("intro_image_raft",data.filename);
                        form_data.append("type","image");
                        form_data.append("draft_exam_id",d_exam_id);
                        $.ajax({
                            url: base_url+"saveExamIntroDraftEdit",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){ console.log(rs);  hideLoader();
                                var obj = $.parseJSON(rs);
                                rs = obj.id;

                                var html = ' <li data-post-id="'+rs+'">'+
                                '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+

                                                '<div class="drgHdr">'+
                                                    '<div class="drgAction">'+
                                                        '<span class="drgDel" onclick="deleteIntroEdit('+rs+')">DELETE</span>'+
                                                        '<!--<span class="drgEdt">EDIT</span>-->'+
                                                    '</div>'+
                                                    '<div class="drgT1">'+
                                                        '<img src="'+base_url+'images/img.svg"/>'+
                                                        '<span>Image</span>'+
                                                    '</div>'+
                                                    '<div class="drgAction drgAction2">'+
                                                        '<span class="drgUpar" onclick="introPositonDown('+rs+')">Uparrow</span>'+
                                                        '<span class="drgDwar" onclick="introPositonUp('+rs+')">Downarrow</span>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="drgWhte">'+
                                                    '<div class="drgimg center">'+
                                                        '<img src="../../api/uploads/'+obj.data+'"/>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div></li>';
                                    $('.quest_subject').append(html);
                                        $('#mask').click();
                            }
                        });

                    // var d_exam_id = localStorage.getItem("draft_exam_id"); console.log(d_exam_id);
                    // if( d_exam_id ==null){
                    //     swal("Error","Please fill the First page","error");
                    // }else{
                    //     var type='image';
                    //     showLoader();
                    //     var form_data = new FormData();
                    //     form_data.append("intro_image_raft",data.filename);
                    //     form_data.append("type",type);
                    //     form_data.append("draft_exam_id",d_exam_id);
                    //     $.ajax({
                    //         url: base_url+"saveExamIntroDraft",
                    //         type: "POST",
                    //         data: form_data,
                    //         enctype: 'multipart/form-data',
                    //         contentType: false,
                    //         cache: false,
                    //         processData:false,
                    //         success: function(rs){  hideLoader(); console.log(rs);
                    //             var obj = $.parseJSON(rs);
                    //             rs = obj.id;

                    //             var html = ' <li data-post-id="'+rs+'">'+
                    //             '<div class="dragBx idnt_'+rs+'" data="'+rs+'">'+

                    //                             '<div class="drgHdr">'+
                    //                                 '<div class="drgAction">'+
                    //                                     '<span class="drgDel" onclick="deleteIntro('+rs+')">DELETE</span>'+
                    //                                     '<!--<span class="drgEdt">EDIT</span>-->'+
                    //                                 '</div>'+
                    //                                 '<div class="drgT1">'+
                    //                                     '<img src="images/img.svg"/>'+
                    //                                     '<span>Image</span>'+
                    //                                 '</div>'+
                    //                                 '<div class="drgAction drgAction2">'+
                    //                                     '<span class="drgUpar" >Uparrow</span>'+
                    //                                     '<span class="drgDwar" >Downarrow</span>'+
                    //                                 '</div>'+
                    //                             '</div>'+
                    //                             '<div class="drgWhte">'+
                    //                                 '<div class="drgimg center">'+
                    //                                     '<img src="api/uploads/'+obj.data+'"/>'+
                    //                                 '</div>'+
                    //                             '</div>'+
                    //                         '</div></li>';
                    //                 $('.quest_subject').append(html);
                    //                     $('#mask').click();
                    //         }
                    //     });
                    // }
                }else if(class_name=='mc_qst_img'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    showLoader();

                    $.ajax({
                    url: base_url+"saveWgTmpImage",
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            var html = '<li class="wgimg mcimg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;mc&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                                        '<div class="wgImgCrop"> '+
                                            '<img src="'+base_url+'api/uploads/'+obj.filename+'">'+
                                        '</div>'+
                                    '</li>';
                                    var existing_real_image = parseInt(parseInt($('.mc_img_list').find('.mcimg').length) + 1);
                                $('.mc_img_list').find('.tmpImg').remove();
                                $('.mc_img_list').append(html);
                                var placeHolderImg = '';
                                for(var m=0;m<(4 - existing_real_image);m++){
                                        placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                                                '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">'+
                                                                    '<img src="'+base_url+'images/image.svg">'+
                                                                '</div>'+
                                                            '</li>';
                                        $('.mc_img_list').append(placeHolderImg);
                                }

                                // $('.mc_img_list').append(html);
                                    $('.mcImgBx').slideDown();

                                    var quest_img = $('.quest_image').val();
                                    quest_img = quest_img+','+obj.filename;
                                    $('.quest_image').val(quest_img);
                                    // if image choose make blank other two
                                    $('.quest_video').val(''); $('.mcvdoTag').html(''); $('.mcVdoBx').slideUp();
                                    $('.quest_audio').val(''); $('.mcaudTag').html(''); $('.mcAudBx').slideUp();
                        }
                    });
                }else if(class_name=='ansImg_1'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img");
                    var index=1
                    showLoader();
                    $.ajax({
                            url: base_url+"saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    // console.log($('.ansImgArea_'+index).length);
                                    // reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                                '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                    }

                            //         reader.readAsDataURL(input.files[0]);
                            //     }
                            // }
                        });
                }else if(class_name=='ansImg_2'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img");
                    var index=2;
                    showLoader();
                    $.ajax({
                            url: base_url+"saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    // console.log($('.ansImgArea_'+index).length);
                                    // reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                            '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                                '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                    }

                            //         reader.readAsDataURL(input.files[0]);
                            //     }
                            // }
                        });
                }else if(class_name=='ansImg_3'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img");
                    var index=3;
                    showLoader();
                    $.ajax({
                            url: base_url+"saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    // console.log($('.ansImgArea_'+index).length);
                                    // reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                                    '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                                '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                    }

                            //         reader.readAsDataURL(input.files[0]);
                            //     }
                            // }
                        });
                }else if(class_name=='ansImg_4'){
                    var form_data = new FormData();
                    var d_exam_id = localStorage.getItem("draft_exam_id");

                    form_data.append("exam_id",d_exam_id);
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("type","mc_qst_img");
                    var index=4;
                    showLoader();
                    $.ajax({
                            url: base_url+"saveWgTmpImage",
                            type: "POST",
                            data: form_data,
                            enctype: 'multipart/form-data',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: function(rs){  hideLoader();
                                    var obj = $.parseJSON(rs);
                                    // if (input.files && input.files[0]) {
                                    // var reader = new FileReader();
                                    // console.log($('.ansImgArea_'+index).length);
                                    // reader.onload = function (e) {
                                        //$('#blah').attr('src', e.target.result);
                                        var html = '<div class="imgFld">'+
                                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                                                '</div>';

                                        $('.ansImgArea_'+index).html(html);
                                        $('.ansImgArea_'+index).show();
                                        $('.ansTxt_'+index).hide();
                                        $('.ansAud_'+index).hide();

                                        //save image name
                                        $('.quest_image_option_'+index).val(obj.filename);
                                    }

                                //     reader.readAsDataURL(input.files[0]);
                                // }
                            //}
                        });
                }else if(class_name=='wg_img'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: base_url+"saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            var html = '<li class="wgimg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;wg&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                                    '<div class="wgImgCrop"> '+
                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                    '</div>'+
                                '</li>';
                                //
                               var existing_real_image = parseInt(parseInt($('.wg_img_list').find('.wgimg').length) + 1);
                               $('.wg_img_list').find('.tmpImg').remove();
                               $('.wg_img_list').append(html);
                               var placeHolderImg = '';
                               for(var m=0;m<(4 - existing_real_image);m++){
                                    placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>'+
                                                            '<div class="wgImgCrop" onclick="clickWgQImage()">'+
                                                                '<img src="'+base_url+'images/image.svg">'+
                                                            '</div>'+
                                                        '</li>';
                                    $('.wg_img_list').append(placeHolderImg);
                               }

                                $('.wgImgBx').slideDown();
                            var quest_img = $('.quest_image').val();
                            quest_img = quest_img+','+obj.filename;
                            $('.quest_image').val(quest_img);

                            // if image choose make blank other two
                            $('.quest_video').val('');
                            $('.quest_audio').val('');
                        }
                    });

                    $('#showWgImg').val('');
                }else if(class_name=='pr_img'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: base_url+"saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            var html = '<li class="wgimg primg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;pr&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                                    '<div class="wgImgCrop"> '+
                                        '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                                    '</div>'+
                                '</li>';
                                $('.pr_img_list').append(html);
                                $('.prImgBx').slideDown();

                                var quest_img = $('.quest_image').val();
                                quest_img = quest_img+','+obj.filename;
                                $('.quest_image').val(quest_img);

                        }
                    });

                    $('#showPrImg').val('');
                }else if(class_name=='cert'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: base_url+"saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            // var html = '<li class="wgimg primg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;pr&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                            //         '<div class="wgImgCrop"> '+
                            //             '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                            //         '</div>'+
                            //     '</li>';
                            //     $('.pr_img_list').append(html);
                            //     $('.prImgBx').slideDown();
                            $('.cert_img').attr("src",base_url+"api/uploads/"+data.filename);


                                $('.sponsor_img').val(obj.filename);

                        }
                    });

                    $('#showPrImg').val('');
                }else if(class_name=='profile_pic_edit'){
                    //$('.wg_tmp_img').val(obj.data);
                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    form_data.append("wg_tmp_img",data.filename);
                    form_data.append("exam_id",d_exam_id);
                    form_data.append("type","mc_qst_img");
                    $.ajax({
                        url: base_url+"saveWgTmpImage",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(rs){  hideLoader();
                            var obj = $.parseJSON(rs);
                            // var html = '<li class="wgimg primg" data="'+obj.id+'"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;pr&#039;,&#039;'+obj.filename+'&#039;,'+obj.id+')">X</span>'+
                            //         '<div class="wgImgCrop"> '+
                            //             '<img src="'+base_url+'api/uploads/'+data.filename+'">'+
                            //         '</div>'+
                            //     '</li>';
                            //     $('.pr_img_list').append(html);
                            //     $('.prImgBx').slideDown();
                            $('#target').attr("src",base_url+"api/uploads/"+data.filename);


                                $('.profile_img').val(obj.filename);

                        }
                    });

                    $('#showPrImg').val('');
                }


                $('.cropperJsPop').hide();


                cropper.destroy();
            },
            error:function(){
                console.log("Error ajax call");
            }
            });
        }
    });

    // showLoader();
    // var img = document.getElementById("crpImg");

    // canvas = cropper.getCroppedCanvas({
    //     width: 160,
    //     height: 160,
    //   });

    //   var crop_image_data = cropper.getData();

    //   var exam_id = localStorage.getItem("draft_exam_id");

    // canvas.toBlob(function(blob) {
    // url = URL.createObjectURL(blob);
    // var reader = new FileReader();
    //     reader.readAsDataURL(blob);
    //     reader.onloadend = function() {
    //     var base64data = reader.result;
    //     console.log("cropping img",crop_image_data);
    //     $.ajax({
    //         type: "POST",
    //         dataType: "json",
    //         url: base_url+"saveDDCropImg",
    //         data: {image: base64data,img_data:crop_image_data.toString(),exam_id:exam_id},
    //         success: function(data){
    //             console.log(data); hideLoader();
    //             //$modal.modal('hide');
    //             $('.target_'+class_name).val(data.filename);
    //             $('.target_'+class_name+'_imgdata').val(JSON.stringify(crop_image_data));
    //             $('.crpTrg'+class_name).html('<img src="../../api/uploads/'+data.filename+'">')
    //             $('.cropperJsPop').hide();

    //             $('.ddTrgtLi_'+class_name).addClass("added");
    //             cropper.destroy();
    //         }
    //         });
    //     }
    // });


    /*
    var canvas = document.getElementById('canvasArea');
    var context = canvas.getContext('2d');

    var c=document.getElementById("canvasArea");
    var ctx=c.getContext("2d");


    ctx.drawImage(img,0,0);
//console.log("x value",crop_image_data.x);
    context.beginPath();
    context.rect(crop_image_data.x, crop_image_data.y, crop_image_data.width, crop_image_data.height);
    //context.globalAlpha = 0;
    context.fillStyle = 'white';
    context.fill();
    context.lineWidth = 7;
    context.strokeStyle = 'white';

    context.stroke();

    var url_img = canvas.toDataURL();
    //console.log(url_img);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url+"saveDDCropImg",
        data: {image: url_img,exam_id:exam_id},
        success: function(data){ hideLoader();
            console.log(data);

            $('.cropperJsPop').hide();

            $('.quest_image').val(data.filename);
            $('.ddImageDisplay').html('<span onclick="clickWgQImage(&#039;dd&#039;)" class="ddImageDisplay"><span class="wgIdlt" onclick="removeDDQsnImg()">X</span><img src="api/uploads/'+data.filename+'"></span>');
        }
        }); */
}

function showHistryReward(id){
    showLoader();
    $.post(base_url+"getRewardHistroy",{id:id},function(rs){ console.log(rs);
        var obj = $.parseJSON(rs);

        // var span = document.createElement("span");
        // if(obj.re_type==0){
        //     span.innerHTML = "Bluetooth";
        // }else if(obj.re_type==1){
        //     span.innerHTML = obj.reward;
        // }else if(obj.re_type==2){
        //     span.innerHTML = '<img height="100px" width="100px" src="../api/uploads/'+obj.reward+'"/>';
        // }else if(obj.re_type==3){
        //     var urls = getId(obj.reward);
        //     span.innerHTML = '<iframe src="https://www.youtube.com/embed/'+urls+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>';
        // }else if(obj.re_type==4){
        //     span.innerHTML = 'Certificate';
        // }


            // swal({
            //     title: "Reward",
            //     content: span,
            //     confirmButtonText: "Okay",
            //     allowOutsideClick: "true"
            // });
        $('.rewMsk').fadeIn();
        showLoader();
        $(".rewPop").load(location.href + " .rewPop");
        setTimeout(function(){
            $('.rewPop').each(function(){

                    $(this).fadeIn();

            })
            hideLoader();
        },2000);

    })
}


function openComment(hid){ console.log("clicking");
    showLoader();
    $.post(base_url+"getRemark",{hid:hid},function(rs){
         console.log(rs); hideLoader();
        var obj = $.parseJSON(rs);
        //show popup
        $('.pcomment').html(obj.remark_text);
        $('.rmMask').fadeIn();
        $('.pcommentBox').fadeIn();
    });
}

// function uploadQuestIntroFile(){
//     var file = $('.introFile').prop("files")[0];
//     var form_data = new FormData();
//     form_data.append("file",file);
//     showLoader();
//         $.ajax({
//             url: base_url+"uploadTmpIntroFile",
//             type: "POST",
//             data: form_data,
//             enctype: 'multipart/form-data',
//             contentType: false,
//             cache: false,
//             processData:false,
//             success: function(rs){ hideLoader();
//                 var obj = $.parseJSON(rs);
//                 app.saveDataPopIntro('file')
//             }
//         });
//     }

function filterHistory(str){
    var all= $('#set1:checkbox:checked').length; console.log("all",all);
    //var fail = $('#set2:checkbox:checked').length;
    var fail = $('#set5:input:checkbox:checked').length; console.log("fail",fail);

	var coupon = $('#set2:checkbox:checked').length; console.log("coupon",coupon);
	var cert = $('#set3:checkbox:checked').length; console.log("cert",cert);
	var other = $('#set4:checkbox:checked').length; console.log("other",other);

    if(fail==0 || coupon==0 || cert==0 || other==0){
        $('body #set1').prop("checked",false);
    }

    if(all!=0){ console.log("here clicking",$('.set2').length);
        	$('.hstBx').each(function(){
        		$(this).show();
            });
            $('body #set2').prop("checked",true);
            $('body #set3').prop("checked",true);
            $('body #set4').prop("checked",true);
            $('body #set5').prop("checked",true);

        }else if(str=='all' && all==0){
            $('body #set2').prop("checked",false);
            $('body #set3').prop("checked",false);
            $('body #set4').prop("checked",false);
            $('body #set5').prop("checked",false);
        }



        if(fail!=0){
            	$('.hstBx').each(function(){
            		if($(this).attr('status')=='0'){
            			$(this).show();
            		}
            	})
            }else{
                $('.hstBx').each(function(){
            		if($(this).attr('status')=='0'){ console.log("hiding");
            			$(this).hide();
            		}
            	})
            }

        if(coupon!=0){
            	$('.hstBx').each(function(){
            		if($(this).attr('status')=='coupon'){
            			$(this).show();
            		}
            	})
            }else{
                $('.hstBx').each(function(){
            		if($(this).attr('status')=='coupon'){
            			$(this).hide();
            		}
            	})
            }

        if(cert!=0){
            	$('.hstBx').each(function(){
            		if($(this).attr('status')=='4'){
            			$(this).show();
            		}
            	})
            }else{
                $('.hstBx').each(function(){
            		if($(this).attr('status')=='4'){
            			$(this).hide();
            		}
            	})
            }
            if(other!=0){
                	$('.hstBx').each(function(){
                		if(($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='') && ($(this).attr('status')!='0')){
                			$(this).show();
                		}
                	})
                }else{
                    $('.hstBx').each(function(){
                		if(($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='') && ($(this).attr('status')!='0')){
                			$(this).hide();
                		}
                	})
                }
	// if(all!=0){
	// 	$('.hstBx').each(function(){
	// 		$(this).show();
    //     });
    //     $('#set2').click();
    //     $('#set3').click();
    //     $('#set4').click();
    //     $('#set5').click();

	// }else if(fail!=0 && coupon==0 && cert==0 && other==0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='0'){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail==0 && coupon!=0 && cert==0 && other==0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='coupon'){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail==0 && coupon==0 && cert!=0 && other==0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='4'){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail==0 && coupon==0 && cert==0 && other!=0){
	// 	$('.hstBx').each(function(){
	// 		if(($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail!=0 && coupon!=0 && cert==0 && other==0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='0' || $(this).attr('status')=='coupon'){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail!=0 && coupon==0 && cert!=0 && other==0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='0' || $(this).attr('status')=='4'){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail!=0 && coupon==0 && cert==0 && other!=0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='0' && ($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail==0 && coupon!=0 && cert!=0 && other==0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='coupon' || $(this).attr('status')=='4'){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail==0 && coupon!=0 && cert==0 && other!=0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='coupon' && ($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }else if(fail==0 && coupon==0 && cert!=0 && other!=0){
	// 	$('.hstBx').each(function(){
	// 		if($(this).attr('status')=='4' && ($(this).attr('status')!='4') && ($(this).attr('status')!='coupon') && ($(this).attr('status')!='')){
	// 			$(this).show();
	// 		}else{
	// 			$(this).hide();
	// 		}
	// 	})
	// }
}

function shareData(title,text,url){
    if(navigator.share){
        navigator.share({
            title: title,
            text: text,
            url: url,
        });
    }else{
        var data =url;
        console.log("desktop detect");
        var dummy = document.createElement("textarea");
        // to avoid breaking orgain page when copying more words
        // cant copy when adding below this code
        // dummy.style.display = 'none'
        document.body.appendChild(dummy);
        //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
        dummy.value = data;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(low);

        iqwerty.toast.toast('Copied');
    }
}

function copyGroupInfo(name,id){ console.log("called ",name,id);
    var show_id = parseInt(1000 + parseInt(id)); console.log("show id",show_id);
    show_id = show_id.toString();
    show_id = 'G' + show_id;
    var group_url = base_url+'group-details/'+id;
    var data = name+' ('+show_id+') \n'+ group_url;

    var dummy = document.createElement("textarea");
    // to avoid breaking orgain page when copying more words
    // cant copy when adding below this code
    // dummy.style.display = 'none'
    document.body.appendChild(dummy);
    //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
    dummy.value = data;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);

    iqwerty.toast.toast('Copied');
}

function deletGroup(id){
    $.post(base_url+"delete_group/"+id,function(rs){ console.log(rs);
        if(rs==1){
            $('.rmv_'+id).slideUp();
        }else{
            swal("Error","Something went wrong!","error");
        }
    })
}

function logout(){
    if(typeof gapi !='undefined'){
        gapi.auth2.getAuthInstance().signOut().then(function() {
            window.location.href=base_url+"logout";
        });
    }else{
        window.location.href=base_url+"logout";
    }
}

function publishExamToGroup(xmid){
    // $( ".gpOpn" ).click(function() {
        $( "#mask" ).fadeIn();
        $( "#togroup" ).addClass( "open" );
        localStorage.setItem("publishXmId",xmid);
    //   });

}

function removeExamFromGroup(gid,id){
    var conf = confirm("Are you sure?");
    if(conf==true){
        $.post(base_url+"removeExamFromGroup",{gid:gid,id:id},function(rs){
            if(rs==1){
                $('.xmrm_'+id).remove();
                iqwerty.toast.toast("Exam Removed");
            }else{
                iqwerty.toast.toast("Something went wrong!");
            }
        })
    }
}

function showGroupDetails(gid){
    $.post(base_url+"getGroupInfo",{gid:gid},function(rs){
        // $( ".gDtlOpn" ).click(function() {
            var obj = $.parseJSON(rs);
            console.log(obj);
            var dp = base_url+'images/prf.png';
            if(obj.user_info.profile_photo!='' && obj.user_info.profile_photo!=null){
                if(obj.user_info.profile_photo.indexOf('https://') != -1){
                    dp = obj.user_info.profile_photo;
                }else{
                    dp = base_url+'api/uploads/'+obj.user_info.profile_photo;
                }
            }
            $('.owner_img').attr("src",dp);
            $('.owner_name').text(obj.user_info.name);
            $('.create_date').text(obj.group_info.time);
            $('.total_quest').text(obj.group_info.total_quest);
            $('.total_follower').text(obj.group_info.total_follower)

            var grp_img = base_url+"images/placeholder.jpeg";
            if(obj.group_info.group_image!='' && obj.group_info.group_image!=null){
                grp_img = base_url+"api/uploads/"+obj.group_info.group_image;
            }
            $('.grp_img').attr("src",grp_img);
            $('.group_name').text(obj.group_info.name +' - '+obj.group_info.group_id);

            if(obj.group_info.owner==1){
                $('.gpsswrdArea').hide();
            }else{
                $('.gpsswrdArea').show();
            }

            toGrplose()
            $( "#mask" ).fadeIn();
            $( "#togdtl" ).addClass( "open" );
        //   });

        localStorage.setItem("publishGroup",gid);

    })
}

function togdtlclose() {
    $( "#togdtl" ).removeClass( "open" );
  };

function searchGroup(val){
    if(val!=''){
        $('.grp_list').each(function(){
            if($(this).attr('data').toLowerCase().indexOf(val.toLowerCase()) != -1){
                $(this).show();
            }else{
                $(this).hide();
            }
        })
    }else{
        $("#grp_list").load(location.href + " #grp_list");
    }
}

function pubslihedToGroup(){
    var xmid = localStorage.getItem("publishXmId");
    var gid = localStorage.getItem("publishGroup");
    var password = $('.publish_password').val();
    $.post(base_url+"publishedExamToGroup",{xmid:xmid,gid:gid,password:password},function(rs){ console.log(rs);
        $('.mask').click();
        if(rs==1){
            swal("Success","Exam published","success");
        }else{
            swal("Error",rs,"error");
        }
    })
}

function addExamToGroupEdit(gid){
    var all_exam = [];
    var htmls = '';
    $('.xm_list').each(function(){
        if($(this).prop("checked")==true){
            all_exam.push($(this).attr("value"));
            var xmname = $(this).attr('xmname');
            var xmimg = $(this).attr('xmimg');
            var id = $(this).attr('value');
            var xmid = (parseInt($(this).attr('value')) + 1000 );

            var group_id = parseInt(parseInt(gid) + 1000);
            group_id = 'G' + group_id;
            htmls += '<li class="xmrm_'+id+'"> <span class="gqlDlt" onclick="removeExamFromGroup(&#039;'+group_id+'&#039;,'+id+')">X</span>'+
                        '<label>'+
                            '<div class="lblIn">'+
                                '<div class="gqlImg"><img src="'+xmimg+'"/></div>'+
                                '<div class="gqlT1">CODE: <b>'+xmid+'</b></div>'+
                                '<div class="gqlT2">'+xmname+'</div>'+
                            '</div>'+
                        '</label>'+
                    '</li>';
        }
    });

    if(all_exam.length>0){
        var all_xm = all_exam.join();
        var gid = $('.group_id').val();
        $.post(base_url+"pushExamToGroupFromEdit",{all_xm:all_xm,gid:gid},function(rs){
            if(rs==1){
                $('.userSelectGrp').append(htmls);

                $('.exam_list_val').val(all_xm);

                console.log(all_exam);
                $('#grpStPop').fadeOut();
            }else{
                swal("Error","Something went wrong!","error");
            }
        })
    }else{
        $('#grpStPop').fadeOut();
    }

}

function addExamToGroup(){
    var all_exam = [];
    var htmls = '';
    $('.xm_list').each(function(){
        if($(this).prop("checked")==true){
            all_exam.push($(this).attr("value"));
            var xmname = $(this).attr('xmname');
            var xmimg = $(this).attr('xmimg');
            var id = $(this).attr('value');
            var xmid = (parseInt($(this).attr('value')) + 1000 );
            htmls += '<li class="removeXm_'+id+'"> <span class="gqlDlt" onclick="rmExm('+id+')">X</span>'+
                        '<label>'+
                            '<div class="lblIn">'+
                                '<div class="gqlImg"><img src="'+xmimg+'"/></div>'+
                                '<div class="gqlT1">CODE: <b>'+xmid+'</b></div>'+
                                '<div class="gqlT2">'+xmname+'</div>'+
                            '</div>'+
                        '</label>'+
                    '</li>';
        }
    })

    $('.userSelectGrp').append(htmls);
    var all_xm = all_exam.join();
    $('.exam_list_val').val(all_xm);

    console.log(all_exam);
    $('#grpStPop').fadeOut();
}

function rmExm(id){
    $('.removeXm_'+id).fadeOut();
}

function UpdateGroup(){
    var gid = $('.group_id').val();
    var grp_img = $('.group_image').val();
    var title = $('.group_title').val();
    //var group_parent = $('.group_parent').val();
    var group_info = $('.group_info').val();
    var group_pass = $('.group_pass').val();
    var group_tags = $('.group_tags').val();
    var is_private = 0;
    if($('#grivate').prop("checked")==true){
        is_private = 1;
    }
    var exams = $('.exam_list_val').val();

    if(title=='' || group_info=='' || group_pass=='' || group_tags==''){
        swal("Warning","All fields are mandatory","warning");
    }else{
        var form_data = new FormData();
        form_data.append("grp_img",grp_img);
        form_data.append("title",title);
        //form_data.append("group_parent",group_parent);
        form_data.append("group_info",group_info);
        form_data.append("group_pass",group_pass);
        form_data.append("group_tags",group_tags);
        form_data.append("is_private",is_private);
        form_data.append("exams",exams);
        form_data.append("gid",gid);
        showLoader();
        $.ajax({
            url: base_url+"UpdateGroup",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData:false,
            success: function(rs){
                console.log(rs);
                if(rs==1){
                    window.location.href=base_url+'profile';
                }else{
                    hideLoader();
                    swal("Error","Something went wrong!","error");
                }
            }
        })
    }
}

function saveGroup(){

    var grp_img = $('.group_image').val();
    //var grp_img = $('.group_img').prop("files")[0];
    var title = $('.group_title').val();
    //var group_parent = $('.group_parent').val();
    var group_info = $('.group_info').val();
    var group_pass = $('.group_pass').val();
    var group_tags = $('.group_tags').val();
    var is_private = 0;
    if($('#grivate').prop("checked")==true){
        is_private = 1;
    }
    var exams = $('.exam_list_val').val();

    if(title=='' || group_info=='' || group_pass=='' || group_tags==''){
        swal("Warning","All fields are mandatory","warning");
    }else{
        var form_data = new FormData();
        form_data.append("grp_img",grp_img);
        form_data.append("title",title);
        //form_data.append("group_parent",group_parent);
        form_data.append("group_info",group_info);
        form_data.append("group_pass",group_pass);
        form_data.append("group_tags",group_tags);
        form_data.append("is_private",is_private);
        form_data.append("exams",exams);
        showLoader();
        $.ajax({
            url: "saveGroup",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData:false,
            success: function(rs){
                console.log(rs);
                if(rs==1){
                    window.location.href=base_url;
                }else{
                    hideLoader();
                    swal("Error","Something went wrong!","error");
                }
            }
        })
    }
}

function followGroup(obj,gid,dicover=''){
    $.post(base_url+"follow-group",{gid:gid},function(rs){
        if(rs==1){
            $(obj).text("UNFOLLOW");
            if(dicover==1){
                $(obj).removeClass("flwd");
            }else{
                $(obj).removeClass("flw");
            }
            if(dicover==1){
                $(obj).attr("onclick","unfollowGroup(this,"+gid+",1)");
            }else if(dicover==2){
                $(obj).attr("onclick","unfollowGroup(this,"+gid+",2)");
                $(obj).removeAttr("style");
            }else{
                $(obj).attr("onclick","unfollowGroup(this,"+gid+")");
            }
        }
    })
}

function unfollowGroup(obj,gid,dicover=''){
    $.post(base_url+"unfollow-group-ajax",{gid:gid},function(rs){
        if(rs==1){
            $(obj).text("FOLLOW");
            if(dicover==1){
                $(obj).addClass("flwd");
            }else{
                $(obj).addClass("flw");
            }
            if(dicover==1){
                $(obj).attr("onclick","followGroup(this,"+gid+",1)");
            }else if(dicover==2){
                $(obj).css("background","#F232A4");
                $(obj).css("color","#fff");
                $(obj).css("border","1px solid #F232A4");
                $(obj).attr("onclick","followGroup(this,"+gid+",2)");

            }else{
                $(obj).attr("onclick","followGroup(this,"+gid+")");
            }
        }
    });
}

function searchGroupDiscover(val){
    if(val==''){
        revertHtml();
    }else{
        $.post(base_url+"searchGroup",{val:val},function(rs){
            var obj = $.parseJSON(rs);
            var html = '';
            for(var i=0;i<obj.length;i++){
                var follow_html ='';
                if(obj[i].is_follow==1){
                    follow_html = '<span class="unflwBtn" onclick="unfollowGroup(this,'+obj[i].id+',2)">UNFOLLOW</span>';
                }else{
                    follow_html = '<span class="unflwBtn" style="background:#F232A4; color: #fff; border: 1px solid #F232A4" onclick="followGroup(this,'+obj[i].id+',2)">FOLLOW</span>';
                }
                var grp_img = '';
                if(obj[i].group_image!=''){
                    grp_img = base_url+'api/uploads/'+obj[i].group_image;
                }else{
                    grp_img = base_url+'images/placeholder.jpeg';
                }
                html += '<li class="rmvGrp_">'+
                            '<div class="flowBx1">'+

                                '<div class="qsRow1 q4c">'+
                                    '<div class="flowBx5">'+
                                    '<a href="'+base_url+'group-details/'+obj[i].id+'"> <span style="right:130px" class="clqBx"></span> </a>'+

                                        '<img src="'+grp_img+'" style="width:100%">'+
                                    '</div>'+
                                    '<div class="flowBx2">'+
                                        '<div class="qsTxt2 q4a">'+
                                            'CODE: <b>'+obj[i].group_id+'</b> <span class="q4Pass">Creator: <b>'+obj[i].creator+'</b></span>'+
                                        '</div>'+
                                        '<div class="flowBx3">'+
                                            obj[i].name+
                                        '</div>'+



                                    '<div class="s7btm">'+
                                    '<aside class="flowBx4">'+
                                        '<b>'+obj[i].total_quest+'</b> Quest <br/>'+
                                        '<b>'+obj[i].total_follower+'</b> Followers'+
                                    '</aside>'+

                                    '<aside class="setRnew q4e">'+

                                        follow_html+
                                        '<span class="btnIc q4Dot"><img src="'+base_url+'images/dot_pink.svg">'+

                                        '</span>'+
                                    '</aside>'+
                                    '</div>'+

                                '</div>'+


                                '</div>'+

                            '</div>'+
                        '</li>';
            }
console.log(html);
            $('.quest_list').html(html);
            $('.msksbr').hide();

            $('.qsLst').show();
            $('.qsLst1').show();

            $('.rcmPLst').hide();
            $('.flwngList').hide();
        })
    }
}

function revertHtml(){
    $('.quest_list').html('');
    $('.qsLst').hide();

    $('.rcmPLst').show();
    $('.flwngList').show();
}

function removeExamFromGroup(exam_id,group_id){
    var conf = confirm("Are you sure?");
    if(conf==true){
        $.post(base_url+"removeExamFromGroupInfoPg",{exam_id:exam_id,group_id:group_id},function(rs){ console.log(rs);
            if(rs==1){
                $('.rmv_group_'+group_id).fadeOut();
            }else if(rs==2){
                iqwerty.toast.toast('Unauthorized access');
            }else{
                swal("Error","Something went wrong!","error");
            }
        })
    }
}

function sendNotificationGroup(id){
    var msg = $('.notification').val();
    var title=$('.notification_title').val();
    var is_news = 0;
    if($('input[name="nwsBx"]:checked').length > 0){
        is_news=1;
    }
    if(msg!=''){
        $.post(base_url+"sendNotificationGroupMember",{id:id,msg:msg,title:title,is_news:is_news},function(rs){ console.log("noti",rs);

        $('.notification_title').val('');
        $('.notification').val('');
            closePop();
            $('.pcancel').click();
        })
    }else{
        closePop();
        $('.pcancel').click();
    }
}

function toGrplose() {
    $( "#togroup" ).removeClass( "open" );
  };

$(".nmbrOnly").keydown(function(event) {
    // Allow only backspace and delete
    if ( event.keyCode == 46 || event.keyCode == 8 ) {
        // let it happen, don't do anything
    }
    else {
        // Ensure that it is a number and stop the keypress
        if (event.keyCode < 48 || event.keyCode > 57 ) {
            event.preventDefault();
        }
    }
});
/*
function stopRecording() {
    console.log("stopButton clicked");
    //disable the stop button, enable the record too allow for new recordings
    clearInterval(timer);
    $('.aud_timer').text('00 : 00');
    $('.audIcnBtn').attr("onclick","startRecording()");
    //tell the recorder to stop the recording
    rec.stop(); //stop microphone access


    gumStream.getAudioTracks()[0].stop();
    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);
    rec.clear();
}

function createDownloadLink(blob) {
    var url = URL.createObjectURL(blob);
    var au = document.createElement('audio');
    var li = document.createElement('li');
    var link = document.createElement('a');
    //add controls to the <audio> element
    au.controls = true;
    au.src = url;
    //link the a element to the blob
    link.href = url;
    link.download = new Date().toISOString() + '.wav';
    link.innerHTML = link.download;
    //add the new audio and a elements to the li element
    li.appendChild(au);
    li.appendChild(link);
    //add the li element to the ordered list
    //recordingsList.appendChild(li);

    form_data = new FormData();
   console.log("form created");
    var filename = new Date().toISOString()+'.wav';
    localStorage.setItem("intro_draft_audio_name",filename);
    form_data.append("intro_audio_draft",blob,filename);

} */

/**
 * function removeDDTarget(number){
    var conf = confirm('Are you sure?');
    if(conf==true){
        $('.crpTrg'+number).html('<img src="images/image.svg" />');
        $('.target_'+number).val('');
        $('.target_'+number+'_imgdata').val('');
        $('.ddTrgtLi_'+class_name).removeClass("added");
    }
}

function removeDDTargetEdit(number){
    var conf = confirm('Are you sure?');
    if(conf==true){
        $('.crpTrg'+number).html('<img src="../images/image.svg" />');
        $('.target_'+number).val('');
        $('.target_'+number+'_imgdata').val('');
        $('.ddTrgtLi_'+class_name).removeClass("added");
    }
}

function showTargetAns(type,number){
    if(type=='text'){
        $('.ddItbtn_'+number).hide();
        $('.ddAnsTxt_'+number).slideDown();
    }else if(type=='image'){
        $('.ddAnsTxt_'+number).slideUp();
        //cliclk image upload
        $('.ddTgAnsImg_'+number).click();
    }
}

function removeTargetAns(number){
    $('.ddTgAnsTxt_'+number).val('');
    $('.ddAnsTxt_'+number).slideUp();
    $('.ddAnsImg_'+number).slideUp();
    $('.ddItbtn_'+number).show();
    $('.ddTgAnsImg_'+number).val('');
}

function removeEntireDDAns(number){
    var conf = confirm("Are you sure?");
    if(conf==true){
        $('.ddTgAnsTxt_'+number).val('');
        $('.ddAnsTxt_'+number).slideUp();
        $('.ddAnsImg_'+number).slideUp();
        $('.ddItbtn_'+number).show();
        $('.ddTgAnsImg_'+number).val('');

        $('.crpTrg'+number).html('<img src="'+base_url+'images/image.svg" />');
        $('.target_'+number).val('');
        $('.target_'+number+'_imgdata').val('');
        $('.ddTrgtLi_'+number).removeClass("added");
    }
}

function readDDImgAns(input,number){

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.ddAnsImg_'+number).html('<div class="ddImgFld"> <div class="ddImgDlt" onclick="removeTargetAns('+number+')">X</div>'+
                                '<div class="ddImFCrop"><img src="'+e.target.result+'"></div>'+
                            '</div>');
               $('.ddAnsImg_'+number).show();

                $('.ddTgAnsTxt_'+number).val('');
               $('.ddAnsTxt_'+number).slideUp();
               $('.ddItbtn_'+number).slideUp();
            };

            reader.readAsDataURL(input.files[0]);
        }
}

function removeDDQsnImg(){
    $('.quest_image').val();
    $('.ddImageDisplay').html('<span class="wgIdlt" onclick="removeDDQsnImg()">X</span>'+
                                    '<img src="images/image.svg">');
}
function removeDDQsnImgEdit(){
    $('.quest_image').val();
    $('.ddImageDisplay').html('<span class="wgIdlt" onclick="removeDDQsnImgEdit()">X</span>'+
                                    '<img src="../images/image.svg">');
}


 */
